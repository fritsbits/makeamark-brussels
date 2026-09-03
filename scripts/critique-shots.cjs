// Capture a consistent set of frames for the design critic.
// Frames are taken by resizing/scrolling the viewport rather than with
// Playwright's fullPage flag, which renders horizontal scrollers unclipped.
// Usage: NODE_PATH="$(npm root -g)" BASE_URL=... OUT_DIR=... node scripts/critique-shots.cjs
const { chromium } = require('playwright');
const path = require('node:path');
const fs = require('node:fs');

const out = process.env.OUT_DIR || path.resolve(__dirname, '../.critique');
const baseUrl = process.env.BASE_URL || 'http://localhost:4322/';
const SLICE = 1000;
const HIDE_TOOLBAR = 'astro-dev-toolbar{display:none!important}';

fs.mkdirSync(out, { recursive: true });

async function settle(page) {
  await page.waitForLoadState('networkidle');
  await page.evaluate(async () => {
    for (const img of document.images) img.loading = 'eager';
    await Promise.all([...document.images].map((img) => img.decode().catch(() => {})));
  });
  await page.waitForLoadState('networkidle');
}

(async () => {
  const browser = await chromium.launch();

  // ---- desktop -------------------------------------------------------
  const page = await browser.newPage({ viewport: { width: 1440, height: SLICE } });
  await page.goto(baseUrl);
  await page.addStyleTag({ content: HIDE_TOOLBAR });
  await settle(page);

  const height = await page.evaluate(() => document.documentElement.scrollHeight);
  // documentElement.scrollWidth is inflated by the nested horizontal scroller
  // in Chromium, so measure the body box instead
  const overflow = await page.evaluate(
    () => Math.round(document.body.getBoundingClientRect().width) - document.documentElement.clientWidth
  );

  const slices = Math.ceil(height / SLICE);
  for (let i = 0; i < slices; i++) {
    await page.evaluate((y) => window.scrollTo(0, y), i * SLICE);
    await page.waitForTimeout(120);
    await page.screenshot({ path: path.join(out, `desktop-${String(i + 1).padStart(2, '0')}.png`) });
  }

  // whole page in one frame: grow the viewport instead of using fullPage
  await page.setViewportSize({ width: 1440, height: Math.min(height, 15000) });
  await page.addStyleTag({ content: HIDE_TOOLBAR });
  await settle(page);
  await page.evaluate(() => window.scrollTo(0, 0));
  await page.screenshot({ path: path.join(out, 'desktop-full.png') });
  await page.close();

  // ---- mobile --------------------------------------------------------
  const m = await browser.newPage({ viewport: { width: 390, height: 844 } });
  await m.goto(baseUrl);
  await m.addStyleTag({ content: HIDE_TOOLBAR });
  await settle(m);
  const mh = await m.evaluate(() => document.documentElement.scrollHeight);
  await m.setViewportSize({ width: 390, height: Math.min(mh, 15000) });
  await m.addStyleTag({ content: HIDE_TOOLBAR });
  await settle(m);
  await m.screenshot({ path: path.join(out, 'mobile-full.png') });
  await m.close();

  await browser.close();
  console.log(`page height ${height}px, ${slices} slices, horizontal overflow ${overflow}px -> ${out}`);
})();
