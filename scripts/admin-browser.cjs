const { chromium } = require('playwright');
const assert = require('node:assert/strict');
const { spawnSync } = require('node:child_process');
const { randomBytes } = require('node:crypto');
const fs = require('node:fs');
const path = require('node:path');

const baseUrl = process.env.BASE_URL || 'https://makeamark-southern-ocean.test';
assert(['makeamark-southern-ocean.test', 'localhost', '127.0.0.1'].includes(new URL(baseUrl).hostname), 'Browser fixtures may only target the local app.');
const prefix = `browser-${randomBytes(8).toString('hex')}`;
const out = process.env.OUT_DIR || '/tmp/makeamark-admin-screenshots';
let stage = 'initialise';
let credentials = [];

function fixture(action, extra = {}) {
    const result = spawnSync('php', [path.join(__dirname, 'admin-browser-fixtures.php')], {
        cwd: path.join(__dirname, '..'),
        input: JSON.stringify({ prefix, action, ...extra }), encoding: 'utf8',
    });
    assert.equal(result.status, 0, redact(result.stderr || 'Fixture helper failed.'));
    try { return JSON.parse(result.stdout); } catch { throw new Error('Fixture helper returned invalid JSON.'); }
}

function redact(value) {
    return credentials.reduce((text, secret) => text.split(secret).join('[redacted]'), String(value));
}

async function open(page, route) {
    const response = await page.goto(`${baseUrl}${route}`);
    assert.equal(response.status(), 200, `${route} did not load successfully.`);
    await page.waitForLoadState('networkidle');
}

async function login(page, account) {
    await open(page, '/admin/login');
    await page.getByLabel(/Email address/).fill(account.email);
    await page.getByLabel(/Password/).fill(account.password);
    await page.getByRole('button', { name: 'Sign in', exact: true }).click();
    await page.waitForURL('**/admin/organisations');
    await page.waitForLoadState('networkidle');
}

async function choose(page, label, option) {
    const control = page.getByLabel(new RegExp(label.replace(/[.*+?^${}()|[\]\\]/g, '\\$&')))
        .and(page.locator('button, select')).first();
    if (await control.evaluate(el => el.tagName === 'SELECT')) {
        await control.selectOption({ label: option });
    } else {
        await control.click();
        const search = page.locator('input[role="combobox"]:visible');
        if (await search.count() === 1) await search.fill(option.split(' · ')[0]);
        try {
            await page.getByRole('option').filter({ hasText: option }).click();
        } catch (error) {
            const available = await page.getByRole('option').allTextContents();
            throw new Error(`${error.message} Control ${await control.evaluate(el => el.outerHTML)}; visible options ${JSON.stringify(available)}.`);
        }
        if (await control.getAttribute('aria-expanded') === 'true') await page.keyboard.press('Escape');
    }
    await page.waitForLoadState('networkidle');
}

async function save(page) {
    const responsePromise = page.waitForResponse(response => response.request().method() === 'POST' && response.url().includes('/livewire'));
    await page.getByRole('button', { name: 'Save changes', exact: true }).click();
    const response = await responsePromise;
    assert.equal(response.status(), 200, 'Livewire save request failed.');
    await page.waitForLoadState('networkidle');
    if (await page.getByText('This team has changed', { exact: true }).isVisible()) {
        throw new Error('The second consecutive team save was rejected as a stale version.');
    }
    await page.getByText('Saved', { exact: true }).last().waitFor();
    assert.equal(await page.locator('.fi-fo-field-wrp-error-message').count(), 0, 'Form displayed validation errors.');
}

