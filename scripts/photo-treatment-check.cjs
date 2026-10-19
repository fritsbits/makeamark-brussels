const { chromium } = require('playwright');
const assert = require('node:assert/strict');
const fs = require('node:fs');
const out = '/tmp/makeamark-site-monochrome';
fs.mkdirSync(out, { recursive: true });

async function waitProgress(page, button, value) {
    const image = await button.locator('.photo-reveal__original').elementHandle();
    try {
        await page.waitForFunction(({ image, value }) => image.style.getPropertyValue('--reveal-progress') === value, { image, value });
    } finally { await image.dispose(); }
}

async function prepare(page, button) {
    await button.scrollIntoViewIfNeeded();
    await button.locator('img[data-photo-treatment="monochrome"]').evaluate(img => img.decode());
    const handle = await button.elementHandle();
    try {
        await page.waitForFunction(element => !element.disabled, handle);
    } finally { await handle.dispose(); }
}

async function hover(button, hero = false) {
    // Enter the exposed upper part of each overlapping hero frame.
    if (hero) {
        const box = await button.boundingBox();
        await button.hover({ position: { x: box.width * 0.2, y: box.height * 0.15 } });
    } else await button.hover();
}

(async () => {
    const browser = await chromium.launch();
    try {
        for (const [name, width, reducedMotion] of [['desktop', 1440, 'no-preference'], ['mobile', 390, 'no-preference'], ['reduced-motion', 1440, 'reduce']]) {
            const mobile = name === 'mobile';
            const context = await browser.newContext({ ignoreHTTPSErrors: true, viewport: { width, height: 1000 }, reducedMotion, hasTouch: mobile, isMobile: mobile });
            const page = await context.newPage();
            const errors = [];
            page.on('pageerror', e => errors.push(e.message));
            page.on('response', r => { if (r.status() >= 400) errors.push(r.status() + ' ' + r.url()); });
            await page.goto('https://makeamark-southern-ocean.test/');
            await page.evaluate(() => document.fonts.ready);
            assert.equal(await page.locator('img[data-photo-treatment="riso"]').count(), 0);
            assert.equal(await page.locator('img[data-photo-treatment="monochrome"]').count(), 13);
            assert.equal(await page.locator('.photo-reveal').count(), 13);
            assert.equal(await page.locator('.photo-reveal__original').count(), 13);
            assert.equal(await page.locator('#edition .duo').count(), 0);
            assert.equal(await page.evaluate(() => document.documentElement.scrollWidth - innerWidth), 0);

            for (const [section, selector, count] of [['hero', '.hero__photo .photo-reveal', 2], ['evidence', '.distilled-evidence .photo-reveal', 8]]) {
                const buttons = page.locator(selector);
                assert.equal(await buttons.count(), count);
                let exercised = 0;
                for (let index = 0; index < count; index++) {
                    const button = buttons.nth(index);
                    // Exercise every reachable photo, including horizontally scrolled slides.
                    if (!(await button.isVisible())) continue;
                    exercised++;
                    await prepare(page, button);
                    const photo = button.locator('img[data-photo-treatment="monochrome"]');
                    const original = button.locator('.photo-reveal__original');
                    assert.equal(await photo.evaluate(img => getComputedStyle(img).filter), 'grayscale(1) contrast(1.04)');
                    assert.equal(await original.evaluate(img => getComputedStyle(img).filter), 'none');
                    const label = await button.getAttribute('aria-label');
                    assert.match(label, /^Show original photo: .+/);
                    if (section === 'evidence') {
                        assert.equal(label, `Show original photo: photo from the day, ${index + 1} of 9`);
                        if (index === 0) assert.ok((await photo.evaluate(img => img.currentSrc)).includes('/images/edition-2026-group.'));
                    }

                    if (mobile) await button.tap();
                    else await hover(button, section === 'hero');
                    await waitProgress(page, button, '1');
                    assert.equal(await original.evaluate(img => getComputedStyle(img).opacity), '1');
                    if (process.env.SCREENSHOTS === '1' && name === 'desktop' && section === 'evidence') await page.locator('.distilled-evidence').screenshot({ path: out + '/desktop-color.png' });
                    if (name === 'reduced-motion') assert.equal(await button.locator('.photo-reveal__frame').evaluate(element => getComputedStyle(element).transform), 'none');
                    if (mobile) {
                        assert.equal(await button.getAttribute('aria-pressed'), 'true');
                        assert.equal(await button.getAttribute('aria-label'), label.replace('Show original photo:', 'Return to desaturated photo:'));
                        await button.tap();
                    } else await page.mouse.move(0, 0);
                    await waitProgress(page, button, '0');
                    assert.equal(await button.getAttribute('aria-pressed'), 'false');

                    if (!mobile) {
                        await button.focus();
                        await page.keyboard.press('Enter');
                        await waitProgress(page, button, '1');
                        assert.equal(await button.getAttribute('aria-pressed'), 'true');
                        assert.equal(await button.getAttribute('aria-label'), label.replace('Show original photo:', 'Return to desaturated photo:'));
                        await page.keyboard.press('Escape');
                        await waitProgress(page, button, '0');
                        assert.equal(await button.getAttribute('aria-pressed'), 'false');
                    }
                }
                assert.equal(exercised, count);
            }

            // A pinned image stays revealed while a different image is used.
            const first = page.locator('.hero__photo .photo-reveal').first();
            const second = page.locator('.distilled-evidence .photo-reveal').first();
            await prepare(page, first);
            if (mobile) await first.tap();
            else { await first.focus(); await page.keyboard.press('Enter'); }
            await waitProgress(page, first, '1');
            await prepare(page, second);
            if (mobile) await second.tap();
            else await second.hover();
            await waitProgress(page, second, '1');
            assert.equal(await first.getAttribute('aria-pressed'), 'true');
            await waitProgress(page, first, '1');
            if (mobile) {
                await second.tap();
                await waitProgress(page, second, '0');
                await prepare(page, first);
                await first.tap();
            } else {
                await page.mouse.move(0, 0);
                await waitProgress(page, second, '0');
                await page.keyboard.press('Escape');
            }
            await waitProgress(page, first, '0');
            assert.equal(await first.getAttribute('aria-pressed'), 'false');
            assert.equal(await second.getAttribute('aria-pressed'), 'false');

            if (process.env.SCREENSHOTS === '1' && (name === 'desktop' || mobile)) {
                await page.evaluate(() => window.scrollTo(0, 0));
                await page.screenshot({ path: out + '/' + name + '-homepage.png', fullPage: true });
            }
            assert.deepEqual(errors, []);
            console.log(name + ': all 10 monochrome bases; hero and gallery reveals restore; independent states; no errors or overflow');
            await context.close();
        }
        const context = await browser.newContext({ ignoreHTTPSErrors: true, javaScriptEnabled: false, viewport: { width: 1440, height: 1000 } });
        const page = await context.newPage();
        await page.goto('https://makeamark-southern-ocean.test/');
        const buttons = page.locator('.photo-reveal');
        assert.equal(await buttons.count(), 6);
        for (let index = 0; index < 6; index++) {
            const button = buttons.nth(index);
            const photo = button.locator('img[data-photo-treatment="monochrome"]');
            await photo.evaluate(img => {
                img.loading = 'eager';
                img.scrollIntoView({ behavior: 'instant', block: 'center', inline: 'center' });
            });
            await photo.evaluate(img => img.decode());
            assert.ok((await photo.evaluate(img => getComputedStyle(img).filter)).includes('grayscale(1)'));
            assert.equal(await button.isDisabled(), true);
            assert.equal(await button.locator('.photo-reveal__original').evaluate(img => getComputedStyle(img).opacity), '0');
        }
        console.log('JavaScript disabled: all 6 monochrome bases remain reachable');
        await context.close();
    } finally { await browser.close(); }
})().catch(error => { console.error(error); process.exit(1); });
