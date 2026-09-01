# Make a Mark Brussels Website Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Build and deploy the one-page Make a Mark Brussels site in Astro, in the international Make a Mark look, direction D "stories wall".

**Architecture:** A single static route composed of ten section components. Content lives in collections (editions, organisations, faq) and two validated JSON files (site, stories). Pure logic (edition status) sits in `src/lib` with node tests. Fonts come from bunny.net through Astro's Fonts API. Netlify builds `dist` on push.

**Tech Stack:** Astro 7.2, TypeScript 5, Zod 4 (via `astro/zod`), `@astrojs/sitemap`, `@astrojs/check`, sharp, Node 22.22, Netlify. Node's built-in test runner. Playwright (globally installed) for screenshots.

**Spec:** `docs/superpowers/specs/2026-09-01-makeamark-brussels-website-design.md`

## Global Constraints

- Node `>=22.12.0`. Odd Node versions unsupported. `.nvmrc` says `22`.
- Astro 7 uses the Rust compiler: every non-void element must be closed, no auto-correction.
- Astro 7 default `compressHTML: 'jsx'` strips whitespace between adjacent inline elements. Where two inline elements must keep a space, write `{" "}` between them.
- Collections must use loaders; config file is `src/content.config.ts`; import `z` from `astro/zod` (Zod 4: use `z.url()`, not `z.string().url()`); render markdown with `render(entry)` from `astro:content`.
- Colours, exact: cream `#F5E2CA`, cream-light `#FFF6EA`, red `#C33F40`, red-2 `#D14B4C`, ink `#1E1A16`, photo tint `rgba(121,26,27,.3)`.
- Type: Big Shoulders Display weights 300 and 600, Plus Jakarta Sans weights 400 and 600, both from bunny.net via `fontProviders.bunny()`.
- Body text on red uses cream-light, never cream. Red-2 is for headings on cream only. Links and body on cream use ink.
- Every visible string comes from a content file or `site.json`. No copy inside components.
- Stories strip photos are full colour. Photos inside red blocks and polaroids get the `.duo` treatment.
- No client JavaScript except the mobile nav toggle.
- Commit after every task with the `prefix(scope): subject` format and the trailer lines below.
- Playwright scripts are `.cjs` and need `NODE_PATH="$(npm root -g)"`, because the project has `"type": "module"` and Playwright is installed globally.
- Working directory for every command: `/Users/frederikvincx/Documents/makeamark/site`.

Commit trailer to append to every commit message:

```
Co-Authored-By: Claude Fable 5.1 <noreply@anthropic.com>
Claude-Session: https://claude.ai/code/session_01XrYNvFcR2mGBmwudJ9Kt2t
```

## File structure

```
astro.config.mjs            site URL, sitemap, fonts (bunny), image layout
netlify.toml                build command and publish dir
.nvmrc                      22
package.json                scripts: dev, build, preview, check, test, test:dist
src/content.config.ts       collections: editions (md), organisations (json), faq (md)
src/content/editions/2026.md
src/content/faq/01-google-doc.md .. 05-your-city.md
src/content/data/organisations.json
src/content/data/site.json          name, urls, hero copy, nav, organisers, footer
src/content/data/stories.json       photo file, alt, credit
src/assets/stories/story-1.jpg .. story-9.jpg
src/assets/logo-brussels.png
src/lib/edition-status.ts (+ .test.ts)   status -> pill label and href
src/lib/site.ts                          zod-validated site.json and stories.json
src/lib/stories.ts                       file name -> imported image
src/styles/tokens.css                    custom properties
src/styles/global.css                    reset, container, block, headings, pill, duo
src/layouts/Base.astro                   head, fonts, skip link, meta
src/components/Nav.astro Footer.astro Pill.astro Polaroid.astro
src/components/Hero.astro StoriesStrip.astro EditionBlock.astro StatusPill.astro
src/components/MakersInfo.astro Organisations.astro Faq.astro About.astro
src/pages/index.astro                    composes the sections
public/robots.txt public/og.png public/favicon.svg
scripts/og.html scripts/og.cjs           renders public/og.png
scripts/screenshot.cjs                   1440 and 390 full-page screenshots
scripts/check-links.mjs                  HEAD-requests every external href in dist
tests/dist.test.ts                       asserts on dist/index.html
README.md                                how to edit content and deploy
```

---

### Task 1: Scaffold, config, Netlify files

**Files:**
- Create: `package.json`, `astro.config.mjs`, `tsconfig.json`, `netlify.toml`, `.nvmrc`, `src/pages/index.astro`, `public/robots.txt`
- Modify: `.gitignore`

**Interfaces:**
- Produces: npm scripts `dev`, `build`, `preview`, `check`, `test`, `test:dist`; font CSS variables `--font-display` and `--font-body`; `site` set to `https://letsmakeamark.brussels`.

- [ ] **Step 1: Scaffold into the existing folder**

Run from the site folder (it already contains `docs/`, `.lavish/`, `.git`):

```bash
npm create astro@latest -- . --template minimal --skip-install --skip-git --yes
```

If the scaffolder refuses a non-empty directory, scaffold into `scratch-astro` and move `package.json`, `tsconfig.json`, `astro.config.mjs`, `src/`, `public/` up one level, then delete `scratch-astro`.

- [ ] **Step 2: Install dependencies**

```bash
npm install astro@^7.2 @astrojs/sitemap@^3.7 sharp@^0.35
npm install -D @astrojs/check@^0.9 typescript@^5.9
```

- [ ] **Step 3: Write `astro.config.mjs`**

```js
import { defineConfig, fontProviders } from 'astro/config';
import sitemap from '@astrojs/sitemap';

export default defineConfig({
  site: 'https://letsmakeamark.brussels',
  integrations: [sitemap()],
  image: { layout: 'constrained' },
  fonts: [
    {
      provider: fontProviders.bunny(),
      name: 'Big Shoulders Display',
      cssVariable: '--font-display',
      weights: [300, 600],
      styles: ['normal'],
      subsets: ['latin', 'latin-ext'],
      fallbacks: ['Impact', 'sans-serif'],
      display: 'swap',
    },
    {
      provider: fontProviders.bunny(),
      name: 'Plus Jakarta Sans',
      cssVariable: '--font-body',
      weights: [400, 600],
      styles: ['normal'],
      subsets: ['latin', 'latin-ext'],
      fallbacks: ['system-ui', 'sans-serif'],
      display: 'swap',
    },
  ],
});
```

- [ ] **Step 4: Write `package.json` scripts**

Replace the `scripts` block with:

```json
"scripts": {
  "dev": "astro dev",
  "build": "astro build",
  "preview": "astro preview",
  "check": "astro check",
  "test": "node --test src/lib/",
  "test:dist": "node --test tests/"
}
```

Keep `"type": "module"`.

- [ ] **Step 5: Write `netlify.toml`, `.nvmrc`, `robots.txt`, `.gitignore`**

`netlify.toml`:

```toml
[build]
command = "npm run build"
publish = "dist"

[build.environment]
NODE_VERSION = "22"
```

`.nvmrc`:

```
22
```

`public/robots.txt`:

```
User-agent: *
Allow: /
Sitemap: https://letsmakeamark.brussels/sitemap-index.xml
```

Append to `.gitignore`:

```
scratch-astro/
```

- [ ] **Step 6: Minimal index page**

`src/pages/index.astro`:

```astro
---
---
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <title>Make a Mark Brussels</title>
  </head>
  <body>
    <h1>Make a Mark Brussels</h1>
  </body>
</html>
```

- [ ] **Step 7: Build and verify fonts are bundled**

```bash
npm run build && ls dist/_astro | grep -i -E 'shoulders|jakarta' | head
```

Expected: build succeeds, `dist/index.html` exists, and font files with those names appear (the Fonts API downloads them at build time). If the grep is empty, check the build log for a fonts warning and fix the provider config before continuing.

- [ ] **Step 8: Commit**

```bash
git add package.json package-lock.json astro.config.mjs tsconfig.json netlify.toml .nvmrc .gitignore src/pages/index.astro public/robots.txt
git commit -m "chore(scaffold): astro 7 project with bunny fonts, sitemap and netlify config

- Minimal Astro scaffold, static output
- Fonts API with the bunny.net provider for Big Shoulders Display and Plus Jakarta Sans
- netlify.toml, .nvmrc, robots.txt

Why: the site replaces a Google Doc and deploys on Netlify.

Co-Authored-By: Claude Fable 5.1 <noreply@anthropic.com>
Claude-Session: https://claude.ai/code/session_01XrYNvFcR2mGBmwudJ9Kt2t"
```

---

### Task 2: Tokens, global styles, base layout

**Files:**
- Create: `src/styles/tokens.css`, `src/styles/global.css`, `src/layouts/Base.astro`, `public/favicon.svg`
- Modify: `src/pages/index.astro`

**Interfaces:**
- Produces: `Base.astro` with props `{ title: string; description: string; ogImage?: string }` and a default slot. Global classes: `.container`, `.block`, `.block--red`, `.h-display`, `.kicker`, `.pill`, `.pill--solid`, `.duo`, `.visually-hidden`. Custom properties listed below.

- [ ] **Step 1: Write `src/styles/tokens.css`**