(async () => {
    let browser;
    try {
        const data = fixture('create');
        credentials = Object.values(data.accounts).map(account => account.password);
        browser = await chromium.launch();
        const context = await browser.newContext({ ignoreHTTPSErrors: true, viewport: { width: 1440, height: 1000 } });
        context.setDefaultTimeout(10000);
        const page = await context.newPage();
        const errors = [];
        page.on('pageerror', error => errors.push(redact(error.message)));
        page.on('dialog', dialog => dialog.accept());

        stage = 'ordinary admin login';
        await login(page, data.accounts.admin);
        for (const name of ['Organisations', 'Makers', 'Teams']) {
            assert(await page.getByRole('link', { name, exact: true }).count(), `${name} navigation missing.`);
        }
        assert.equal(await page.getByRole('link', { name: 'Admin accounts', exact: true }).count(), 0);
        const denied = await page.goto(`${baseUrl}/admin/users`);
        assert.equal(denied.status(), 403, 'Ordinary admin could access account management.');

        stage = 'organisation briefing save';
        await open(page, `/admin/organisations/${data.organisation}/edit`);
        await page.getByLabel('Briefing for makers', { exact: true }).fill(`${prefix} revised briefing`);
        await page.getByLabel('Contact and internal notes', { exact: true }).fill(`${prefix} contact reviewed`);
        await save(page);

        stage = 'organisation My review';
        await page.getByRole('button', { name: 'My review', exact: true }).click();
        await choose(page, 'My rating (1–5)', '4');
        await page.getByLabel('My assessment', { exact: true }).fill(`${prefix} independent assessment`);
        await page.getByRole('button', { name: 'Submit', exact: true }).click();
        await page.getByText('Your review is saved', { exact: true }).waitFor();
        fixture('check', { stage: 'organisation' });

        stage = 'maker selection';
        await open(page, `/admin/makers/${data.makers[0]}/edit`);
        await choose(page, 'Status', 'Confirmed');
        await page.getByLabel('Selection notes', { exact: true }).fill(`${prefix} selected in browser`);
        await save(page);
        fixture('check', { stage: 'maker' });

        stage = 'first team edit and maker assignment';
        await open(page, `/admin/teams/${data.team}/edit`);
        await choose(page, 'Makers', `${prefix} maker two · Web design`);
        await page.getByLabel('Team notes', { exact: true }).fill(`${prefix} first browser save`);
        await save(page);
        fixture('check', { stage: 'team-first' });

        stage = 'second team edit without reload';
        await page.getByLabel('Team notes', { exact: true }).fill(`${prefix} second browser save`);
        await save(page);
        fixture('check', { stage: 'team-second' });

        stage = 'profile';
        await open(page, '/admin/profile');
        await page.getByRole('heading', { name: 'Profile', exact: true }).waitFor();

        stage = 'super admin account pages';
        const superContext = await browser.newContext({ ignoreHTTPSErrors: true, viewport: { width: 1440, height: 1000 } });
        const superPage = await superContext.newPage();
        await login(superPage, data.accounts.super);
        await superPage.getByRole('link', { name: 'Admin accounts', exact: true }).click();
        await superPage.waitForURL('**/admin/users');
        await open(superPage, '/admin/users/create');
        await superPage.getByLabel(/Email address/).waitFor();
        await open(superPage, `/admin/users/${data.accounts.admin.id}/edit`);
        await superPage.getByLabel(/Email address/).waitFor();

        stage = 'password reset entry';
        const guestContext = await browser.newContext({ ignoreHTTPSErrors: true });
        const guest = await guestContext.newPage();
        await open(guest, '/admin/login');
        await guest.getByRole('link', { name: 'Forgot password?', exact: true }).click();
        await guest.waitForURL('**/admin/password-reset/request');
        await guest.getByLabel(/Email address/).waitFor();

        stage = 'final screenshot pass';
        fs.mkdirSync(out, { recursive: true });
        await open(page, `/admin/organisations/${data.organisation}/edit`);
        await page.screenshot({ path: path.join(out, 'desktop-organisation.png'), fullPage: true });
        await open(page, `/admin/teams/${data.team}/edit`);
        await page.screenshot({ path: path.join(out, 'desktop-team.png'), fullPage: true });
        await page.setViewportSize({ width: 390, height: 900 });
        await page.reload();
        await page.waitForLoadState('networkidle');
        const overflow = await page.evaluate(() => document.documentElement.scrollWidth - document.documentElement.clientWidth);
        assert.equal(overflow, 0, 'Mobile team page has horizontal overflow.');
        await page.screenshot({ path: path.join(out, 'mobile-team.png'), fullPage: true });
        assert.deepEqual(errors, [], 'Browser JavaScript errors.');
        console.log(`Admin browser verification passed: briefing, independent review, maker selection, two team saves, role restrictions, account pages, profile and reset entry. Screenshots: ${out}`);
    } finally {
        try {
            if (browser) await browser.close();
        } finally {
            fixture('cleanup');
            console.log('Temporary admin browser fixtures removed.');
        }
    }
})().catch(error => {
    console.error(`Admin browser verification failed at ${stage}: ${redact(error.message)}`);
    process.exit(1);
});
