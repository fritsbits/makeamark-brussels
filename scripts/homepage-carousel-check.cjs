const { chromium } = require('playwright');
const assert = require('node:assert/strict');
const fs = require('node:fs');

const baseUrl = process.env.BASE_URL || 'https://makeamark-southern-ocean.test';

(async () => {
    const browser = await chromium.launch();
    try {
        for (const width of [1440, 1024, 860, 640, 390]) {
            const context = await browser.newContext({ ignoreHTTPSErrors: true, hasTouch: width === 390, viewport: { width, height: 1000 } });
            const page = await context.newPage();
            const errors = [];
            page.on('pageerror', error => errors.push(error.message));
            await page.goto(baseUrl, { waitUntil: 'networkidle' });
            const carousel = page.getByRole('region', { name: 'Photos from the day', exact: true });
            await carousel.waitFor({ timeout: 3000 });
            await carousel.scrollIntoViewIfNeeded();
            const track = carousel.locator('.day-carousel__track');
            const slides = carousel.locator('.day-carousel__slide');
            const previous = carousel.getByRole('button', { name: 'Previous photo', exact: true });
            const next = carousel.getByRole('button', { name: 'Next photo', exact: true });
            assert.equal(await slides.count(), 11);
            assert.equal(await previous.isDisabled(), true);
            await page.evaluate(async () => {
                for (const image of document.querySelectorAll('.day-carousel__slide img')) image.loading = 'eager';
                await Promise.all([...document.querySelectorAll('.day-carousel__slide img')].map(image => image.decode()));
            });
            const imageWidths = await carousel.locator('.day-carousel__slide img').evaluateAll(images => images.map(image => image.naturalWidth));
            assert.equal(imageWidths.length, 22, `${width}: two image treatments for each of eleven slides`);
            assert(imageWidths.every(imageWidth => imageWidth > 0), `${width}: every responsive carousel image loads`);
            await page.evaluate(() => {
                const stage = document.querySelector('.distilled-evidence__stage');
                scrollTo({ top: stage.getBoundingClientRect().top + scrollY - 100, behavior: 'instant' });
            });
            await page.waitForTimeout(100);
            const dimensions = await carousel.evaluate(el => {
                const section = el.closest('.distilled-evidence');
                const styles = getComputedStyle(section);
                const track = el.querySelector('.day-carousel__track').getBoundingClientRect();
                const image = el.querySelector('img');
                const photo = image.getBoundingClientRect();
                const frameRatios = [...el.querySelectorAll('.photo-reveal__frame')].map(frame => {
                    const rect = frame.getBoundingClientRect();
                    return rect.width / rect.height;
                });
                const next = el.querySelectorAll('.day-carousel__slide')[1].getBoundingClientRect();
                return {
                    container: section.clientWidth - parseFloat(styles.paddingLeft) - parseFloat(styles.paddingRight),
                    photoWidth: photo.width,
                    frameRatios,
                    neighborVisible: next.left < track.right - 1,
                };
            });
            assert(Math.abs(dimensions.photoWidth - dimensions.container) < 2, `${width}: final photo fills the container (${dimensions.photoWidth} / ${dimensions.container})`);
            assert(dimensions.frameRatios.every(ratio => Math.abs(ratio - 4 / 3) < .01), `${width}: all carousel frames stay 4:3`);
            assert.equal(dimensions.neighborVisible, false, `${width}: no cropped neighboring slide`);
            await next.click();
            await page.waitForFunction(() => document.querySelector('.day-carousel__track').scrollLeft > 50);
            await page.waitForTimeout(650);
            await next.focus();
            await page.keyboard.press('End');
            await page.waitForFunction(() => {
                const track = document.querySelector('.day-carousel__track');
                return Math.abs(track.scrollLeft - (track.scrollWidth - track.clientWidth)) < 2
                    && document.querySelector('[aria-label="Next photo"]').disabled;
            });
            assert.equal(await next.isDisabled(), true);
            await previous.focus();
            await page.keyboard.press('Home');
            await page.waitForTimeout(650);
            assert.equal(await previous.isDisabled(), true);
            // Native scrolling is also the touch/swipe path; it must update the controls.
            await track.evaluate(el => { el.scrollLeft = (el.scrollWidth - el.clientWidth) / 3; });
            await page.waitForTimeout(650);
            const photo = slides.nth(1).locator('.photo-reveal');
            await photo.focus();
            await page.keyboard.press('Enter');
            assert.equal(await photo.getAttribute('aria-pressed'), 'true');
            assert.equal(await photo.locator('img').first().evaluate(img => getComputedStyle(img).transform), 'none', 'colour reveal keeps the whole image in view');
            await page.keyboard.press('Escape');
            assert.equal(await photo.getAttribute('aria-pressed'), 'false');
            if (width === 390) {
                await previous.click();
                await page.waitForTimeout(650);
                const box = await track.boundingBox();
                const session = await context.newCDPSession(page);
                const start = box.x + box.width * .8;
                const y = box.y + box.height * .5;
                await session.send('Input.dispatchTouchEvent', { type: 'touchStart', touchPoints: [{ x: start, y }] });
                for (let step = 1; step <= 8; step++) {
                    await session.send('Input.dispatchTouchEvent', { type: 'touchMove', touchPoints: [{ x: start - box.width * .6 * step / 8, y }] });
                    await page.waitForTimeout(25);
                }
                await session.send('Input.dispatchTouchEvent', { type: 'touchEnd', touchPoints: [] });
                await page.waitForTimeout(650);
                assert((await track.evaluate(el => el.scrollLeft)) > 50, 'a touch swipe advances the gallery');
                await session.detach();
            }
            await page.emulateMedia({ reducedMotion: 'reduce' });
            assert.equal(await track.evaluate(el => getComputedStyle(el).scrollBehavior), 'auto');
            assert.equal(await page.evaluate(() => document.documentElement.scrollWidth - innerWidth), 0);
            assert.deepEqual(errors, []);
            if (process.env.SCREENSHOTS === '1' && [1440, 390].includes(width)) {
                await previous.click();
                await page.locator('#evidence-heading').scrollIntoViewIfNeeded();
                await page.mouse.move(0, 0);
                await page.evaluate(() => document.activeElement.blur());
                fs.mkdirSync('/tmp/makeamark-carousel', { recursive: true });
                await page.locator('.distilled-evidence').screenshot({ path: `/tmp/makeamark-carousel/${width}.png` });
            }
            console.log(`${width}: arrows, boundaries, keyboard, native scrolling, colour reveal, reduced motion and overflow pass`);
            await context.close();
        }
        const context = await browser.newContext({ ignoreHTTPSErrors: true, javaScriptEnabled: false, viewport: { width: 390, height: 1000 } });
        const page = await context.newPage();
        await page.goto(baseUrl);
        const track = page.locator('.day-carousel__track');
        assert.equal(await page.locator('.day-carousel__controls').isVisible(), false);
        assert.equal(await page.locator('.day-carousel__slide').count(), 11);
        await track.evaluate(el => { el.scrollLeft = el.scrollWidth; });
        assert((await track.evaluate(el => el.scrollLeft)) > 0, 'photos remain scrollable without JavaScript');
        console.log('No JavaScript: all photos reachable by native scrolling; inactive controls hidden');
        await context.close();
    } finally {
        await browser.close();
    }
})().catch(error => { console.error(error); process.exit(1); });