```css
:root {
  --cream: #F5E2CA;
  --cream-light: #FFF6EA;
  --red: #C33F40;
  --red-2: #D14B4C;
  --ink: #1E1A16;
  --tint: rgba(121, 26, 27, 0.3);

  --radius-block: 18px;
  --radius-card: 10px;
  --radius-pill: 999px;

  --container: 1360px;
  --gutter: clamp(20px, 3vw, 40px);
  --block-margin: clamp(12px, 2vw, 24px);
  --section-y: clamp(56px, 8vw, 96px);

  --fs-hero: clamp(56px, 9vw, 118px);
  --fs-h2: clamp(40px, 6vw, 78px);
  --fs-h3: clamp(26px, 3vw, 34px);
  --fs-num: clamp(48px, 5vw, 64px);
  --fs-body: 17px;
  --fs-small: 13px;

  --track-caps: 0.12em;
}
```

- [ ] **Step 2: Write `src/styles/global.css`**

```css
@import './tokens.css';

*, *::before, *::after { box-sizing: border-box; }
html { background: var(--cream); color: var(--ink); scroll-behavior: smooth; }
body { margin: 0; font-family: var(--font-body); font-size: var(--fs-body); line-height: 1.6; }
img { max-width: 100%; height: auto; display: block; }
a { color: inherit; }
p { margin: 0 0 1em; }
h1, h2, h3 { margin: 0; }

.container { max-width: var(--container); margin-inline: auto; padding-inline: var(--gutter); }

.h-display {
  font-family: var(--font-display);
  font-weight: 600;
  text-transform: uppercase;
  letter-spacing: 0.01em;
  line-height: 0.92;
}
.h-display--light { font-weight: 300; }
.kicker {
  font-size: 12px;
  font-weight: 600;
  letter-spacing: var(--track-caps);
  text-transform: uppercase;
}

.block {
  margin: 0 var(--block-margin) var(--block-margin);
  border-radius: var(--radius-block);
  padding: var(--section-y) clamp(20px, 4vw, 56px);
}
.block--red { background: var(--red); color: var(--cream-light); }
.block--red .h-display { color: var(--cream); }

.pill {
  display: inline-flex;
  align-items: center;
  gap: 10px;
  border: 1.5px solid currentColor;
  border-radius: var(--radius-pill);
  padding: 12px 20px;
  font-size: 12px;
  font-weight: 600;
  letter-spacing: var(--track-caps);
  text-transform: uppercase;
  text-decoration: none;
  color: inherit;
}
a.pill:hover, a.pill:focus-visible { background: currentColor; }
a.pill:hover > span, a.pill:focus-visible > span { color: var(--cream); }
.block--red a.pill:hover > span, .block--red a.pill:focus-visible > span { color: var(--red); }
.pill--solid { background: var(--red); color: var(--cream); border-color: var(--red); }

.duo { position: relative; }
.duo img { filter: grayscale(1) contrast(1.05); }
.duo::after { content: ""; position: absolute; inset: 0; background: var(--tint); mix-blend-mode: multiply; pointer-events: none; }

.visually-hidden { position: absolute; width: 1px; height: 1px; overflow: hidden; clip: rect(0 0 0 0); white-space: nowrap; }
.skip-link { position: absolute; left: 12px; top: -60px; background: var(--ink); color: var(--cream); padding: 8px 12px; border-radius: 6px; z-index: 100; }
.skip-link:focus { top: 12px; }

@media (prefers-reduced-motion: reduce) {
  html { scroll-behavior: auto; }
  * { transition: none !important; animation: none !important; }
}
```

Pill text must be wrapped in a `<span>` by every user of `.pill` so the hover colour swap works.

- [ ] **Step 3: Write `src/layouts/Base.astro`**

```astro
---
import '../styles/global.css';
import { Font } from 'astro:assets';

interface Props {
  title: string;
  description: string;
  ogImage?: string;
}
const { title, description, ogImage = '/og.png' } = Astro.props;
const canonical = new URL(Astro.url.pathname, Astro.site);
const ogUrl = new URL(ogImage, Astro.site);
---
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>{title}</title>
    <meta name="description" content={description} />
    <link rel="canonical" href={canonical} />
    <link rel="icon" href="/favicon.svg" type="image/svg+xml" />
    <link rel="sitemap" href="/sitemap-index.xml" />
    <meta property="og:type" content="website" />
    <meta property="og:title" content={title} />
    <meta property="og:description" content={description} />
    <meta property="og:url" content={canonical} />
    <meta property="og:image" content={ogUrl} />
    <meta name="twitter:card" content="summary_large_image" />
    <Font cssVariable="--font-display" preload />
    <Font cssVariable="--font-body" preload />
  </head>
  <body>
    <a class="skip-link" href="#main">Skip to content</a>
    <slot />
  </body>
</html>
```

- [ ] **Step 4: Write `public/favicon.svg`**

```svg
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 64"><rect width="64" height="64" rx="12" fill="#C33F40"/><path d="M14 46 L24 18 L32 36 L40 18 L50 46" fill="none" stroke="#F5E2CA" stroke-width="6" stroke-linejoin="round" stroke-linecap="round"/></svg>
```

- [ ] **Step 5: Use the layout in `src/pages/index.astro`**

```astro
---
import Base from '../layouts/Base.astro';
---
<Base title="Make a Mark Brussels" description="A make-a-thon where creatives give Brussels social-profits a day of design work.">
  <main id="main">
    <div class="container">
      <h1 class="h-display" style="font-size: var(--fs-hero); color: var(--red-2)">Make a Mark Brussels</h1>
      <a class="pill" href="#main"><span>Pill check</span></a>
    </div>
  </main>
</Base>
```

- [ ] **Step 6: Build and check**

```bash
npm run build && grep -c -- '--font-display' dist/index.html && grep -o 'og:image" content="[^"]*"' dist/index.html
```

Expected: build ok, the font variable appears at least once, og:image is `https://letsmakeamark.brussels/og.png`.

- [ ] **Step 7: Commit**

```bash
git add src/styles src/layouts public/favicon.svg src/pages/index.astro
git commit -m "feat(layout): tokens, global styles and base layout

- Colour and type tokens from the spec
- Global container, block, pill, duotone and skip-link styles
- Base layout with meta, Open Graph and preloaded fonts

Why: every section builds on these tokens and classes.

Co-Authored-By: Claude Fable 5.1 <noreply@anthropic.com>
Claude-Session: https://claude.ai/code/session_01XrYNvFcR2mGBmwudJ9Kt2t"
```

---

### Task 3: Content collections, data files, edition status logic

**Files:**
- Create: `src/content.config.ts`, `src/content/editions/2026.md`, `src/content/faq/01-google-doc.md`, `02-paid.md`, `03-organisations-pay.md`, `04-bruzz.md`, `05-your-city.md`, `src/content/data/organisations.json`, `src/content/data/site.json`, `src/content/data/stories.json`, `src/lib/site.ts`, `src/lib/stories.ts`, `src/lib/edition-status.ts`, `src/lib/edition-status.test.ts`
- Copy: `.lavish/assets/story-1.jpg` .. `story-9.jpg` to `src/assets/stories/`, `.lavish/assets/logo-brussels.png` to `src/assets/logo-brussels.png`

**Interfaces:**
- Produces:
  - collections `editions` (schema below), `organisations` (`{ id, name, url? }`), `faq` (`{ question, order }` with markdown body)
  - `src/lib/site.ts`: `export const site: Site` (validated) and `export const stories: Story[]`
  - `src/lib/stories.ts`: `export function storyImage(file: string): ImageMetadata`
  - `src/lib/edition-status.ts`: `export type EditionStatus = 'open' | 'closed' | 'selected' | 'done'`; `export interface PillModel { label: string; href?: string }`; `export function statusPill(input: { status: EditionStatus; year: number; applyUrl?: string; instagramUrl: string }): PillModel`

- [ ] **Step 1: Copy the images**

```bash
mkdir -p src/assets/stories && cp .lavish/assets/story-*.jpg src/assets/stories/ && cp .lavish/assets/logo-brussels.png src/assets/logo-brussels.png && ls src/assets/stories | wc -l
```

Expected: 9.

- [ ] **Step 2: Write the failing test for `statusPill`**

`src/lib/edition-status.test.ts`:

```ts
import { test } from 'node:test';
import assert from 'node:assert/strict';
import { statusPill } from './edition-status.ts';

const instagramUrl = 'https://www.instagram.com/lets_makeamarkbxl/';

test('open links to the application form', () => {
  const pill = statusPill({ status: 'open', year: 2026, applyUrl: 'https://forms.example/apply', instagramUrl });
  assert.deepEqual(pill, { label: 'Apply as a Maker', href: 'https://forms.example/apply' });
});

test('open without an applyUrl falls back to closed wording', () => {
  const pill = statusPill({ status: 'open', year: 2026, instagramUrl });
  assert.deepEqual(pill, { label: 'Applications are closed for 2026' });
});

test('closed is a label with the year', () => {
  assert.deepEqual(statusPill({ status: 'closed', year: 2026, instagramUrl }), { label: 'Applications are closed for 2026' });
});

test('selected is a label', () => {
  assert.deepEqual(statusPill({ status: 'selected', year: 2026, instagramUrl }), { label: 'Makers have been selected, see you in March' });
});

test('done links to instagram', () => {
  assert.deepEqual(statusPill({ status: 'done', year: 2026, instagramUrl }), { label: 'Read about the 2026 edition on Instagram', href: instagramUrl });
});
```

- [ ] **Step 3: Run the test, expect failure**

```bash
npm test
```

Expected: fails with a module-not-found error for `./edition-status.ts`.

