const { chromium } = require('playwright');
const assert = require('node:assert/strict');
const fs = require('node:fs');
const path = require('node:path');

const baseUrl = process.env.BASE_URL || 'https://makeamark-southern-ocean.test';
const screenshots = process.env.SCREENSHOTS === '1';
const out = process.env.OUT_DIR || '/tmp/makeamark-homepage-boldness';
const scale = async photo => photo.evaluate(element => {
    const box = element.getBoundingClientRect();
    return box.width / element.offsetWidth;
});

(async () => {
    const browser = await chromium.launch();
    try {
        for (const width of [1440, 1024, 860, 640, 390]) {
            const context = await browser.newContext({ ignoreHTTPSErrors: true, hasTouch: width === 390, viewport: { width, height: 1000 } });
            const page = await context.newPage();
            const errors = [];
            page.on('pageerror', error => errors.push(error.message));
            await page.goto(baseUrl, { waitUntil: 'networkidle' });
            await page.evaluate(async () => {
                await document.fonts.ready;
                for (const image of document.images) image.loading = 'eager';
                await Promise.all([...document.images].map(image => image.decode()));
            });
            const photo = page.locator('.distilled-evidence__photo .photo-reveal').first();
            const top = await photo.evaluate(element => element.getBoundingClientRect().top + scrollY);
            await page.evaluate(y => scrollTo({ top: y, behavior: 'instant' }), top - 940);
            await page.waitForTimeout(100);
            const small = await scale(photo);
            await page.evaluate(y => scrollTo({ top: y, behavior: 'instant' }), top - 200);
            await page.waitForTimeout(100);
            const large = await scale(photo);
            assert(large > small + (width > 860 ? .12 : .04), `${width}: group photo grows with scroll (${small} -> ${large})`);
            assert(Math.abs(large - 1) < .01, `${width}: group photo reaches final scale`);
            const containerWidth = await page.locator('.distilled-evidence').evaluate(el => {
                const style = getComputedStyle(el);
                return el.clientWidth - parseFloat(style.paddingLeft) - parseFloat(style.paddingRight);
            });
            assert(Math.abs((await photo.boundingBox()).width - containerWidth) < 2, `${width}: final photo fills the container`);
            const height = await page.evaluate(() => document.documentElement.scrollHeight);
            await page.evaluate(y => scrollTo({ top: y, behavior: 'instant' }), top - 940);
            await page.waitForTimeout(100);
            assert((await scale(photo)) < large - .04, `${width}: scrolling back reverses growth`);
            assert.equal(await page.evaluate(() => document.documentElement.scrollHeight), height, 'growth does not shift layout');

            await photo.focus();
            await page.keyboard.press('Enter');
            assert.equal(await photo.getAttribute('aria-pressed'), 'true');
            await page.keyboard.press('Escape');
            assert.equal(await photo.getAttribute('aria-pressed'), 'false');
            if (width === 390) {
                await photo.tap();
                assert.equal(await photo.getAttribute('aria-pressed'), 'true', 'touch pins the colour reveal');
                await photo.tap();
                assert.equal(await photo.getAttribute('aria-pressed'), 'false', 'second touch restores monochrome');
            }
            await page.emulateMedia({ reducedMotion: 'reduce' });
            await page.waitForTimeout(100);
            assert(Math.abs(await scale(photo) - 1) < .01, 'runtime reduced motion restores full size');
            assert.equal(await page.locator('.hero__photo').count(), 2);
            for (const image of await page.locator('.hero__photo').all()) {
                assert(await image.isVisible(), `${width}: both hero photos are visible`);
                assert.equal(await image.evaluate(e => getComputedStyle(e).animationName), 'none');
            }
            assert.equal(await page.locator('.audience-panel .pill').count(), 2);
            assert.equal(await page.evaluate(() => document.documentElement.scrollWidth - innerWidth), 0, `${width}: horizontal overflow`);
            assert.deepEqual(errors, []);
            if (screenshots && [1440, 390].includes(width)) {
                fs.mkdirSync(out, { recursive: true });
                await page.evaluate(() => { document.activeElement.blur(); scrollTo({ top: 0, behavior: 'instant' }); });
                await page.screenshot({ path: path.join(out, `${width}.png`), fullPage: true });
            }
            console.log(`${width}: scroll growth/reversal, stable layout, full-size frame, keyboard reveal, both photos and reduced motion pass`);
            await context.close();
        }
        const context = await browser.newContext({ ignoreHTTPSErrors: true, javaScriptEnabled: false, viewport: { width: 390, height: 1000 } });
        const page = await context.newPage();
        await page.goto(baseUrl);
        assert.equal(await page.locator('.hero__photo:visible').count(), 2);
        const photo = page.locator('.distilled-evidence__photo .photo-reveal').first();
        assert.equal(await scale(photo), 1, 'no JavaScript leaves the full-size photograph visible');
        assert.equal(await page.evaluate(() => document.documentElement.scrollWidth - innerWidth), 0);
        await context.close();
        console.log('No JavaScript: both hero photos and full-size group photograph remain visible');
    } finally {
        await browser.close();
    }
})().catch(error => { console.error(error); process.exit(1); });
