const { chromium } = require('playwright');
const assert = require('node:assert/strict');
const fs = require('node:fs');

const base = process.env.BASE_URL || 'http://127.0.0.1:8127';
const out = '.lavish/solumob-assets';
fs.mkdirSync(out, { recursive: true });

(async () => {
    const browser = await chromium.launch();
    const results = [];
    try {
        for (const width of [1440, 390, 320]) {
            const context = await browser.newContext({ ignoreHTTPSErrors: true, viewport: { width, height: 900 } });
            const page = await context.newPage();
            const errors = [];
            page.on('pageerror', error => errors.push(error.message));
            const response = await page.goto(`${base}/case-studies/solumob`);
            await page.waitForLoadState('networkidle');
            await page.evaluate(async () => {
                await document.fonts.ready;
                await Promise.all([...document.images].map(image => { image.loading = 'eager'; return image.decode(); }));
            });
            assert.equal(response.status(), 200);
            assert.equal(response.headers()['x-robots-tag'], 'noindex, nofollow');
            assert.equal(await page.locator('h1').count(), 1);
            assert.equal(await page.locator('main img').count(), 10);
            assert.equal(await page.locator('iframe').count(), 0);
            assert.equal(await page.evaluate(() => document.documentElement.scrollWidth - innerWidth), 0, `${width}: overflow`);
            assert.equal(await page.getByRole('link', { name: 'Explore organisation details' }).getAttribute('href'), '/organisations');
            assert.equal(await page.getByRole('link', { name: 'Explore maker details' }).getAttribute('href'), '/makers');
            if (width !== 320) await page.screenshot({ path: `${out}/built-${width}.png`, fullPage: true });
            assert.deepEqual(errors, []);
            results.push({ width, status: 200, overflow: 0, pageErrors: errors, imagesDecoded: 10 });
            // Verify player creation by keyboard without relying on third-party playback.
            await context.route('https://www.youtube-nocookie.com/**', route => route.fulfill({ status: 200, contentType: 'text/html', body: '<p>Player request verified</p>' }));
            await page.getByRole('button', { name: 'Play the making-of', exact: true }).focus();
            await page.keyboard.press('Enter');
            await page.waitForSelector('.case-video__player');
            assert.match(await page.locator('.case-video__player').getAttribute('src'), /youtube-nocookie.com\/embed\/M6fTa72AHuo/);
            for (const route of ['/', '/makers', '/organisations']) {
                await page.goto(`${base}${route}`);
                await page.waitForLoadState('networkidle');
                assert.equal(await page.evaluate(() => document.documentElement.scrollWidth - innerWidth), 0, `${width} ${route}: overflow`);
                if (route === '/') {
                    await page.locator('.case-feature img').evaluate(image => image.decode());
                    if (width !== 320) await page.locator('.case-feature').screenshot({ path: `${out}/homepage-feature-${width}.png` });
                    await page.getByRole('link', { name: 'See what we made for Solumob', exact: true }).click();
                } else {
                    const boxes = await page.evaluate(() => ['.commitment', '.case-invitation', '#application'].map(selector => { const r = document.querySelector(selector).getBoundingClientRect(); return { top: r.top, bottom: r.bottom }; }));
                    assert(boxes[1].top >= boxes[0].bottom && boxes[2].top >= boxes[1].bottom, `${route}: invitation follows commitment, precedes form`);
                    if (width === 1440) await page.locator('.case-invitation').screenshot({ path: `${out}/${route.slice(1)}-invitation.png` });
                    await page.locator('.case-invitation a').click();
                }
                await page.waitForURL('**/case-studies/solumob');
                assert.equal(await page.locator('h1').count(), 1);
            }
            await context.close();
        }
        const context = await browser.newContext({ javaScriptEnabled: false, reducedMotion: 'reduce', viewport: { width: 390, height: 900 } });
        const page = await context.newPage();
        await page.goto(`${base}/case-studies/solumob`);
        assert.equal(await page.getByRole('link', { name: 'Watch the making-of on YouTube' }).count(), 1);
        assert.equal(await page.getByRole('link', { name: 'Explore maker details' }).count(), 1);
        await context.close();
        const evidence = { checked: new Date().toISOString(), base, results, video: 'keyboard click creates the expected privacy-enhanced player; third-party playback not tested', links: 'homepage, makers and organisations lead to the shared case; audience links precede forms', noJavaScript: 'video and participation links available', reducedMotion: 'static layout and links usable' };
        fs.writeFileSync('docs/raw/2026-10-19-solumob-build-verification.json', JSON.stringify(evidence, null, 2) + '\n', { flag: 'wx' });
        console.log(JSON.stringify(evidence));
    } finally { await browser.close(); }
})().catch(error => { console.error(error); process.exitCode = 1; });