- [ ] **Step 4: Implement `src/lib/edition-status.ts`**

```ts
export type EditionStatus = 'open' | 'closed' | 'selected' | 'done';

export interface PillModel {
  label: string;
  href?: string;
}

export function statusPill(input: {
  status: EditionStatus;
  year: number;
  applyUrl?: string;
  instagramUrl: string;
}): PillModel {
  const { status, year, applyUrl, instagramUrl } = input;
  if (status === 'open' && applyUrl) return { label: 'Apply as a Maker', href: applyUrl };
  if (status === 'selected') return { label: 'Makers have been selected, see you in March' };
  if (status === 'done') return { label: `Read about the ${year} edition on Instagram`, href: instagramUrl };
  return { label: `Applications are closed for ${year}` };
}
```

- [ ] **Step 5: Run the test, expect pass**

```bash
npm test
```

Expected: 5 passing.

- [ ] **Step 6: Write `src/content.config.ts`**

```ts
import { defineCollection } from 'astro:content';
import { glob, file } from 'astro/loaders';
import { z } from 'astro/zod';

const editions = defineCollection({
  loader: glob({ pattern: '*.md', base: './src/content/editions' }),
  schema: z.object({
    year: z.number().int(),
    kicker: z.string(),
    title: z.string(),
    dates: z.string(),
    startDate: z.coerce.date(),
    endDate: z.coerce.date(),
    status: z.enum(['open', 'closed', 'selected', 'done']),
    applyUrl: z.url().optional(),
    case: z.object({ name: z.string(), url: z.url() }),
    photo: z.string(),
    photoAlt: z.string(),
    callToAction: z.string(),
  }),
});

const organisations = defineCollection({
  loader: file('./src/content/data/organisations.json'),
  schema: z.object({
    id: z.string(),
    name: z.string(),
    url: z.url().optional(),
  }),
});

const faq = defineCollection({
  loader: glob({ pattern: '*.md', base: './src/content/faq' }),
  schema: z.object({
    question: z.string(),
    order: z.number().int(),
  }),
});

export const collections = { editions, organisations, faq };
```

- [ ] **Step 7: Write `src/content/editions/2026.md`**

```md
---
year: 2026
kicker: Special edition 2026
title: Make a Mark for the Planet
dates: Friday evening March 20 and all day Saturday March 21
startDate: 2026-03-20
endDate: 2026-03-21
status: closed
case:
  name: Klimaatzaak / L'Affaire Climat / Climate Case
  url: https://klimaatzaak.eu/en/
photo: story-5.jpg
photoAlt: A team at work during Make a Mark Brussels
callToAction: Can we count on you? Bring your creative friends and colleagues too!
---
The eighth edition of our make-a-thon for Brussels is a special one, placing climate communication at the centre: we'll Make a Mark for the Planet.

Our central case is [Klimaatzaak / L'Affaire Climat / Climate Case](https://klimaatzaak.eu/en/), a landmark climate case with impact far beyond Belgium. The climate crisis remains an existential challenge, yet it is increasingly pushed to the background. We need your brainpower to break through this trend.
```

- [ ] **Step 8: Write the five FAQ files**

`src/content/faq/01-google-doc.md`:

```md
---
question: Is this website really just a Google doc?
order: 1
---
Not any more. It was, for seven years.
```

`src/content/faq/02-paid.md`:

```md
---
question: Do Makers get paid for the day of work?
order: 2
---
Kind of. In drinks, delicious snacks and good vibes.
```

`src/content/faq/03-organisations-pay.md`:

```md
---
question: Do organisations need to pay to participate?
order: 3
---
Nope. The Makers gift their time and talent to help Brussels. No strings attached.
```

`src/content/faq/04-bruzz.md`:

```md
---
question: Is there a Bruzz article with video that can give me a sense of the vibe of the event?
order: 4
---
Yes, there is. [You can find it here](https://www.bruzz.be/samenleving/creatieve-marathon-make-mark-maakt-brussel-opnieuw-een-beetje-beter-2023-03-19).
```

`src/content/faq/05-your-city.md`:

```md
---
question: Would you like to organize Make a Mark in your city?
order: 5
---
Go for it! It's already happening in tens of cities around the globe. You can find guides and checklists on the [global website of Make a Mark](https://www.letsmakeamark.org/).
```

- [ ] **Step 9: Write `src/content/data/organisations.json`**

Ids are lowercase slugs. Full list, 56 entries:

```json
[
  { "id": "100pap", "name": "100PAP" },
  { "id": "apomo", "name": "Apomo" },
  { "id": "aprestoe", "name": "Aprèstoe" },
  { "id": "association-belge-des-paralyses", "name": "Association Belge des Paralysés" },
  { "id": "atelier-groot-eiland", "name": "Atelier Groot-Eiland" },
  { "id": "baob-brussels", "name": "Baob Brussels" },
  { "id": "bazaar-trottoir", "name": "Bazaar Trottoir" },
  { "id": "belgium-kitchen", "name": "Belgium Kitchen" },
  { "id": "bru-divers", "name": "Bru-Divers" },
  { "id": "buurtpensioen", "name": "Buurtpensioen / Pens(i)onsQuartier" },
  { "id": "cltb", "name": "CLTB" },
  { "id": "campaign-58", "name": "Campaign 58" },
  { "id": "carrousel", "name": "Carrousel" },
  { "id": "children-museum", "name": "Children Museum" },
  { "id": "cinemaximiliaan", "name": "Cinemaximiliaan" },
  { "id": "circus-zonder-handen", "name": "Circus Zonder Handen" },
  { "id": "communa", "name": "Communa" },
  { "id": "cultureghem", "name": "Cultureghem" },
  { "id": "cyclo", "name": "Cyclo" },
  { "id": "de-harmonie", "name": "De Harmonie" },
  { "id": "diogenes", "name": "Diogenes" },
  { "id": "eat-vzw", "name": "EAT vzw" },
  { "id": "eatmosphere", "name": "Eatmosphere" },
  { "id": "fair-ground", "name": "Fair Ground" },
  { "id": "groot-eiland", "name": "Groot Eiland" },
  { "id": "growfunding", "name": "Growfunding" },
  { "id": "heroes-for-zero", "name": "Heroes for Zero" },
  { "id": "kaos", "name": "KAOS" },
  { "id": "kind-ouder-en-kanker", "name": "Kind, Ouder en Kanker" },
  { "id": "kuumba", "name": "Kuumba" },
  { "id": "labolobo", "name": "Labolobo" },
  { "id": "ladderop", "name": "Ladder'op" },
  { "id": "les-gazelles-de-bruxelles", "name": "Les Gazelles de Bruxelles" },
  { "id": "lokale-dienstencentra", "name": "Lokale dienstencentra" },
  { "id": "mani", "name": "Mani" },
  { "id": "minor-ndako", "name": "Minor-Ndako" },
  { "id": "molenbeek-rebels-basketball", "name": "Molenbeek Rebels Basketball" },
  { "id": "nasci", "name": "NASCI" },
  { "id": "oasis-heyvaert", "name": "Oasis Heyvaert" },
  { "id": "pool-is-cool", "name": "Pool is cool" },
  { "id": "pigment", "name": "Pigment" },
  { "id": "point-stalingrad", "name": "Point Stalingrad" },
  { "id": "rwdm-girls", "name": "RWDM girls" },
  { "id": "rainbowhouse-brussels", "name": "RainbowHouse Brussels" },
  { "id": "solumob-volontaires", "name": "Solumob Volontaires" },
  { "id": "steunpunt-vrijwilligerswerk-brussel", "name": "Steunpunt Vrijwilligerswerk Brussel" },
  { "id": "tadaforlife", "name": "Tadaforlife" },
  { "id": "toestand", "name": "Toestand" },
  { "id": "tournevie", "name": "Tournevie" },
  { "id": "urban-center-brussel", "name": "Urban Center Brussel" },
  { "id": "wheel-of-care", "name": "Wheel of Care" },
  { "id": "wijkhuis-chambery", "name": "Wijkhuis Chambery" },
  { "id": "zinne", "name": "Zinne" },
  { "id": "zinneguides", "name": "Zinneguides" },
  { "id": "zwanger-in-brussel", "name": "Zwanger in Brussel" }
]
```

That is 55 entries: the doc's list counts "Kind, Ouder en Kanker" as one organisation. Use 55 everywhere the number appears, not 56.

- [ ] **Step 10: Write `src/content/data/site.json`**

