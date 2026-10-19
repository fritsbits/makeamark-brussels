const { chromium } = require('playwright');
const assert = require('node:assert/strict');
const fs = require('node:fs');
const path = require('node:path');

const baseUrl = process.env.BASE_URL || 'https://makeamark-southern-ocean.test';
const out = process.env.OUT_DIR || '/tmp/makeamark-laravel-screenshots';
fs.mkdirSync(out, { recursive: true });

(async () => {
    const browser = await chromium.launch();
    try {
        for (const [name, width] of [['desktop', 1440], ['mobile', 390]]) {
            const context = await browser.newContext({ ignoreHTTPSErrors: true, viewport: { width, height: 900 } });
            const page = await context.newPage();
            const errors = [];
            page.on('pageerror', error => errors.push(error.message));
            page.on('response', response => {
                if (response.status() >= 400) errors.push(`${response.status()} ${response.url()}`);
            });
            await page.goto(baseUrl);
            await page.waitForLoadState('networkidle');
            await page.evaluate(async () => {
                for (const image of document.images) image.loading = 'eager';
                await Promise.all([...document.images].map(image => image.decode()));
                await document.fonts.ready;
            });
            assert.equal(await page.locator('h1').count(), 1);
            assert.equal(await page.locator('.audience-panel').count(), 2);
            const overflow = await page.evaluate(() => document.documentElement.scrollWidth - document.documentElement.clientWidth);
            assert.equal(overflow, 0, `${name}: horizontal overflow`);
            if (name === 'mobile') {
                const toggle = page.getByRole('button', { name: 'Menu', exact: true });
                await toggle.click();
                assert.equal(await toggle.getAttribute('aria-expanded'), 'true');
                await page.locator('#nav-links a').first().click();
                await page.waitForURL('**/makers');
                assert.equal(await toggle.getAttribute('aria-expanded'), 'false');
                await page.goto(baseUrl);
                await page.waitForLoadState('networkidle');
            }
            assert.equal(await page.getByRole('heading', { name: 'How it works', exact: true }).count(), 0);
            assert.equal(await page.locator('.faq__item, .strip__list').count(), 0);
            assert.equal(await page.locator('.photo-reveal').count(), 13);
            const photo = page.locator('.photo-reveal').first();
            await page.waitForFunction(() => !document.querySelector('.photo-reveal').disabled);
            await photo.focus();
            await page.keyboard.press('Enter');
            assert.equal(await photo.getAttribute('aria-pressed'), 'true');
            await page.keyboard.press('Escape');
            assert.equal(await photo.getAttribute('aria-pressed'), 'false');
            await page.evaluate(() => { document.activeElement.blur(); window.scrollTo({ top: 0, behavior: 'instant' }); });
            await page.screenshot({ path: path.join(out, `${name}.png`), fullPage: true });
            for (const route of ['/makers', '/organisations', '/makers/apply', '/organisations/apply']) {
                await page.goto(`${baseUrl}${route}`);
                await page.waitForLoadState('networkidle');
                assert.equal(await page.locator('h1').count(), 1, `${route}: one page heading`);
                const audienceRoute = route.replace('/apply', '');
                assert.equal(await page.locator('link[rel="canonical"]').getAttribute('href'), `https://letsmakeamark.brussels${audienceRoute}`);
                assert.equal(await page.locator('#application form').count(), 1, `${route}: embedded form`);
                if (route.endsWith('/apply')) {
                    assert.equal(new URL(page.url()).hash, '#application', `${route}: old link jumps to form`);
                }
                const routeOverflow = await page.evaluate(() => document.documentElement.scrollWidth - document.documentElement.clientWidth);
                assert.equal(routeOverflow, 0, `${name} ${route}: horizontal overflow`);
            }
            await page.goto(`${baseUrl}/makers`);
            await page.getByRole('link', { name: 'Start your application', exact: true }).click();
            await page.waitForURL('**/makers#application');
            await page.waitForTimeout(500);
            await page.screenshot({ path: path.join(out, `${name}-maker-form.png`) });
            await page.locator('input[name="name"]').fill('Validation check');
            await page.locator('input[name="email"]').fill('invalid');
            await page.locator('#application form').evaluate(form => { form.noValidate = true; });
            await page.getByRole('button', { name: 'Send maker application' }).click();
            await page.waitForURL('**/makers#application');
            await page.waitForLoadState('networkidle');
            assert.equal(await page.locator('.application-form__errors').count(), 1, `${name}: inline validation errors`);
            assert.equal(await page.locator('input[name="name"]').inputValue(), 'Validation check');
            assert.equal(await page.locator('input[name="email"]').inputValue(), 'invalid');
            assert.deepEqual(errors, [], `${name}: browser errors`);
            console.log(`${name}: navigation, six images, keyboard reveal and audience/application routes passed; overflow ${overflow}px; ${out}/${name}.png`);
            await context.close();
        }
    } finally {
        await browser.close();
    }
})().catch(error => { console.error(error); process.exit(1); });
