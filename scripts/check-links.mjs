import { readFileSync } from 'node:fs';

const html = readFileSync(new URL('../dist/index.html', import.meta.url), 'utf8');
const hrefs = [...new Set([...html.matchAll(/href="(https?:\/\/[^"]+)"/g)].map((m) => m[1]))];
let failed = 0;
for (const href of hrefs) {
  try {
    const res = await fetch(href, { method: 'GET', redirect: 'follow', headers: { 'user-agent': 'Mozilla/5.0 link-check' } });
    const ok = res.status < 400;
    if (!ok) failed++;
    console.log(`${ok ? 'ok ' : 'FAIL'} ${res.status} ${href}`);
  } catch (err) {
    failed++;
    console.log(`FAIL ---- ${href} (${err.message})`);
  }
}
process.exit(failed ? 1 : 0);