```json
{
  "name": "Make a Mark Brussels",
  "shortName": "Make a Mark",
  "domain": "https://letsmakeamark.brussels",
  "instagramUrl": "https://www.instagram.com/lets_makeamarkbxl/",
  "email": "makeamarkbxl@gmail.com",
  "globalSiteUrl": "https://www.letsmakeamark.org/",
  "firstEditionYear": 2016,
  "meta": {
    "title": "Make a Mark Brussels",
    "description": "A make-a-thon where hundreds of creatives give Brussels social-profits a day of voluntary design work. Eighth edition: Make a Mark for the Planet, 20 and 21 March 2026."
  },
  "nav": [
    { "label": "Makers", "href": "#makers" },
    { "label": "Organisations", "href": "#organisations" },
    { "label": "FAQ", "href": "#faq" },
    { "label": "About", "href": "#about" }
  ],
  "hero": {
    "headline": "Hundreds of creatives. One day. Brussels.",
    "intro": "Make a Mark Brussels is a make-a-thon. Since 2016, hundreds of creatives have made Brussels social-profits look fabulous with a day of voluntary design work.",
    "instagramLabel": "See their work on Instagram"
  },
  "makers": {
    "heading": "Info for Makers",
    "steps": [
      { "title": "Friday evening", "text": "We all come together to plan and get to know each other. To make sure everyone can go in turbo creative mode Saturday." },
      { "title": "All day Saturday", "text": "You'll spend the whole of Saturday collaborating in a team, tackling a creative challenge for the central case. We'll provide WiFi, food, drinks, and great company to keep you energized throughout the day." },
      { "title": "Selected in advance", "text": "We select Makers in advance, ensuring we form the most effective teams to tackle the challenges. We'll let you know at the end of January if you got selected. A few weeks after that you'll meet your team members and the organisation to discuss their challenge, in a one-hour meeting on an evening in the first week of March." }
    ],
    "closing": "Please make sure you can make it."
  },
  "organisations": {
    "heading": "55 social-profits made their mark",
    "intro": "In the past years hundreds of creatives have made social-profits look fabulous with a day of voluntary design work. These are the previous social organisations that were helped."
  },
  "faq": { "heading": "FAQ" },
  "about": {
    "heading": "About the organizers",
    "intro": "This is already the 8th edition of Make a Mark Brussels.",
    "organisers": [
      { "name": "Gwen Dubois", "role": "The instigator. An interior architect and creative activist from Jette.", "url": "https://www.gwentibold.com/" },
      { "name": "Piet Lambrecht", "role": "Organising it from the start with Gwen. Until recently he led the communication of Muntpunt.", "url": "https://www.brusseleir.eu/neus/piet-lambrechts-brussel-komen-kijken-en-het-willen-zien/" }
    ],
    "contactLabel": "Contact us"
  },
  "footer": {
    "globalLabel": "Part of the global Make a Mark movement",
    "instagramLabel": "Instagram",
    "emailLabel": "Email"
  }
}
```

- [ ] **Step 11: Write `src/content/data/stories.json`**

```json
[
  { "file": "story-3.jpg", "alt": "A maker working on a laptop with a teammate looking on", "credit": "@cardemom" },
  { "file": "story-9.jpg", "alt": "A photographer directing a group portrait of an organisation", "credit": "@brion.dominique" },
  { "file": "story-4.jpg", "alt": "Collage announcing Make a Mark: ten non-profits, ten teams, sixty creatives", "credit": "@lets_makeamarkbxl" },
  { "file": "story-6.jpg", "alt": "A photographer crouching to shoot a mural and a table of drinks", "credit": "@dieter.daniels" },
  { "file": "story-7.jpg", "alt": "A team posing together at the evening presentation", "credit": "@cardemom" },
  { "file": "story-8.jpg", "alt": "A designer presenting a visual identity on stage", "credit": "@lets_makeamarkbxl" },
  { "file": "story-2.jpg", "alt": "Two makers sketching by a window", "credit": "@cardemom" },
  { "file": "story-1.jpg", "alt": "The crowd at the closing presentations", "credit": "@lets_makeamarkbxl" },
  { "file": "story-5.jpg", "alt": "Concept sketches spread over a table", "credit": "@cardemom" }
]
```

- [ ] **Step 12: Write `src/lib/site.ts` and `src/lib/stories.ts`**

`src/lib/site.ts`:

```ts
import { z } from 'astro/zod';
import siteJson from '../content/data/site.json';
import storiesJson from '../content/data/stories.json';

const link = z.object({ label: z.string(), href: z.string() });

const SiteSchema = z.object({
  name: z.string(),
  shortName: z.string(),
  domain: z.url(),
  instagramUrl: z.url(),
  email: z.email(),
  globalSiteUrl: z.url(),
  firstEditionYear: z.number().int(),
  meta: z.object({ title: z.string(), description: z.string() }),
  nav: z.array(link),
  hero: z.object({ headline: z.string(), intro: z.string(), instagramLabel: z.string() }),
  makers: z.object({
    heading: z.string(),
    steps: z.array(z.object({ title: z.string(), text: z.string() })),
    closing: z.string(),
  }),
  organisations: z.object({ heading: z.string(), intro: z.string() }),
  faq: z.object({ heading: z.string() }),
  about: z.object({
    heading: z.string(),
    intro: z.string(),
    organisers: z.array(z.object({ name: z.string(), role: z.string(), url: z.url() })),
    contactLabel: z.string(),
  }),
  footer: z.object({ globalLabel: z.string(), instagramLabel: z.string(), emailLabel: z.string() }),
});

const StorySchema = z.object({ file: z.string(), alt: z.string(), credit: z.string() });

export type Site = z.infer<typeof SiteSchema>;
export type Story = z.infer<typeof StorySchema>;

export const site: Site = SiteSchema.parse(siteJson);
export const stories: Story[] = z.array(StorySchema).parse(storiesJson);
```

`src/lib/stories.ts`:

```ts
import type { ImageMetadata } from 'astro';

const images = import.meta.glob<{ default: ImageMetadata }>('../assets/stories/*.{jpg,jpeg,png}', { eager: true });

export function storyImage(file: string): ImageMetadata {
  const mod = images[`../assets/stories/${file}`];
  if (!mod) throw new Error(`Story image not found in src/assets/stories: ${file}`);
  return mod.default;
}
```

- [ ] **Step 13: Prove the collections load**

Temporarily make `src/pages/index.astro` print counts:

```astro
---
import Base from '../layouts/Base.astro';
import { getCollection } from 'astro:content';
import { site, stories } from '../lib/site';
import { storyImage } from '../lib/stories';
const editions = await getCollection('editions');
const organisations = await getCollection('organisations');
const faq = await getCollection('faq');
const firstStory = storyImage(stories[0].file);
---
<Base title={site.meta.title} description={site.meta.description}>
  <main id="main">
    <p>{editions.length} editions, {organisations.length} organisations, {faq.length} faq, first story {firstStory.width}x{firstStory.height}</p>
  </main>
</Base>
```

```bash
npm run check && npm run build && grep -o '1 editions, 55 organisations, 5 faq, first story [0-9]*x[0-9]*' dist/index.html
```

Expected: `astro check` reports 0 errors and the grep prints the sentence.

- [ ] **Step 14: Commit**

```bash
git add src/content.config.ts src/content src/assets src/lib src/pages/index.astro
git commit -m "feat(content): collections, site data, stories and edition status logic

- editions, organisations and faq collections with zod schemas
- site.json and stories.json validated in src/lib/site.ts
- statusPill() maps edition status to the call-to-action, with tests
- nine placeholder story photos and the hand-drawn logo

Why: every visible string lives in content files, so organisers edit data, not components.

Co-Authored-By: Claude Fable 5.1 <noreply@anthropic.com>
Claude-Session: https://claude.ai/code/session_01XrYNvFcR2mGBmwudJ9Kt2t"
```

---

### Task 4: Nav, Footer, Pill, page skeleton with anchors, dist test

**Files:**
- Create: `src/components/Nav.astro`, `src/components/Footer.astro`, `src/components/Pill.astro`, `tests/dist.test.ts`
- Modify: `src/pages/index.astro`

**Interfaces:**
- Consumes: `site` from `src/lib/site.ts`.
- Produces: `Pill.astro` props `{ href?: string; solid?: boolean; class?: string }` with a default slot; `Nav.astro` and `Footer.astro` take no props. Section ids on the page: `top`, `edition`, `makers`, `organisations`, `faq`, `about`.

- [ ] **Step 1: Write the failing dist test**

`tests/dist.test.ts`:

```ts
import { test } from 'node:test';
import assert from 'node:assert/strict';
import { readFileSync } from 'node:fs';

const html = readFileSync(new URL('../dist/index.html', import.meta.url), 'utf8');

test('one h1', () => {
  assert.equal((html.match(/<h1[\s>]/g) ?? []).length, 1);
});

test('every section anchor exists once', () => {
  for (const id of ['top', 'edition', 'makers', 'organisations', 'faq', 'about']) {
    assert.equal((html.match(new RegExp(`id="${id}"`, 'g')) ?? []).length, 1, id);
  }
});

test('nav links point at sections', () => {
  for (const href of ['#makers', '#organisations', '#faq', '#about']) {
    assert.ok(html.includes(`href="${href}"`), href);
  }
});

test('external links from the doc are present', () => {
  for (const href of [
    'https://klimaatzaak.eu/en/',
    'https://www.instagram.com/lets_makeamarkbxl/',
    'https://www.bruzz.be/samenleving/creatieve-marathon-make-mark-maakt-brussel-opnieuw-een-beetje-beter-2023-03-19',
    'https://www.letsmakeamark.org/',
    'https://www.gwentibold.com/',
    'https://www.brusseleir.eu/neus/piet-lambrechts-brussel-komen-kijken-en-het-willen-zien/',
    'mailto:makeamarkbxl@gmail.com',
  ]) {
    assert.ok(html.includes(`href="${href}"`), href);
  }
});

test('no undefined or [object Object] leaked into the html', () => {
  assert.ok(!html.includes('undefined'));
  assert.ok(!html.includes('[object Object]'));
});

test('55 organisations are listed', () => {
  assert.equal((html.match(/class="org"/g) ?? []).length, 55);
});

test('the status pill reads closed for 2026', () => {
  assert.ok(html.includes('Applications are closed for 2026'));
});

test('five faq items', () => {
  assert.equal((html.match(/<details/g) ?? []).length, 5);
});
```

