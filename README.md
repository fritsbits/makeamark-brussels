# Make a Mark Brussels

The website at https://letsmakeamark.brussels, built with Astro and deployed on Netlify.

## Edit the content

Everything visible on the page lives in these files:

- `src/content/editions/2026.md`: the current edition. Change `status` to `open`, `closed`, `selected` or `done`. Set `applyUrl` when applications are open. Add `2027.md` for the next edition; the site shows the newest year.
- `src/content/data/organisations.json`: the list of past organisations.
- `src/content/faq/*.md`: one file per question, `order` sets the sequence.
- `src/content/data/site.json`: headline, intro, makers steps, organisers, nav and footer labels.
- `src/content/data/stories.json` and `src/assets/stories/`: the photo strip. Add a portrait photo to the folder and one line to the JSON.

A new edition touches four things: `src/content/editions/<year>.md`, the meta description in `src/content/data/site.json`, the About intro in the same file, and `scripts/og.html` (regenerate `public/og.png` afterwards).

Push to `main` and Netlify rebuilds the site.

## Develop

    npm install
    npm run dev        # http://localhost:4321
    npm run build      # writes dist/
    npm run check      # types
    npm test           # logic tests
    npm run test:dist  # asserts on the built html
    npm run links      # checks every external link in the build

`npm run test:dist` and `npm run links` read `dist/`, so run `npm run build` first.

Regenerate the Open Graph image with `NODE_PATH="$(npm root -g)" node scripts/og.cjs`, and take screenshots with `NODE_PATH="$(npm root -g)" node scripts/screenshot.cjs` against a running `npm run preview`. Both need Playwright installed globally.

Fonts come from bunny.net through Astro's Fonts API and are served with the site.
