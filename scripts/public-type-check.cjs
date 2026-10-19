const { chromium } = require('playwright');
const assert = require('node:assert/strict');
const fs = require('node:fs');
const base = process.env.BASE_URL || 'https://makeamark-southern-ocean.test';
const out = '/tmp/makeamark-public-type';
fs.mkdirSync(out, { recursive: true });

// Check glyph positions: a heading can have no page overflow yet split a word.
async function headingWords(page) {
    return page.locator('.application__title, .audience-hero__title').evaluateAll(headings => headings.map(el => {
        const walker = document.createTreeWalker(el, NodeFilter.SHOW_TEXT);
        const words = [];
        while (walker.nextNode()) {
            const node = walker.currentNode;
            for (const match of node.textContent.matchAll(/\S+/g)) {
                const lines = new Set();
                for (let i = match.index; i < match.index + match[0].length; i++) {
                    const range = document.createRange();
                    range.setStart(node, i); range.setEnd(node, i + 1);
                    lines.add(Math.round(range.getBoundingClientRect().top));
                }
                words.push({ word: match[0], lines: lines.size });
            }
        }
        return { text: el.textContent.trim(), words, width: el.getBoundingClientRect().width, overflow: el.scrollWidth - el.clientWidth };
    }));
}

(async () => {
    const browser = await chromium.launch();
    const observations = [];
    try {
        for (const width of [1440, 1024, 900, 860, 390, 320]) {
            const context = await browser.newContext({ ignoreHTTPSErrors: true, viewport: { width, height: 1000 } });
            const page = await context.newPage();
            const errors = [];
            page.on('pageerror', e => errors.push(e.message));
            const routes = process.env.REPRO ? ['/makers'] : ['/', '/makers', '/organisations', '/privacy'];
            for (const route of routes) {
                await page.goto(base + route); await page.waitForLoadState('networkidle');
                await page.evaluate(() => document.fonts.ready);
                const headings = await headingWords(page);
                for (const heading of headings) assert.ok(heading.overflow <= 1, `${route} ${width}px: heading overflows own column`);
                for (const heading of headings) for (const word of heading.words) {
                    assert.equal(word.lines, 1, `${route} ${width}px: ${word.word} splits inside a word`);
                }
                if (process.env.REPRO) continue;
                const proseSelector = route === '/' ? '.audience-panel p' : route === '/privacy' ? '.privacy__body section p' : '.commitment__lead p';
                const type = await page.locator(proseSelector).first().evaluate(el => {
                    const c = getComputedStyle(el); return { weight: c.fontWeight, size: c.fontSize, line: c.lineHeight };
                });
                assert.deepEqual(type, { weight: '300', size: '17px', line: '27.2px' });
                assert.equal(await page.locator('link[rel=preload][href="/fonts/plus-jakarta-sans-light-300.woff2"]').count(), 1);
                assert.equal(await page.evaluate(() => document.documentElement.scrollWidth - innerWidth), 0, `${route} ${width}px overflow`);
                if (route !== '/') {
                    const back = page.getByRole('link', { name: 'Back to the edition', exact: true });
                    assert.equal((await back.textContent()).trim(), 'Edition');
                    assert.equal(await back.locator('svg').count(), 1);
                    assert.ok((await back.boundingBox()).height >= 44);
                    await back.focus(); assert.equal(await back.evaluate(el => getComputedStyle(el).outlineWidth), '2px');
                }
                if (route === '/makers' || route === '/organisations') {
                    const input = page.locator('input[name=email]');
                    assert.equal(await input.evaluate(el => getComputedStyle(el).fontWeight), '400');
                    assert.equal(await input.evaluate(el => getComputedStyle(el).fontSize), '17px');
                    assert.equal(await page.locator('.application__privacy-note').evaluate(el => getComputedStyle(el).fontSize), '14px');
                }
                if ((width === 1440 || width === 390) && process.env.SCREENSHOTS !== '0') {
                    const target = route === '/' ? '.audience-pair' : route === '/privacy' ? '.privacy__body section' : '.application';
                    await page.locator(target).first().evaluate(el => el.scrollIntoView({ block: 'start', behavior: 'instant' }));
                    await page.evaluate(() => scrollBy(0, -110));
                    await page.screenshot({ path: `${out}/${route.slice(1) || 'home'}-${width}.png` });
                    if (route === '/organisations') {
                        await page.evaluate(() => scrollTo({ top: 0, behavior: 'instant' }));
                        await page.screenshot({ path: `${out}/organisations-hero-${width}.png` });
                    }
                }
                await page.evaluate(() => document.documentElement.style.fontSize = '200%');
                assert.equal(await page.locator(proseSelector).first().evaluate(el => getComputedStyle(el).fontSize), '34px');
                for (const heading of await headingWords(page)) for (const word of heading.words) assert.equal(word.lines, 1, `${route} ${width}px 200%: ${word.word} splits`);
                assert.equal(await page.evaluate(() => document.documentElement.scrollWidth - innerWidth), 0, `${route} ${width}px 200% overflow`);
                observations.push({ route, width, type, headings, textScaling: '200% passed' });
            }
            assert.deepEqual(errors, []); await context.close();
        }
        if (!process.env.REPRO) {
            const context = await browser.newContext({ ignoreHTTPSErrors: true, viewport: { width: 320, height: 1000 } });
            const page = await context.newPage();
            await page.route('**/plus-jakarta-sans-light-300.woff2', r => r.abort());
            for (const route of ['/', '/makers', '/organisations', '/privacy']) {
                await page.goto(base + route); await page.evaluate(() => document.fonts.ready);
                assert.equal(await page.locator('h1').isVisible(), true);
                assert.equal(await page.evaluate(() => document.documentElement.scrollWidth - innerWidth), 0);
                for (const heading of await headingWords(page)) for (const word of heading.words) assert.equal(word.lines, 1, `${route}: fallback split`);
            }
            await page.goto(base + '/tools/riso');
            assert.equal(await page.locator('body.public-site').count(), 0);
            assert.equal(await page.locator('link[rel=preload][href="/fonts/plus-jakarta-sans-light-300.woff2"]').count(), 0);
            await context.close();
        }
        fs.writeFileSync(`${out}/observations.json`, JSON.stringify(observations, null, 2));
        console.log('Passed: public text roles, intact heading words, six widths, 200% text reflow, fallback and tool isolation.');
    } finally { await browser.close(); }
})().catch(e => { console.error(e); process.exit(1); });