- [ ] **Step 2: Run it, expect failures**

```bash
npm run build && npm run test:dist
```

Expected: the anchor, links, organisations, pill and faq tests fail. The `undefined` test may pass. That is fine; the later tasks turn the rest green.

- [ ] **Step 3: Write `src/components/Pill.astro`**

```astro
---
interface Props {
  href?: string;
  solid?: boolean;
  class?: string;
}
const { href, solid = false, class: className = '' } = Astro.props;
const classes = ['pill', solid ? 'pill--solid' : '', className].filter(Boolean).join(' ');
const external = href?.startsWith('http');
---
{href ? (
  <a class={classes} href={href} rel={external ? 'noopener' : undefined} target={external ? '_blank' : undefined}><span><slot /></span></a>
) : (
  <span class={classes}><span><slot /></span></span>
)}
```

- [ ] **Step 4: Write `src/components/Nav.astro`**

```astro
---
import { site } from '../lib/site';
---
<header class="nav">
  <div class="container nav__inner">
    <a class="nav__wordmark h-display" href="#top">{site.name}</a>
    <button class="nav__toggle" type="button" aria-expanded="false" aria-controls="nav-links" aria-label="Menu">
      <i></i>
    </button>
    <nav id="nav-links" class="nav__links" aria-label="Sections">
      {site.nav.map((item) => <a href={item.href}>{item.label}</a>)}
    </nav>
  </div>
</header>

<style>
  .nav { position: sticky; top: 0; z-index: 50; background: var(--cream); }
  .nav__inner { display: flex; align-items: center; justify-content: space-between; gap: 24px; padding-block: 18px; }
  .nav__wordmark { font-size: 22px; color: var(--red-2); text-decoration: none; }
  .nav__links { display: flex; gap: 28px; }
  .nav__links a { font-size: 12px; font-weight: 600; letter-spacing: var(--track-caps); text-transform: uppercase; text-decoration: none; color: var(--red-2); }
  .nav__links a:hover, .nav__links a:focus-visible { text-decoration: underline; text-underline-offset: 4px; }
  .nav__toggle { display: none; width: 40px; height: 40px; border-radius: 50%; border: 0; background: var(--red); cursor: pointer; place-items: center; }
  .nav__toggle i { display: block; width: 16px; height: 2px; background: var(--cream); box-shadow: 0 -5px 0 var(--cream), 0 5px 0 var(--cream); }
  @media (max-width: 760px) {
    .nav__toggle { display: grid; }
    .nav__links { display: none; position: absolute; left: 0; right: 0; top: 100%; flex-direction: column; gap: 0; background: var(--cream); border-bottom: 1px solid rgba(30,26,22,.12); padding: 8px var(--gutter) 16px; }
    .nav__links a { padding: 12px 0; font-size: 14px; }
    .nav.is-open .nav__links { display: flex; }
  }
</style>

<script>
  const nav = document.querySelector<HTMLElement>('.nav');
  const toggle = document.querySelector<HTMLButtonElement>('.nav__toggle');
  if (nav && toggle) {
    toggle.addEventListener('click', () => {
      const open = nav.classList.toggle('is-open');
      toggle.setAttribute('aria-expanded', String(open));
    });
    nav.querySelectorAll('.nav__links a').forEach((a) =>
      a.addEventListener('click', () => {
        nav.classList.remove('is-open');
        toggle.setAttribute('aria-expanded', 'false');
      }),
    );
  }
</script>
```

- [ ] **Step 5: Write `src/components/Footer.astro`**

```astro
---
import { site } from '../lib/site';
---
<footer class="footer">
  <div class="container footer__inner">
    <div>
      <div class="footer__wordmark h-display">{site.name}</div>
      <a class="footer__global" href={site.globalSiteUrl} rel="noopener" target="_blank">{site.footer.globalLabel}</a>
    </div>
    <nav class="footer__links" aria-label="Footer">
      {site.nav.map((item) => <a href={item.href}>{item.label}</a>)}
      <a href={site.instagramUrl} rel="noopener" target="_blank">{site.footer.instagramLabel}</a>
      <a href={`mailto:${site.email}`}>{site.footer.emailLabel}</a>
    </nav>
  </div>
</footer>

<style>
  .footer { background: var(--red); color: var(--cream-light); margin: 0 var(--block-margin) var(--block-margin); border-radius: var(--radius-block); padding: 40px 0; }
  .footer__inner { display: flex; justify-content: space-between; align-items: flex-start; gap: 24px; flex-wrap: wrap; }
  .footer__wordmark { font-size: 22px; color: var(--cream); }
  .footer__global { display: block; margin-top: 8px; font-size: var(--fs-small); color: var(--cream-light); }
  .footer__links { display: flex; gap: 22px; flex-wrap: wrap; }
  .footer__links a { font-size: 12px; font-weight: 600; letter-spacing: var(--track-caps); text-transform: uppercase; text-decoration: none; color: var(--cream); }
  .footer__links a:hover, .footer__links a:focus-visible { text-decoration: underline; text-underline-offset: 4px; }
</style>
```

- [ ] **Step 6: Page skeleton in `src/pages/index.astro`**

```astro
---
import Base from '../layouts/Base.astro';
import Nav from '../components/Nav.astro';
import Footer from '../components/Footer.astro';
import { site } from '../lib/site';
---
<Base title={site.meta.title} description={site.meta.description}>
  <Nav />
  <main id="main">
    <section id="top" class="container"><h1 class="h-display">{site.hero.headline}</h1></section>
    <section id="edition" class="block block--red"><h2 class="h-display">Edition</h2></section>
    <section id="makers" class="container"><h2 class="h-display">{site.makers.heading}</h2></section>
    <section id="organisations" class="block block--red"><h2 class="h-display">{site.organisations.heading}</h2></section>
    <section id="faq" class="container"><h2 class="h-display">{site.faq.heading}</h2></section>
    <section id="about" class="container"><h2 class="h-display">{site.about.heading}</h2></section>
  </main>
  <Footer />
</Base>
```

- [ ] **Step 7: Build and run the dist test**

```bash
npm run check && npm run build && npm run test:dist
```

Expected: `one h1`, `every section anchor`, `nav links`, `no undefined` pass. `external links`, `55 organisations`, `status pill`, `five faq` still fail.

- [ ] **Step 8: Commit**

```bash
git add src/components/Nav.astro src/components/Footer.astro src/components/Pill.astro src/pages/index.astro tests/dist.test.ts
git commit -m "feat(shell): nav, footer, pill and the page skeleton with section anchors

- Sticky nav with wordmark, anchor links and a mobile toggle
- Red footer with nav, Instagram, email and the global site link
- Pill component used by every call to action
- dist test asserting anchors, links and counts

Why: gives every later section a place and a test to turn green.

Co-Authored-By: Claude Fable 5.1 <noreply@anthropic.com>
Claude-Session: https://claude.ai/code/session_01XrYNvFcR2mGBmwudJ9Kt2t"
```

---

### Task 5: Hero and stories strip

**Files:**
- Create: `src/components/Hero.astro`, `src/components/StoriesStrip.astro`
- Modify: `src/pages/index.astro`

**Interfaces:**
- Consumes: `site`, `stories` from `src/lib/site.ts`; `storyImage` from `src/lib/stories.ts`; `Pill.astro`.
- Produces: `Hero.astro` (no props, renders the `#top` section and the h1), `StoriesStrip.astro` (no props).

- [ ] **Step 1: Write `src/components/Hero.astro`**

```astro
---
import { site } from '../lib/site';
---
<section id="top" class="hero container">
  <h1 class="h-display hero__headline">{site.hero.headline}</h1>
  <p class="hero__intro">{site.hero.intro}</p>
</section>

<style>
  .hero { text-align: center; padding-block: clamp(32px, 6vw, 72px) 0; }
  .hero__headline { font-size: var(--fs-hero); color: var(--red-2); max-width: 14ch; margin-inline: auto; }
  .hero__intro { max-width: 560px; margin: 24px auto 0; font-size: clamp(16px, 1.4vw, 19px); color: var(--red-2); }
</style>
```

- [ ] **Step 2: Write `src/components/StoriesStrip.astro`**

```astro
---
import { Image } from 'astro:assets';
import { site, stories } from '../lib/site';
import { storyImage } from '../lib/stories';
import Pill from './Pill.astro';
---
<section class="strip" aria-label="Photos from past editions">
  <ul class="strip__list">
    {stories.map((story, i) => (
      <li class="strip__item" style={`--rot: ${[-2, 1, -1, 2, -2, 1, -1, 2, -1][i % 9]}deg; --lift: ${i % 2 ? '18px' : '0px'}`}>
        <Image src={storyImage(story.file)} alt={story.alt} width={320} height={569} widths={[240, 320, 480]} sizes="(max-width: 760px) 60vw, 190px" loading={i < 4 ? 'eager' : 'lazy'} />
        <span class="visually-hidden">Photo by {story.credit}</span>
      </li>
    ))}
  </ul>
  <div class="strip__cta">
    <Pill href={site.instagramUrl}>{site.hero.instagramLabel}</Pill>
  </div>
</section>

<style>
  .strip { padding-block: clamp(28px, 4vw, 44px) 0; }
  .strip__list {
    list-style: none; margin: 0; padding: 0 var(--gutter);
    display: flex; gap: clamp(10px, 1.2vw, 16px);
    overflow-x: auto; scroll-snap-type: x mandatory; scrollbar-width: none;
    -webkit-overflow-scrolling: touch;
  }
  .strip__list::-webkit-scrollbar { display: none; }
  .strip__item {
    flex: 0 0 clamp(150px, 13vw, 190px);
    aspect-ratio: 9 / 16;
    border-radius: 12px; overflow: hidden;
    transform: rotate(var(--rot)) translateY(var(--lift));
    scroll-snap-align: center;
    box-shadow: 0 10px 24px rgba(60, 30, 20, 0.18);
  }
  .strip__item img { width: 100%; height: 100%; object-fit: cover; }
  .strip__cta { text-align: center; margin-top: clamp(36px, 5vw, 56px); color: var(--red-2); }
  @media (min-width: 1360px) { .strip__list { justify-content: center; } }
  @media (prefers-reduced-motion: reduce) { .strip__item { transform: none; } }
</style>
```

