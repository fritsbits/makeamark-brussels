const { chromium } = require('playwright');
const assert = require('node:assert/strict');
const { spawnSync } = require('node:child_process');
const { randomBytes } = require('node:crypto');
const fs = require('node:fs');
const path = require('node:path');
const stubTurnstile = require('./turnstile-stub.cjs');

const base = process.env.BASE_URL || 'http://127.0.0.1:8049';
assert(['localhost', '127.0.0.1'].includes(new URL(base).hostname), 'Use an isolated local app.');
const out = process.env.OUT_DIR || '/tmp/makeamark-spam-check';
const prefix = `browser-${randomBytes(8).toString('hex')}`;
function fixture(action) {
    const result = spawnSync('php', [path.join(__dirname, 'admin-browser-fixtures.php')], { input: JSON.stringify({ action, prefix }), encoding: 'utf8' });
    assert.equal(result.status, 0, 'Local fixture setup/cleanup failed.');
    return JSON.parse(result.stdout);
}
async function fill(page, audience, email) {
    const values = {
        email, phone: '0470000000',
        ...(audience === 'organisations'
            ? { organisation_name: 'Browser Youth', contact_name: 'José', mission: 'Support youth.', challenge: 'Better communication.' }
            : { name: 'José', portfolio: 'https://example.com/work' }),
        ...(audience === 'makers' ? { motivation: 'Help local projects.', main_skills: 'Design', meeting_availability: 'Evenings' } : {}),
    };
    for (const [name, value] of Object.entries(values)) await page.locator(`[name="${name}"]`).fill(value);
    for (const control of await page.locator('ui-checkbox[required]').all()) await control.click();
    if (audience === 'organisations') {
        for (const name of ['languages[]', 'organisation_languages[]']) await page.locator(`ui-checkbox[name="${name}"]`).first().click();
    }
    if (audience === 'volunteers') {
        await page.locator('ui-checkbox[name="roles[]"][value="photographer"]').click();
        await page.locator('ui-checkbox[name="day_parts[]"]').first().click();
    }
}
(async () => {
    fs.mkdirSync(out, { recursive: true });
    const accounts = fixture('create').accounts;
    const browser = await chromium.launch();
    const observations = [];
    let longContext, longPage, longStarted;
    try {
        for (const width of process.env.SPAM_RECOVERY_ONLY ? [1440] : [1440, 390, 320]) {
            const context = await browser.newContext({ ignoreHTTPSErrors: true, viewport: { width, height: 900 } });
            const page = await context.newPage();
            page.setDefaultTimeout(60000);
            const errors = [];
            page.on('pageerror', error => errors.push(error.message));
            await stubTurnstile(page);
            await page.goto(`${base}/admin/login`);
            await page.getByLabel(/Email address/).fill(accounts.admin.email);
            await page.getByLabel(/Password/).fill(accounts.admin.password);
            await page.getByRole('button', { name: 'Sign in', exact: true }).click();
            await page.waitForURL('**/admin/organisations', { waitUntil: 'domcontentloaded' }).catch(async () => {
                throw new Error(`Local login failed at ${page.url()}: ${await page.locator('body').innerText()}`);
            });
            if (width === 1440) {
                longContext = await browser.newContext({ ignoreHTTPSErrors: true, storageState: await context.storageState() });
                longPage = await longContext.newPage();
                await stubTurnstile(longPage);
                await longPage.goto(`${base}/makers`);
                await fill(longPage, 'makers', `${prefix}-long@example.invalid`);
                longStarted = Date.now();
            }
            if (process.env.SPAM_RECOVERY_ONLY) {
                await context.close();
                break;
            }
            for (const audience of ['makers', 'organisations', 'volunteers']) {
                await page.goto(`${base}/${audience}`);
                await page.waitForLoadState('networkidle');
                const form = page.locator('.application-form');
                assert.equal((await form.locator('[name="_submission_token"]').inputValue()).length, 97);
                assert.equal(await form.locator('[name="website_confirmation"]').isVisible(), false);
                const email = `${prefix}-${audience}-${width}@example.invalid`;
                await fill(page, audience, email);
                await page.locator('[name="email"]').focus();
                await page.keyboard.press('Tab');
                assert.equal(await page.evaluate(() => document.activeElement.name === 'website_confirmation'), false);
                await page.waitForTimeout(3100);
                await page.evaluate(() => { window.verificationFailure = true; });
                await form.locator('[type="submit"]').click();
                await page.locator('[data-verification-error]').waitFor({ state: 'visible' });
                assert.equal(await page.locator('[name="email"]').inputValue(), email);
                assert.equal(await page.locator('[type="submit"]').isDisabled(), false);
                assert.equal(await page.locator('[data-verification-error]').evaluate(el => document.activeElement === el), true);
                const overflow = await page.evaluate(() => document.documentElement.scrollWidth - innerWidth);
                assert.equal(overflow, 0, `${audience} at ${width}px overflows`);
                if (process.env.CAPTURE_SPAM_SCREENSHOTS) await page.screenshot({ path: path.join(out, `${audience}-${width}.png`), fullPage: true });
                await page.evaluate(() => { window.verificationFailure = false; });
                // Only desktop saves reach Laravel. Other viewports intercept production-like POSTs.
                if (width !== 1440) await page.route(`**/${audience}/apply`, route => route.fulfill({ status: 204, body: '' }));
                if (width === 1440 && audience === 'makers') {
                    // Server-only validation failure, followed by a fresh token and retained answers.
                    await form.evaluate(el => { el.noValidate = true; el.querySelector('[name="email"]').value = 'invalid'; });
                    await form.locator('[type="submit"]').click();
                    await page.waitForURL(`**/${audience}#application`);
                    await page.locator('.application-form__errors:not([data-verification-error])').waitFor({ state: 'visible' });
                    assert.equal(await page.locator('[name="name"]').inputValue(), 'José');
                    await page.locator('[name="email"]').fill(email);
                    await page.waitForTimeout(3100);
                }
                await page.locator('[type="submit"]').click();
                if (width === 1440) {
                    await page.getByText('Application received.', { exact: true }).waitFor({ timeout: 8000 }).catch(async () => {
                        throw new Error(`${audience}: ${await page.locator('.application-form__errors:not([data-verification-error])').allTextContents()}`);
                    });
                    await page.goto(`${base}/admin/${audience}`);
                    const searched = page.waitForResponse(response => response.url().includes('/livewire') && response.request().method() === 'POST');
                    await page.locator('input[type="search"]').fill(email);
                    await searched;
                    await page.waitForFunction(() => document.querySelectorAll('table tbody tr').length === 1);
                    await page.locator('table tbody tr a[href*="/edit"]').first().click();
                    await page.getByText(email, { exact: true }).waitFor();
                } else {
                    await page.waitForTimeout(100);
                    assert.equal(await page.locator('[type="submit"]').isDisabled(), true);
                }
                observations.push({ audience, width, overflow, saved: width === 1440 });
                console.log(`${audience} ${width}px passed`);
            }
            assert.deepEqual(errors, []);
            await context.close();
        }
        const context = await browser.newContext({ ignoreHTTPSErrors: true, javaScriptEnabled: false });
        const page = await context.newPage();
        await page.goto(`${base}/volunteers`);
        const notice = page.locator('noscript').first();
        assert.match(await notice.textContent(), /This form needs JavaScript for spam protection/);
        assert.equal(await notice.locator('p').isVisible(), true);
        assert.equal(await notice.locator('a[href="mailto:hello@letsmakeamark.brussels"]').count(), 1);
        const metadata = Object.fromEntries(await page.locator('input[type="hidden"]').evaluateAll(inputs => inputs.map(input => [input.name, input.value])));
        await page.waitForTimeout(3100);
        const rejection = await context.request.post(`${base}/volunteers/apply`, { form: { ...metadata, website_confirmation: '', name: 'Direct Bot', email: `${prefix}-direct@example.invalid`, phone: '0470000000', 'roles[]': 'photographer', portfolio: 'https://example.com', date_confirmed: '1', 'day_parts[]': 'daytime' } });
        assert.match(await rejection.text(), /couldn&#039;t verify|couldn.t verify/);
        await context.close();
        const remaining = Math.max(0, 301000 - (Date.now() - longStarted));
        console.log(`Browser matrix passed; waiting ${Math.ceil(remaining / 1000)}s for the real five-minute form check.`);
        await longPage.waitForTimeout(remaining);
        assert.equal(await longPage.evaluate(() => window.verificationExecutions || 0), 0, 'no token spent while writing');
        await longPage.locator('[type="submit"]').click();
        await longPage.getByText('Application received.', { exact: true }).waitFor();
        observations.push({ longFormWaitSeconds: Math.floor((Date.now() - longStarted) / 1000), saved: true });
        await longContext.close();
        fs.writeFileSync(path.join(out, 'observations.json'), JSON.stringify(observations, null, 2));
        console.log(`${process.env.SPAM_RECOVERY_ONLY ? 'Passed no-JS rejection and the real five-minute form check' : 'Passed all audiences at 1440/390/320px, keyboard, failure/retry, single POST, server validation recovery, saves/admin records, no-JS and five-minute form'}. Evidence: ${out}`);
    } finally {
        await browser.close();
        fixture('cleanup');
    }
})().catch(error => { console.error(error); process.exit(1); });
