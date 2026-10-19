const { chromium } = require('playwright');
const assert = require('node:assert/strict');
const fs = require('node:fs');
const out = '/tmp/makeamark-riso';
fs.mkdirSync(out, { recursive: true });

(async () => {
    const browser = await chromium.launch();
    try {
        for (const [name, width] of [['desktop', 1440], ['mobile', 390]]) {
            const context = await browser.newContext({ ignoreHTTPSErrors: true, viewport: { width, height: 1000 } });
            const page = await context.newPage();
            const errors = [];
            page.on('pageerror', e => errors.push(e.message));
            page.on('response', r => { if (r.status() >= 400) errors.push(`${r.status()} ${r.url()}`); });
            const response = await page.goto('https://makeamark-southern-ocean.test/tools/riso');
            assert.equal(response.status(), 200);
            assert.match(response.headers()['x-robots-tag'], /noindex/);
            await page.waitForFunction(() => document.querySelector('canvas')?.width > 1000);
            await page.evaluate(() => document.fonts.ready);
            await page.screenshot({ path: `${out}/${name}.png`, fullPage: true });
            assert.equal(await page.locator('h1').count(), 1);
            assert.ok((await page.locator('#export-size').boundingBox()).height <= 50, 'export selector should remain compact');
            assert.equal(await page.evaluate(() => document.documentElement.scrollWidth - innerWidth), 0);
            const fingerprint = () => page.locator('canvas').evaluate(c => c.toDataURL());
            const initial = await fingerprint();
            await page.getByRole('button', { name: 'Original', exact: true }).click();
            assert.notEqual(await fingerprint(), initial, 'original comparison must change pixels');
            await page.getByRole('button', { name: 'Risoprint', exact: true }).click();
            assert.equal(await fingerprint(), initial, 'grain must remain deterministic');
            await page.locator('#grain').fill('20');
            await page.waitForTimeout(200);
            assert.notEqual(await fingerprint(), initial, 'grain control must change pixels');
            await page.getByRole('button', { name: 'Reset', exact: true }).click();
            await page.waitForTimeout(200);
            assert.equal(await fingerprint(), initial);
            const settingsDownload = page.waitForEvent('download');
            await page.getByRole('button', { name: 'Save treatment', exact: true }).click();
            const saved = await settingsDownload;
            await saved.saveAs(`${out}/${name}-treatment.json`);
            const settings = JSON.parse(fs.readFileSync(`${out}/${name}-treatment.json`, 'utf8'));
            assert.equal(settings.version, 1);
            settings.settings.grain = 12;
            await page.locator('input[type=file]').nth(1).setInputFiles({ name: 'treatment.json', mimeType: 'application/json', buffer: Buffer.from(JSON.stringify(settings)) });
            await page.waitForTimeout(250);
            assert.equal(await page.locator('#grain').inputValue(), '12');
            await page.getByRole('button', { name: 'Reset', exact: true }).click();
            await page.waitForTimeout(200);
            await page.locator('#export-size').selectOption('1600');
            const pngDownload = page.waitForEvent('download');
            await page.getByRole('button', { name: 'Download PNG', exact: true }).click();
            const png = await pngDownload;
            await png.saveAs(`${out}/${name}-print.png`);
            const bytes = fs.readFileSync(`${out}/${name}-print.png`);
            assert.equal(bytes.readUInt32BE(16), 1728);
            assert.equal(bytes.readUInt32BE(20), 1328);
            await page.locator('input[type=file]').first().setInputFiles('resources/images/riso-sample.png');
            await page.waitForFunction(() => document.querySelector('.riso__photo-meta').textContent.includes('riso-sample.png'));
            await page.locator('input[type=file]').nth(1).setInputFiles({ name: 'invalid.json', mimeType: 'application/json', buffer: Buffer.from('invalid') });
            await page.getByRole('alert').waitFor({ state: 'visible' });
            assert.match(await page.getByRole('alert').textContent(), /treatment JSON/);
            assert.deepEqual(errors, [], `${name}: browser errors`);
            console.log(`${name}: preview, comparison, grain, reset, settings roundtrip, PNG dimensions, upload, errors and overflow passed`);
            await context.close();
        }
    } finally { await browser.close(); }
})().catch(error => { console.error(error); process.exit(1); });