Note on colour: the strip never gets `.duo`. The photos stay in full colour by spec.

- [ ] **Step 3: Wire into the page**

In `src/pages/index.astro`, import `Hero` and `StoriesStrip` and replace the `#top` placeholder section with:

```astro
<Hero />
<StoriesStrip />
```

- [ ] **Step 4: Build and test**

```bash
npm run check && npm run build && npm run test:dist && ls dist/_astro | grep -c story
```

Expected: the same tests pass as before plus `external links` still failing on the doc links (Instagram now present). The `ls` count is at least 9 (generated image variants).

- [ ] **Step 5: Commit**

```bash
git add src/components/Hero.astro src/components/StoriesStrip.astro src/pages/index.astro
git commit -m "feat(hero): evergreen headline and the stories strip

- Hero with the headline and the since-2016 intro
- Nine full-colour story photos in a snapping strip, tilted, responsive sizes
- Instagram call to action under the strip

Why: direction D leads with the people in the event.

Co-Authored-By: Claude Fable 5.1 <noreply@anthropic.com>
Claude-Session: https://claude.ai/code/session_01XrYNvFcR2mGBmwudJ9Kt2t"
```

---

### Task 6: Edition block, status pill, polaroid

**Files:**
- Create: `src/components/EditionBlock.astro`, `src/components/StatusPill.astro`, `src/components/Polaroid.astro`
- Modify: `src/pages/index.astro`

**Interfaces:**
- Consumes: `getCollection('editions')`, `render`, `statusPill` from `src/lib/edition-status.ts`, `storyImage`, `Pill.astro`, `site`.
- Produces: `EditionBlock.astro` (no props; picks the edition with the highest `year`), `StatusPill.astro` props `{ status, year, applyUrl? }`, `Polaroid.astro` props `{ src: ImageMetadata; alt: string; width: number; height: number; rotate?: number; duo?: boolean }`.

- [ ] **Step 1: Write `src/components/Polaroid.astro`**

```astro
---
import { Image } from 'astro:assets';
import type { ImageMetadata } from 'astro';

interface Props {
  src: ImageMetadata;
  alt: string;
  width: number;
  height: number;
  rotate?: number;
  duo?: boolean;
}
const { src, alt, width, height, rotate = 0, duo = true } = Astro.props;
---
<figure class="polaroid" style={`--rot: ${rotate}deg`}>
  <div class:list={['polaroid__photo', { duo }]}>
    <Image src={src} alt={alt} width={width} height={height} />
  </div>
</figure>

<style>
  .polaroid { margin: 0; display: inline-block; background: var(--cream); padding: 10px; box-shadow: 0 14px 30px rgba(60, 30, 20, 0.22); transform: rotate(var(--rot)); }
  .polaroid__photo { overflow: hidden; }
  .polaroid__photo img { width: 100%; height: auto; }
  @media (prefers-reduced-motion: reduce) { .polaroid { transform: none; } }
</style>
```

- [ ] **Step 2: Write `src/components/StatusPill.astro`**

```astro
---
import Pill from './Pill.astro';
import { statusPill, type EditionStatus } from '../lib/edition-status';
import { site } from '../lib/site';

interface Props {
  status: EditionStatus;
  year: number;
  applyUrl?: string;
}
const { status, year, applyUrl } = Astro.props;
const pill = statusPill({ status, year, applyUrl, instagramUrl: site.instagramUrl });
---
<Pill href={pill.href} solid={status === 'open'}>{pill.label}</Pill>
```

- [ ] **Step 3: Write `src/components/EditionBlock.astro`**

```astro
---
import { getCollection, render } from 'astro:content';
import StatusPill from './StatusPill.astro';
import Polaroid from './Polaroid.astro';
import { storyImage } from '../lib/stories';

const editions = await getCollection('editions');
const edition = editions.sort((a, b) => b.data.year - a.data.year)[0];
if (!edition) throw new Error('No edition found in src/content/editions');
const { Content } = await render(edition);
const d = edition.data;
---
<section id="edition" class="block block--red edition">
  <div class="container edition__grid">
    <div class="edition__text">
      <p class="kicker">{d.kicker}</p>
      <h2 class="h-display edition__title">{d.title}</h2>
      <p class="edition__dates">{d.dates}</p>
      <div class="edition__body"><Content /></div>
      <p class="edition__cta">{d.callToAction}</p>
      <StatusPill status={d.status} year={d.year} applyUrl={d.applyUrl} />
    </div>
    <div class="edition__photo">
      <Polaroid src={storyImage(d.photo)} alt={d.photoAlt} width={420} height={420} rotate={3} />
    </div>
  </div>
</section>

<style>
  .edition__grid { display: grid; grid-template-columns: minmax(0, 1.1fr) minmax(0, 1fr); gap: clamp(32px, 5vw, 64px); align-items: center; }
  .edition__title { font-size: var(--fs-h2); margin: 14px 0 18px; }
  .edition__dates { font-weight: 600; margin-bottom: 20px; }
  .edition__body :global(a) { color: var(--cream); }
  .edition__cta { font-weight: 600; margin: 8px 0 22px; }
  .edition__photo { justify-self: center; max-width: 440px; width: 100%; }
  @media (max-width: 860px) {
    .edition__grid { grid-template-columns: 1fr; }
    .edition__photo { max-width: 320px; }
  }
</style>
```

- [ ] **Step 4: Wire into the page**

In `src/pages/index.astro`, import `EditionBlock` and replace the `#edition` placeholder with `<EditionBlock />`.

- [ ] **Step 5: Build and test**

```bash
npm run check && npm run build && npm run test:dist
```

Expected: `the status pill reads closed for 2026` now passes. `external links` still fails only on Bruzz, letsmakeamark.org, the two organiser links and mailto.

- [ ] **Step 6: Prove the status switch**

Change `status: closed` to `status: done` in `src/content/editions/2026.md`, build, grep, then revert:

```bash
sed -i '' 's/^status: closed/status: done/' src/content/editions/2026.md && npm run build && grep -c 'Read about the 2026 edition on Instagram' dist/index.html; sed -i '' 's/^status: done/status: closed/' src/content/editions/2026.md && git diff --stat src/content/editions/2026.md
```

Expected: grep prints 1, and the final diff is empty.

- [ ] **Step 7: Commit**

```bash
git add src/components/EditionBlock.astro src/components/StatusPill.astro src/components/Polaroid.astro src/pages/index.astro
git commit -m "feat(edition): red edition block with status pill and duotone polaroid

- Renders the newest edition from the collection with its markdown body
- Status pill derived from one field via statusPill()
- Polaroid component with the duotone treatment

Why: organisers change one word to reopen the call.

Co-Authored-By: Claude Fable 5.1 <noreply@anthropic.com>
Claude-Session: https://claude.ai/code/session_01XrYNvFcR2mGBmwudJ9Kt2t"
```

---

### Task 7: Info for Makers and past organisations

**Files:**
- Create: `src/components/MakersInfo.astro`, `src/components/Organisations.astro`
- Modify: `src/pages/index.astro`

**Interfaces:**
- Consumes: `site.makers`, `site.organisations`, `getCollection('organisations')`.
- Produces: `MakersInfo.astro`, `Organisations.astro`, no props. Each organisation renders as `<li class="org">`.

- [ ] **Step 1: Write `src/components/MakersInfo.astro`**

```astro
---
import { site } from '../lib/site';
const { heading, steps, closing } = site.makers;
---
<section id="makers" class="makers container">
  <h2 class="h-display makers__heading">{heading}</h2>
  <ol class="makers__steps">
    {steps.map((step, i) => (
      <li class="makers__step">
        <span class="h-display h-display--light makers__num" aria-hidden="true">{String(i + 1).padStart(2, '0')}</span>
        <h3 class="h-display makers__title">{step.title}</h3>
        <p>{step.text}</p>
      </li>
    ))}
  </ol>
  <p class="makers__closing">{closing}</p>
</section>

<style>
  .makers { padding-block: var(--section-y); }
  .makers__heading { font-size: var(--fs-h2); color: var(--red-2); margin-bottom: clamp(28px, 4vw, 48px); }
  .makers__steps { list-style: none; margin: 0; padding: 0; display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: clamp(20px, 3vw, 40px); }
  .makers__step { border-top: 1px solid rgba(195, 63, 64, 0.35); padding-top: 18px; }
  .makers__num { display: block; font-size: var(--fs-num); color: var(--red-2); }
  .makers__title { font-size: var(--fs-h3); color: var(--red-2); margin: 12px 0 10px; }
  .makers__closing { margin-top: 28px; font-weight: 600; color: var(--red-2); }
  @media (max-width: 860px) { .makers__steps { grid-template-columns: 1fr; } }
</style>
```

