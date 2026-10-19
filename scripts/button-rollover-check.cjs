const { chromium } = require('playwright');
const assert = require('node:assert/strict');
const fs = require('node:fs');
const path = require('node:path');

const output = process.env.OUT_DIR || '/tmp/makeamark-button-ink-sweep';
const baseUrl = process.env.BASE_URL || 'https://makeamark-southern-ocean.test';
const luminance = color => {
    const [r, g, b] = color.match(/[\d.]+/g).slice(0, 3).map(Number).map(value => value / 255)
        .map(value => value <= .04045 ? value / 12.92 : ((value + .055) / 1.055) ** 2.4);
    return .2126 * r + .7152 * g + .0722 * b;
};
const contrast = ({ color, background }) => {
    const values = [luminance(color), luminance(background)].sort((a, b) => b - a);
    return (values[0] + .05) / (values[1] + .05);
};

(async () => {
    const browser = await chromium.launch();
    try {
        const page = await browser.newPage({ ignoreHTTPSErrors: true, viewport: { width: 1440, height: 1000 } });
        await page.goto(baseUrl, { waitUntil: 'networkidle' });
        // The owner removed the edition action. Exercise the shared link markup
        // on the red surface in this browser-only fixture without adding a CTA.
        if (!await page.locator('#edition a.pill').count()) {
            await page.evaluate(() => {
                const link = document.querySelector('.audience-panel a.pill').cloneNode(true);
                document.querySelector('#edition').append(link);
            });
        }
        for (const selector of ['#edition a.pill', '.audience-panel a.pill']) {
            const link = page.locator(selector).first();
            assert.equal(await link.locator('.pill__sweep[aria-hidden="true"]').count(), 1,
                `${selector}: R02 supplies one contrasting sweep layer hidden from assistive technology`);
            for (const state of ['hover', 'focus']) {
                if (state === 'hover') await link.hover();
                else { await page.mouse.move(0, 0); await link.focus(); }
                await page.waitForTimeout(300);
                const styles = await link.evaluate(element => {
                    const layer = element.querySelector('.pill__sweep');
                    const style = getComputedStyle(layer);
                    return { color: style.color, background: style.backgroundColor, clip: style.clipPath };
                });
                const ratio = contrast(styles);
                assert(ratio >= 4.5, `${selector} ${state}: ${ratio.toFixed(2)}:1 label contrast; foreground ${styles.color}, background ${styles.background}`);
                assert.equal(styles.clip, 'inset(0px)', 'hover/focus completes the ink sweep');
                console.log(`${selector} ${state}: ${ratio.toFixed(2)}:1`);
            }
        }
        await page.close();

        for (const width of [1440, 860, 390]) {
            const context = await browser.newContext({ ignoreHTTPSErrors: true, viewport: { width, height: 1000 } });
            const screen = await context.newPage();
            const errors = [];
            screen.on('pageerror', error => errors.push(error.message));
            for (const route of ['/', '/makers', '/organisations']) {
                await screen.goto(baseUrl + route, { waitUntil: 'networkidle' });
                assert.equal(await screen.getByRole('link', { name: 'Explore ways to take part', exact: true }).count(), 0);
                for (const link of await screen.locator('a.pill').all()) {
                    const layers = await link.evaluate(element => {
                        const content = element.querySelector(':scope > .pill__content');
                        const overlay = element.querySelector('.pill__sweep');
                        const twin = overlay.querySelector('.pill__content');
                        const getBackground = start => {
                            for (let node = start; node; node = node.parentElement) {
                                const value = getComputedStyle(node).backgroundColor;
                                if (value !== 'rgba(0, 0, 0, 0)') return value;
                            }
                        };
                        const a = content.getBoundingClientRect();
                        const b = twin.getBoundingClientRect();
                        return {
                            label: content.textContent.trim(),
                            base: { color: getComputedStyle(content).color, background: getBackground(element) },
                            overlay: { color: getComputedStyle(twin).color, background: getBackground(overlay) },
                            alignment: Math.max(Math.abs(a.x - b.x), Math.abs(a.y - b.y), Math.abs(a.width - b.width), Math.abs(a.height - b.height)),
                        };
                    });
                    assert(contrast(layers.base) >= 4.5, `${width} ${route}: base label contrast`);
                    assert(contrast(layers.overlay) >= 4.5, `${width} ${route}: sweep label contrast`);
                    assert(layers.alignment < 1, `${width} ${route}: label layers align within 1px (${layers.alignment})`);
                    assert.equal(await screen.getByRole('link', { name: layers.label, exact: true }).count(), 1, 'one accessible label');
                }
                const link = screen.locator('a.pill').first();
                const bounds = await link.boundingBox();
                await link.hover();
                await screen.waitForTimeout(40);
                const intermediate = await link.locator('.pill__sweep').evaluate(element => {
                    const transition = element.getAnimations()[0];
                    if (transition) { transition.pause(); transition.currentTime = 90; }
                    return { clip: getComputedStyle(element).clipPath, animated: Boolean(transition) };
                });
                assert(intermediate.animated && intermediate.clip !== 'inset(0px)', 'ink sweeps through an intermediate state');
                await link.locator('.pill__sweep').evaluate(element => element.getAnimations().forEach(animation => animation.play()));
                await screen.waitForTimeout(300);
                assert.equal(await link.locator('.pill__sweep').evaluate(element => getComputedStyle(element).clipPath), 'inset(0px)');
                assert(Math.abs((await link.boundingBox()).width - bounds.width) < 1, 'rollover does not shift button layout');
                if (process.env.SCREENSHOTS === '1' && ((width === 1440 && route === '/') || (width === 390 && route === '/makers'))) {
                    fs.mkdirSync(output, { recursive: true });
                    await screen.locator(route === '/' ? '.audience-pair' : '.audience-hero').screenshot({ path: path.join(output, `${width}.png`) });
                }
                await screen.mouse.move(0, 0);
                await screen.waitForTimeout(300);
                assert.equal(await link.locator('.pill__sweep').evaluate(element => getComputedStyle(element).clipPath), 'inset(0px 100% 0px 0px)');
                await screen.keyboard.press('Tab');
                await link.focus();
                await screen.waitForTimeout(300);
                assert.equal(await link.locator('.pill__sweep').evaluate(element => getComputedStyle(element).clipPath), 'inset(0px)');
                assert.equal(await link.evaluate(element => getComputedStyle(element).outlineWidth), '3px');
                await screen.emulateMedia({ reducedMotion: 'reduce' });
                assert.equal(await link.locator('.pill__sweep').evaluate(element => getComputedStyle(element).transitionDuration), '0s');
                await screen.emulateMedia({ reducedMotion: 'no-preference' });
                assert.equal(await screen.evaluate(() => document.documentElement.scrollWidth - innerWidth), 0, 'no horizontal overflow');
            }
            assert.deepEqual(errors, []);
            console.log(`${width}: home/makers/organisations, both label layers, alignment, mid-sweep/reversal, keyboard and reduced motion pass`);
            await context.close();
        }
        const staticContext = await browser.newContext({ ignoreHTTPSErrors: true, javaScriptEnabled: false, viewport: { width: 390, height: 1000 } });
        const staticPage = await staticContext.newPage();
        await staticPage.goto(baseUrl);
        const staticLink = staticPage.getByRole('link', { name: 'Maker details', exact: true });
        await staticLink.hover();
        await staticPage.waitForTimeout(300);
        assert.equal(await staticLink.locator('.pill__sweep').evaluate(element => getComputedStyle(element).clipPath), 'inset(0px)');
        assert.equal(await staticLink.getAttribute('href'), '/makers');
        await staticContext.close();
        console.log('No JavaScript: CSS rollover and original destination remain available');
    } finally {
        await browser.close();
    }
})().catch(error => { console.error(error); process.exit(1); });
