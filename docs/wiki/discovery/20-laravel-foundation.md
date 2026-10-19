---
title: Laravel foundation
tags: [setup, laravel, implementation]
sources:
  - "User request, 2026-10-18: turn this site into a Laravel project"
  - "https://app.notion.com/p/334d3ecc475c81719bdcc15d82bd5b23"
  - "https://laravel.com/docs/13.x/installation"
  - "https://fluxui.dev/docs/installation"
phase: Discovery
updated: 2026-10-18
---

# Laravel foundation

## Scope and evidence

The owner requested a Laravel project and supplied the [Laravel setup
playbook](https://app.notion.com/p/334d3ecc475c81719bdcc15d82bd5b23).
It calls for a clean install without a starter kit, Tailwind, Livewire,
Flux Pro, and Alpine bundled through Livewire. It requires an approved sitemap
in Notion before product pages and approved wireframes before custom styling.

The owner then identified the existing Astro one-pager in `/site`. It lives
in the main checkout's separate repository and was absent from this worktree.
The [source capture](../../raw/2026-10-18-astro-site.md) records its commit,
content, and behavior contracts.

Implementation decision: put the Laravel application at this worktree's root
and port the existing one-pager without redesigning it. Preserve the original
Astro checkout, file-based content editing, and Cascade documents. The existing
page supplies the migration structure and styling; no new pages are introduced.
This work does not close Discovery. New product pages still require the
playbook's sitemap and wireframe approvals.

## Implementation plan

- [x] Install the current stable Laravel release with its default SQLite
  database and PHPUnit, without a starter kit or optional product packages.
- [x] Install Flux Pro and Livewire using existing local Composer credentials.
  Configure Tailwind's Flux imports and a reusable application layout with
  Flux and Livewire assets, following the [installation
  instructions](https://fluxui.dev/docs/installation).
- [x] Port the original page to Blade sections and Flux components. Preserve
  authored edition status, sorted organisations and FAQs, story order,
  accessible disclosures, mobile navigation, metadata, and sitemap URLs.
- [x] Record component conventions in `AGENTS.md`; keep `CLAUDE.md` pointing
  to those shared instructions. Add local setup instructions to `README.md`.
- [x] Verify migrations, PHP tests, asset compilation, component rendering,
  and the running application. Record results here and in the change log.

## Deferred work

New product scope still needs the Cascade phase gates and an approved sitemap.
The original one-pager's existing design is carried over. Leaflet and the
Typography plugin will only be installed if the product needs them.

## Verification

Verified on 2026-10-18 in the running application at
`https://makeamark-southern-ocean.test`:

- Installed versions from the lockfiles: Laravel 13.37.0, Livewire 4.4.7,
  Flux Pro 2.20.1, Tailwind 4, and Vite 8. PHP 8.4.25 is the local runtime.
- `composer validate --strict`, `vendor/bin/pint --test`, and
  `php artisan test --compact` passed. The test suite reports 9 tests and
  31 assertions, covering migrated content, metadata, anchors, counts, story
  order, edition actions, and sitemap responses.
- `npm run build` passed. The fonts remain served from `public/fonts`;
  Vite reports their absolute URLs as runtime-resolved references.
- All three default SQLite migrations ran; `php artisan migrate:status`
  confirms them.
- `scripts/screenshot.cjs` passed at 1440px and 390px. It verified loaded
  images, no browser errors, native FAQ toggling, keyboard photo scrolling,
  mobile menu opening and closing, and zero horizontal page overflow.
  Screenshots were inspected at `/tmp/makeamark-laravel-screenshots/`.
- A browser comparison with the original Astro build at 390px found the same
  section top positions and heights across every section. Flux button labels
  wrap like the original pills; the source fonts and styling are retained.
- Independent migration/security review found no material issues. Locked
  Composer and npm dependency audits reported no advisories. The original
  Astro checkout remains clean.
- `git check-ignore` confirmed that `.env`, Composer `auth.json`, SQLite,
  installed dependencies, and built assets are excluded from version control.

Known authoring difference: JSON replaces Astro's content collections and
frontmatter, while body fields retain Markdown. The original Zod content
schemas were not ported; PHP tests verify the current migrated content.
No hosting deployment or new product pages were part of this conversion.