- [ ] **Step 2: Write `src/components/Organisations.astro`**

```astro
---
import { getCollection } from 'astro:content';
import { site } from '../lib/site';
const organisations = (await getCollection('organisations')).sort((a, b) => a.data.name.localeCompare(b.data.name, 'en', { sensitivity: 'base' }));
---
<section id="organisations" class="block block--red orgs">
  <div class="container">
    <h2 class="h-display orgs__heading">{site.organisations.heading}</h2>
    <p class="orgs__intro">{site.organisations.intro}</p>
    <ul class="orgs__list">
      {organisations.map((org) => (
        <li class="org">{org.data.url ? <a href={org.data.url} rel="noopener" target="_blank">{org.data.name}</a> : org.data.name}</li>
      ))}
    </ul>
  </div>
</section>

<style>
  .orgs__heading { font-size: var(--fs-h2); max-width: 12ch; }
  .orgs__intro { max-width: 600px; margin: 20px 0 32px; }
  .orgs__list { list-style: none; margin: 0; padding: 0; display: flex; flex-wrap: wrap; gap: 10px; }
  .org { border: 1.5px solid var(--cream); border-radius: var(--radius-pill); padding: 8px 14px; font-size: 14px; color: var(--cream-light); }
  .org a { color: inherit; text-decoration: none; }
  .org a:hover, .org a:focus-visible { text-decoration: underline; }
</style>
```

- [ ] **Step 3: Wire into the page**

Import both and replace the `#makers` and `#organisations` placeholders with `<MakersInfo />` and `<Organisations />`.

- [ ] **Step 4: Build and test**

```bash
npm run check && npm run build && npm run test:dist
```

Expected: `55 organisations are listed` passes.

- [ ] **Step 5: Commit**

```bash
git add src/components/MakersInfo.astro src/components/Organisations.astro src/pages/index.astro
git commit -m "feat(sections): info for makers and the past organisations block

- Three numbered steps for makers from site.json
- 55 organisations as cream pills in a red block, sorted by name

Why: the two sections every maker and organisation reads first.

Co-Authored-By: Claude Fable 5.1 <noreply@anthropic.com>
Claude-Session: https://claude.ai/code/session_01XrYNvFcR2mGBmwudJ9Kt2t"
```

---

### Task 8: FAQ and About

**Files:**
- Create: `src/components/Faq.astro`, `src/components/About.astro`
- Modify: `src/pages/index.astro`

**Interfaces:**
- Consumes: `getCollection('faq')`, `render`, `site.about`, `site.email`, `Pill.astro`, `src/assets/logo-brussels.png`.
- Produces: `Faq.astro`, `About.astro`, no props.

- [ ] **Step 1: Write `src/components/Faq.astro`**

```astro
---
import { getCollection, render } from 'astro:content';
import { site } from '../lib/site';
const entries = (await getCollection('faq')).sort((a, b) => a.data.order - b.data.order);
const items = await Promise.all(entries.map(async (entry) => ({ entry, Content: (await render(entry)).Content })));
---
<section id="faq" class="faq container">
  <h2 class="h-display faq__heading">{site.faq.heading}</h2>
  <div class="faq__list">
    {items.map(({ entry, Content }, i) => (
      <details class="faq__item">
        <summary class="faq__summary">
          <span class="h-display h-display--light faq__num" aria-hidden="true">{String(i + 1).padStart(2, '0')}</span>
          <span class="faq__question">{entry.data.question}</span>
          <span class="faq__icon" aria-hidden="true"></span>
        </summary>
        <div class="faq__answer"><Content /></div>
      </details>
    ))}
  </div>
</section>

<style>
  .faq { padding-block: var(--section-y); }
  .faq__heading { font-size: var(--fs-h2); color: var(--red-2); margin-bottom: clamp(20px, 3vw, 36px); }
  .faq__item { border-top: 1px solid rgba(195, 63, 64, 0.35); }
  .faq__item:last-child { border-bottom: 1px solid rgba(195, 63, 64, 0.35); }
  .faq__summary { display: grid; grid-template-columns: 64px minmax(0, 1fr) 32px; align-items: center; gap: 16px; padding: 18px 0; cursor: pointer; list-style: none; }
  .faq__summary::-webkit-details-marker { display: none; }
  .faq__num { font-size: 40px; color: var(--red-2); }
  .faq__question { font-family: var(--font-display); font-weight: 600; text-transform: uppercase; font-size: clamp(20px, 2.2vw, 28px); line-height: 1; color: var(--red-2); }
  .faq__icon { width: 28px; height: 28px; border: 1.5px solid var(--red-2); border-radius: 50%; position: relative; }
  .faq__icon::before, .faq__icon::after { content: ""; position: absolute; background: var(--red-2); left: 50%; top: 50%; transform: translate(-50%, -50%); }
  .faq__icon::before { width: 12px; height: 1.5px; }
  .faq__icon::after { width: 1.5px; height: 12px; }
  .faq__item[open] .faq__icon::after { display: none; }
  .faq__answer { padding: 0 0 22px 80px; max-width: 680px; }
  .faq__answer :global(a) { color: var(--red-2); }
  @media (max-width: 760px) {
    .faq__summary { grid-template-columns: 40px minmax(0, 1fr) 28px; }
    .faq__num { font-size: 28px; }
    .faq__answer { padding-left: 56px; }
  }
</style>
```

- [ ] **Step 2: Write `src/components/About.astro`**

```astro
---
import { Image } from 'astro:assets';
import { site } from '../lib/site';
import Pill from './Pill.astro';
import logo from '../assets/logo-brussels.png';
const { heading, intro, organisers, contactLabel } = site.about;
---
<section id="about" class="about container">
  <div class="about__text">
    <h2 class="h-display about__heading">{heading}</h2>
    <p>{intro}</p>
    <ul class="about__people">
      {organisers.map((person) => (
        <li>
          <a href={person.url} rel="noopener" target="_blank">{person.name}</a>
          {" "}<span>{person.role}</span>
        </li>
      ))}
    </ul>
    <p class="about__contact"><Pill href={`mailto:${site.email}`}>{contactLabel}</Pill></p>
  </div>
  <div class="about__logo">
    <Image src={logo} alt="Make a Mark Brussels, hand-drawn logo" width={420} height={407} />
  </div>
</section>

<style>
  .about { display: grid; grid-template-columns: minmax(0, 1.2fr) minmax(0, 1fr); gap: clamp(32px, 5vw, 64px); align-items: center; padding-block: var(--section-y); }
  .about__heading { font-size: var(--fs-h2); color: var(--red-2); margin-bottom: 20px; }
  .about__people { list-style: none; margin: 0 0 24px; padding: 0; }
  .about__people li { margin-bottom: 12px; }
  .about__people a { font-weight: 600; color: var(--red-2); }
  .about__contact { color: var(--red-2); }
  .about__logo { justify-self: center; max-width: 420px; width: 100%; mix-blend-mode: multiply; }
  @media (max-width: 860px) { .about { grid-template-columns: 1fr; } .about__logo { max-width: 280px; } }
</style>
```

The `{" "}` between the link and the role span is required: Astro 7 strips whitespace between adjacent inline elements.

- [ ] **Step 3: Wire into the page**

Import both and replace the `#faq` and `#about` placeholders with `<Faq />` and `<About />`. The finished `src/pages/index.astro`:

```astro
---
import Base from '../layouts/Base.astro';
import Nav from '../components/Nav.astro';
import Hero from '../components/Hero.astro';
import StoriesStrip from '../components/StoriesStrip.astro';
import EditionBlock from '../components/EditionBlock.astro';
import MakersInfo from '../components/MakersInfo.astro';
import Organisations from '../components/Organisations.astro';
import Faq from '../components/Faq.astro';
import About from '../components/About.astro';
import Footer from '../components/Footer.astro';
import { site } from '../lib/site';
---
<Base title={site.meta.title} description={site.meta.description}>
  <Nav />
  <main id="main">
    <Hero />
    <StoriesStrip />
    <EditionBlock />
    <MakersInfo />
    <Organisations />
    <Faq />
    <About />
  </main>
  <Footer />
</Base>
```

- [ ] **Step 4: Build and run every test**

```bash
npm run check && npm run build && npm test && npm run test:dist
```

Expected: all green, including `external links` and `five faq items`.

- [ ] **Step 5: Commit**

```bash
git add src/components/Faq.astro src/components/About.astro src/pages/index.astro
git commit -m "feat(sections): faq accordion and about the organisers

- FAQ as native details elements, numbered, rendered from markdown
- About with organiser links, contact pill and the hand-drawn logo
- index.astro composes all sections

Why: completes the page content from the doc.

Co-Authored-By: Claude Fable 5.1 <noreply@anthropic.com>
Claude-Session: https://claude.ai/code/session_01XrYNvFcR2mGBmwudJ9Kt2t"
```

---

### Task 9: Open Graph image, link checker, README

**Files:**
- Create: `scripts/og.html`, `scripts/og.cjs`, `scripts/check-links.mjs`, `public/og.png`, `README.md`

**Interfaces:**
- Produces: `public/og.png` 1200 by 630; `npm run links` script.

- [ ] **Step 1: Write `scripts/og.html`**

