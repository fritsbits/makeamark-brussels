const { chromium } = require('playwright');
const assert = require('node:assert/strict');
const fs = require('node:fs');
const path = require('node:path');

const baseUrl = process.env.BASE_URL || 'https://makeamark-southern-ocean.test';
const out = process.env.OUT_DIR || '/tmp/makeamark-messaging-preview';
fs.mkdirSync(out, { recursive: true });

(async () => {
    const browser = await chromium.launch();
    try {
        for (const width of [1440, 390]) {
            const context = await browser.newContext({ ignoreHTTPSErrors: true, viewport: { width, height: 1000 } });
            const page = await context.newPage();
            const errors = [];
            page.on('pageerror', error => errors.push(error.message));
            page.on('response', response => { if (response.status() >= 400) errors.push(`${response.status()} ${response.url()}`); });

            for (const route of ['/tools/messaging', '/tools/messaging/makers', '/tools/messaging/organisations']) {
                const response = await page.goto(`${baseUrl}${route}`);
                assert.equal(response.status(), 200, route);
                assert.equal(response.headers()['x-robots-tag'], 'noindex, nofollow');
                await page.waitForLoadState('networkidle');
                await page.evaluate(async () => {
                    for (const image of document.images) image.loading = 'eager';
                    await Promise.all([...document.images].map(image => image.decode()));
                    await document.fonts.ready;
                });
                assert.equal(await page.locator('h1').count(), 1);
                assert.equal(await page.evaluate(() => document.documentElement.scrollWidth > innerWidth), false, `${width}px ${route}: overflow`);
                if (route === '/tools/messaging') {
                    assert.equal(await page.locator('.photo-reveal').count(), 13, 'Two hero images and eleven carousel images');
                    if (width === 390) {
                        const toggle = page.getByRole('button', { name: 'Menu', exact: true });
                        await toggle.click();
                        await page.locator('#nav-links').getByRole('link', { name: 'For makers', exact: true }).click();
                        await page.waitForURL('**/makers');
                        assert.equal(await page.getByRole('button', { name: 'Menu', exact: true }).getAttribute('aria-expanded'), 'false');
                        await page.goto(`${baseUrl}/tools/messaging`);
                        await page.waitForLoadState('networkidle');
                    }
                    await page.screenshot({ path: path.join(out, `distilled-${width}.png`), fullPage: true });
                    const previewWords = (await page.locator('main').innerText()).trim().split(/\s+/).length;
                    await page.goto(`${baseUrl}/tools/messaging/original`);
                    const originalWords = (await page.locator('main').innerText()).trim().split(/\s+/).length;
                    console.log(`${width}px: homepage main text ${originalWords} → ${previewWords} words, ${Math.round((1 - previewWords / originalWords) * 100)}% shorter`);
                } else {
                    const apply = page.getByRole('link', { name: 'Start your application', exact: true });
                    assert.equal(await apply.count(), 2);
                    assert.equal(await apply.first().getAttribute('href'), route.endsWith('makers') ? '/makers/apply' : '/organisations/apply');
                }
            }

            await page.goto(`${baseUrl}/tools/messaging/compare`);
            await page.waitForLoadState('networkidle');
            await page.waitForFunction(() => {
                const frame = document.querySelector('iframe');
                return frame && getComputedStyle(frame).transform !== 'none';
            });
            assert.ok(await page.getByRole('combobox', { name: 'Preview viewport' }).evaluate(element => element.getBoundingClientRect().height <= 48), 'Viewport control stays at input height');
            for (const viewport of [1440, 390]) {
                await page.getByRole('combobox', { name: 'Preview viewport' }).selectOption(String(viewport));
                await page.waitForFunction(expected => [...document.querySelectorAll('iframe')].every(frame => frame.contentWindow.innerWidth === expected), viewport);
                const sizes = await page.locator('iframe').evaluateAll(frames => frames.map(frame => ({ width: frame.getBoundingClientRect().width, stage: frame.parentElement.clientWidth })));
                assert.ok(sizes.every(size => size.width <= size.stage + 1), 'Both preview frames fit their pane');
            }
            await page.getByRole('combobox', { name: 'Preview viewport' }).selectOption(width === 1440 ? '1440' : '390');
            await page.waitForFunction(expected => [...document.querySelectorAll('iframe')].every(frame => frame.contentWindow.innerWidth === expected), width);
            assert.equal(await page.evaluate(() => document.documentElement.scrollWidth > innerWidth), false);
            await page.screenshot({ path: path.join(out, `compare-${width}.png`) });
            assert.deepEqual(errors, [], `${width}px: browser errors`);
            console.log(`${width}px: preview routes, audience links, viewport controls, images and overflow passed`);
            await context.close();
        }
    } finally {
        await browser.close();
    }
})().catch(error => { console.error(error); process.exit(1); });
