const { chromium } = require('playwright');
const path = require('node:path');
const out = process.env.OUT_DIR || path.resolve(__dirname, '../.screenshots');
const baseUrl = process.env.BASE_URL || 'http://localhost:4321/';
require('node:fs').mkdirSync(out, { recursive: true });
(async () => {
  const browser = await chromium.launch();
  for (const [name, width] of [['desktop', 1440], ['mobile', 390]]) {
    const page = await browser.newPage({ viewport: { width, height: 900 } });
    await page.goto(baseUrl);
    await page.waitForLoadState('networkidle');
    // pull in lazily loaded images so they are in the full-page capture
    await page.evaluate(async () => {
      for (const img of document.images) img.loading = 'eager';
      await Promise.all([...document.images].map((img) => img.decode().catch(() => {})));
    });
    await page.waitForLoadState('networkidle');
    const overflow = await page.evaluate(() => document.documentElement.scrollWidth - document.documentElement.clientWidth);
    await page.screenshot({ path: path.join(out, `${name}.png`), fullPage: true });
    console.log(`${name}: horizontal overflow ${overflow}px, saved ${name}.png`);
    await page.close();
  }
  await browser.close();
})();