```html
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<link rel="stylesheet" href="https://fonts.bunny.net/css?family=big-shoulders-display:600|plus-jakarta-sans:400">
<style>
  body { margin: 0; width: 1200px; height: 630px; background: #C33F40; color: #F5E2CA; font-family: 'Plus Jakarta Sans', system-ui, sans-serif; display: flex; flex-direction: column; justify-content: center; padding: 72px; box-sizing: border-box; }
  .kicker { font-size: 20px; letter-spacing: .14em; text-transform: uppercase; font-weight: 600; }
  h1 { font-family: 'Big Shoulders Display', Impact, sans-serif; font-weight: 600; text-transform: uppercase; font-size: 150px; line-height: .9; margin: 18px 0 28px; }
  p { font-size: 28px; margin: 0; max-width: 900px; }
</style>
</head>
<body>
  <div class="kicker">Make a Mark Brussels</div>
  <h1>Hundreds of creatives.<br>One day. Brussels.</h1>
  <p>A make-a-thon where creatives give social-profits a day of design work. Eighth edition: Make a Mark for the Planet, 20 and 21 March 2026.</p>
</body>
</html>
```

- [ ] **Step 2: Write `scripts/og.cjs`**

```js
const { chromium } = require('playwright');
const path = require('node:path');
(async () => {
  const browser = await chromium.launch();
  const page = await browser.newPage({ viewport: { width: 1200, height: 630 } });
  await page.goto('file://' + path.resolve(__dirname, 'og.html'));
  await page.waitForLoadState('networkidle');
  await page.evaluate(() => document.fonts.ready);
  await page.screenshot({ path: path.resolve(__dirname, '../public/og.png') });
  await browser.close();
  console.log('public/og.png written');
})();
```

Run:

```bash
NODE_PATH="$(npm root -g)" node scripts/og.cjs && sips -g pixelWidth -g pixelHeight public/og.png
```

Expected: 1200 by 630.

- [ ] **Step 3: Write `scripts/check-links.mjs`**

```js
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
```

Add to `package.json` scripts: `"links": "node scripts/check-links.mjs"`.

Run after a build:

```bash
npm run build && npm run links
```

Expected: every line `ok`. Instagram may answer 429 or redirect to a login page; treat a 200 or 3xx as ok, and if Instagram returns 429 note it in the task report rather than failing the task.

- [ ] **Step 4: Write `README.md`**

```md
# Make a Mark Brussels

The website at https://letsmakeamark.brussels, built with Astro and deployed on Netlify.

## Edit the content

Everything visible on the page lives in these files:

- `src/content/editions/2026.md`: the current edition. Change `status` to `open`, `closed`, `selected` or `done`. Set `applyUrl` when applications are open. Add `2027.md` for the next edition; the site shows the newest year.
- `src/content/data/organisations.json`: the list of past organisations.
- `src/content/faq/*.md`: one file per question, `order` sets the sequence.
- `src/content/data/site.json`: headline, intro, makers steps, organisers, nav and footer labels.
- `src/content/data/stories.json` and `src/assets/stories/`: the photo strip. Add a portrait photo to the folder and one line to the JSON.

Push to `main` and Netlify rebuilds the site.

## Develop

    npm install
    npm run dev        # http://localhost:4321
    npm run build      # writes dist/
    npm run check      # types
    npm test           # logic tests
    npm run test:dist  # asserts on the built html
    npm run links      # checks every external link in the build

Fonts come from bunny.net through Astro's Fonts API and are served with the site.
```

- [ ] **Step 5: Commit**

```bash
git add scripts/og.html scripts/og.cjs scripts/check-links.mjs public/og.png package.json README.md
git commit -m "chore(site): open graph image, link checker and readme

- og.png rendered from scripts/og.html with Playwright
- check-links.mjs verifies every external href in dist
- README with the content editing guide

Why: sharing on social needs an image, and the organisers need to know which file to edit.

Co-Authored-By: Claude Fable 5.1 <noreply@anthropic.com>
Claude-Session: https://claude.ai/code/session_01XrYNvFcR2mGBmwudJ9Kt2t"
```

---

### Task 10: Visual verification pass at 1440 and 390

**Files:**
- Create: `scripts/screenshot.cjs`
- Modify: any component whose rendering the screenshots show as wrong

**Interfaces:**
- Consumes: `npm run preview` on port 4321.

- [ ] **Step 1: Write `scripts/screenshot.cjs`**

```js
const { chromium } = require('playwright');
const path = require('node:path');
const out = process.env.OUT_DIR || path.resolve(__dirname, '../.screenshots');
require('node:fs').mkdirSync(out, { recursive: true });
(async () => {
  const browser = await chromium.launch();
  for (const [name, width] of [['desktop', 1440], ['mobile', 390]]) {
    const page = await browser.newPage({ viewport: { width, height: 900 } });
    await page.goto('http://localhost:4321/');
    await page.waitForLoadState('networkidle');
    const overflow = await page.evaluate(() => document.documentElement.scrollWidth - document.documentElement.clientWidth);
    await page.screenshot({ path: path.join(out, `${name}.png`), fullPage: true });
    console.log(`${name}: horizontal overflow ${overflow}px, saved ${name}.png`);
    await page.close();
  }
  await browser.close();
})();
```

Add `.screenshots/` to `.gitignore`.

- [ ] **Step 2: Build, preview, screenshot**

```bash
npm run build && (npm run preview -- --port 4321 & echo $! > .preview.pid) && sleep 3 && NODE_PATH="$(npm root -g)" node scripts/screenshot.cjs; kill $(cat .preview.pid); rm .preview.pid
```

Expected: both lines report `horizontal overflow 0px`.

- [ ] **Step 3: Inspect both screenshots once and fix in one batch**

Open `.screenshots/desktop.png` and `.screenshots/mobile.png`. Compare against the direction D mockup in `.lavish/creative-directions.html` (section 01, card D). Check, in this order, and fix everything found in one pass:

1. Section order: nav, hero, strip, red edition block, makers, red organisations block, faq, about, red footer.
2. Headline uses the display font (tall condensed caps). If it shows a fallback, the Fonts API did not load; check the build log.
3. Strip photos are colour; edition polaroid is duotone.
4. No text in cream on red below 17px except kickers and pills.
5. Mobile: strip scrolls horizontally, the nav toggle shows the links, the edition block stacks, organisations pills wrap.
6. No element clipped at the page edge.

Re-run Step 2 once after the fixes. Stop after that second round.

- [ ] **Step 4: Full test run**

```bash
npm run check && npm run build && npm test && npm run test:dist
```

Expected: all green.

- [ ] **Step 5: Commit**

```bash
git add scripts/screenshot.cjs .gitignore src
git commit -m "fix(ui): visual pass at 1440 and 390 against the direction D mockup

- Screenshot script reporting horizontal overflow
- Layout fixes found in the pass

Why: the spec's done criteria include a visual match at both widths.

Co-Authored-By: Claude Fable 5.1 <noreply@anthropic.com>
Claude-Session: https://claude.ai/code/session_01XrYNvFcR2mGBmwudJ9Kt2t"
```

---

### Task 11: Netlify deploy

**Files:**
- None new; uses `netlify.toml` from Task 1.

This task needs Frederik at the keyboard for the login and the GitHub remote. The executor prepares and stops.

- [ ] **Step 1: Confirm the repo has a remote**

```bash
git remote -v
```

If empty, ask Frederik to create the GitHub repository and run `git remote add origin <url>` and `git push -u origin main`. Do not create the repository on his behalf.

- [ ] **Step 2: Link and deploy**

Frederik runs, in this folder:

```bash
netlify login
netlify init
```

Choose "Create & configure a new site", accept the detected build command `npm run build` and publish directory `dist`. Netlify reads `NODE_VERSION` from `netlify.toml`.

- [ ] **Step 3: Verify the deploy preview**

Open the deploy URL Netlify prints. Check the fonts load, the og image URL returns 200 at `/og.png`, and `/sitemap-index.xml` exists.

- [ ] **Step 4: Custom domain**

In the Netlify site settings, add `letsmakeamark.brussels` as the primary domain and follow Netlify's DNS instructions at the registrar. HTTPS is automatic once DNS resolves.

No commit in this task.

---

## Self-review against the spec

- Page structure, nine sections with anchors: Tasks 4 to 8. The nav has four links; the spec's "About" section id is `about`. Covered.
- Edition status table: Task 3 `statusPill()` with tests, Task 6 proves the switch. Covered.
- Content model: Task 3. `site.json` and `stories.json` validated by zod; the collections use loaders. The spec mentioned `photo` on the edition as a path; the plan uses a file name resolved through `storyImage()`, same intent.
- Visual system tokens: Task 2. Cream-light for body on red: `.block--red` sets `color: var(--cream-light)` and headings use cream. Covered.
- Fonts via bunny provider: Task 1. Covered.
- Images through `astro:assets`, lazy below the fold: Tasks 5, 6, 8. Covered.
- Sitemap, robots, og image: Tasks 1, 2, 9. Covered.
- Netlify: Tasks 1 and 11. Covered.
- Accessibility: skip link, landmarks, one h1, reduced motion: Tasks 2, 4, 5. `aria-current` explicitly out of scope per spec.
- Verification criteria: build and check (every task), visual match at 1440 and 390 (Task 10), links resolve (Task 9 checker plus Task 4 test), status switch (Task 6), Netlify preview (Task 11). Covered.
- Number of organisations: the doc's list resolves to 55 once "Kind, Ouder en Kanker" is read as one name. The spec says 56; the plan uses 55 and the spec's count should be read as 55.
