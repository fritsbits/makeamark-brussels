# Make a Mark Brussels

Laravel version of the Make a Mark Brussels website. The app lives at this
repository's root. Its content, section order, design, photos, and production
metadata come from the original `site` repository at commit
`74b55fa9c11bed88b4addc0c0005fc02aad65827`.

The original Astro checkout at `/Users/frederikvincx/Documents/makeamark/site`
is unchanged. See the [migration record](docs/wiki/discovery/20-laravel-foundation.md)
and [source capture](docs/raw/2026-10-18-astro-site.md).

## Local setup

Requires PHP 8.3+, Composer, and Node 22.12+.
The stack is Laravel 13, Livewire 4, Flux Pro 2, Tailwind 4, and Vite.
Alpine is included with Livewire. SQLite is the default database.

Configure Composer authentication for `composer.fluxui.dev` using your Flux
account email and license key before installing. Use global Composer
authentication or a local, ignored `auth.json`; never commit credentials.
See [Flux installation](https://fluxui.dev/docs/installation).

```sh
composer install
cp .env.example .env
php artisan key:generate
touch database/database.sqlite
php artisan migrate
npm ci
npm run build
```

With Herd:

```sh
herd link makeamark-southern-ocean --secure
npm run dev
```

Open https://makeamark-southern-ocean.test. Without Herd, use
`composer run dev` and open http://localhost:8000. Set `APP_URL` in `.env`
to the local address you use. Production must set its own `APP_KEY`,
`APP_URL`, and `APP_DEBUG=false`, and serve the `public` directory.

## Editing content

Use the [tone of voice guide](docs/wiki/strategy/10-tone-of-voice.md) as the draft writing reference. It includes source evidence, examples, and pending owner choices. The [product knowledge index](docs/wiki/index.md) tracks the Cascade scaffold.

- `resources/content/site.json`: shared copy, links, SEO, navigation, and organisers.
- `resources/content/editions/*.json`: edition metadata and Markdown body.
  The highest year appears on the homepage. Status is authored explicitly:
  `open`, `closed`, `selected`, or `done`. An open edition needs `applyUrl`.
- `resources/content/faq/*.json`: ordered questions and Markdown answers.
- `resources/content/organisations.json`: the organisation list.
- `resources/content/stories.json`: photo order, alt text, and photographer credits.
- `public/images`: responsive WebP photos and logo.
- `public/fonts`: the original self-hosted fonts.
- `public/og-2027.png`: owner-approved 2027 social sharing artwork, referenced by the public layout with a fresh image URL.

When adding an edition, update its content, the shared meta description,
About intro, and social sharing artwork as needed. Keep canonical URLs and
sitemaps on the production domain in `site.json`, including in local previews.
The edition hub and the maker and organisation information pages are in place.
Audience-specific application routes are ready for the approved field review
and data-handling decisions. They stay in a preparation state until the edition
status is changed to `open`.

The current edition status may be `preparing`, `open`, `closed`, `selected`, or
`done`. `preparing` keeps application actions unavailable; `open` requires a
valid application destination before showing an active action. The owner still
needs to map migrated statuses to the public phases and approve application
fields, consent wording, and retention.

Follow [AGENTS.md](AGENTS.md) for component and page-building conventions.

## Build and test workflow

### Photo treatments

The saved site treatment lives in `resources/content/photo-treatments/riso.json`.
Only the homepage group photo currently uses it. Add `"photoTreatment": "riso"`
to an edition or `"treatment": "riso"` to a story to opt that photo in. Run
`npm run photos:build`, or the normal `npm run build`, to regenerate the
responsive images. See [selected photo treatments](docs/wiki/build/11-site-photo-treatments.md).

The owner selected an expanding reveal of the original homepage photo in a
larger frame. The photo treatment record distinguishes the agent-applied
600px and motion defaults from that choice and records passing running-photo
checks separately from two earlier PHP application-state assertion failures.

GitHub Actions runs the Laravel test suite and Vite production build for pushes
and pull requests. Add the Flux Composer credentials as the `COMPOSER_AUTH`
repository secret so CI can install `livewire/flux-pro`.

```sh
php artisan test
npm run build
```

The selected distilled homepage is served at `/`, with related information at
`/makers` and `/organisations`. Its process section is omitted; process and
commitment details live on the audience pages. The previous homepage is
preserved at `/tools/messaging/original`, alongside the selected version in
`/tools/messaging/compare`. Tools are local/testing only and excluded from indexing.
[Selection record](docs/wiki/build/13-messaging-review.md).

The browser helper uses global Playwright and Herd's HTTPS URL. It checks
mobile navigation, six photo instances, keyboard colour reveal, audience/application
routes and overflow at 1440px and 390px. Screenshots go to
`/tmp/makeamark-laravel-screenshots`. Override `BASE_URL` and `OUT_DIR` as needed.
`scripts/messaging-preview-check.cjs` checks the preserved comparison and its
desktop/mobile viewport controls. `scripts/photo-treatment-check.cjs` covers
the six photo instances, reduced motion, touch and the no-JavaScript fallback.
`scripts/homepage-carousel-check.cjs` checks arrows, keyboard navigation,
native scrolling, a mobile touch swipe and the no-JavaScript gallery at five
widths. Set `SCREENSHOTS=1` for desktop/mobile captures. The edition's `gallery`
array in `resources/content/editions/2027.json` contains the four duplicate
placeholders to replace with new files and alt text later.
