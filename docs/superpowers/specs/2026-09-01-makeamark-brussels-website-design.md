# Make a Mark Brussels website, design spec

Date: 2026-09-01
Status: draft for review
Source of picks: `.lavish/creative-directions.html`, decisions queued by Frederik on 2026-09-01.

## What we are building

A one-page static site for Make a Mark Brussels at https://letsmakeamark.brussels/, replacing the Google Doc that has been the site for seven years. Built with Astro, styled after the international site letsmakeamark.org, fonts served by fonts.bunny.net, deployed on Netlify. Content lives in the repository as markdown and JSON.

The chosen direction is D, the stories wall: the site sells the event through the people in it. A strip of portrait photos in the Instagram story format leads the page. The current edition sits in a red block right below it. Type, colour and layout stay inside the international family.

## Decisions taken

| Question | Decision |
|---|---|
| Creative direction | D, stories wall |
| Structure | One long page, nav links scroll to anchors |
| Hero | Evergreen headline first, edition block second |
| Photos | Duotone inside red blocks and polaroids, full colour in the stories strip |
| Type | Big Shoulders Display for display, Plus Jakarta Sans for body |
| Languages | English only at launch. Every string lives in content files so French and Dutch can be added as translated files |
| Editing | Files in the repo. No CMS. Netlify builds on push |
| Domain | letsmakeamark.brussels |
| Logo | Use the hand-drawn PNG as is, no redraw |
| First edition | 2016, to be confirmed by the organisers. The site says "since 2016" |

Deferred, not in this build: better photos (Frederik asks the co-organisers), the application form, a section for organisations that want to apply, an editions archive, translations.

## Page structure

One route, `/`. Sections in order, each with an id used by the nav:

1. **Nav.** Wordmark "Make a Mark Brussels" left, links right: Makers, Organisations, FAQ, About. Burger on mobile. Sticky.
2. **Hero** (`#top`). Headline "Hundreds of creatives. One day. Brussels." One paragraph: since 2016 makers, designers and developers have given 56 social-profits a day of their talent. No button; the stories strip is the call to look.
3. **Stories strip.** Seven to nine portrait photos, 9:16, full colour, slight alternating rotation, horizontal scroll on mobile, fits the container on desktop. Below it a pill button "See their work on Instagram" linking to instagram.com/lets_makeamarkbxl.
4. **Edition block** (`#edition`). Red rounded block. Kicker "Special edition 2026". Heading "Make a Mark for the Planet". Dates, the Klimaatzaak paragraph, the "can we count on you" line. Status pill (see below). One duotone polaroid on the right.
5. **Info for Makers** (`#makers`). Cream. Heading plus three numbered cards, 01 Friday evening, 02 All day Saturday, 03 Selected in advance, using the copy from the doc. Last line: "Please make sure you can make it."
6. **Past organisations** (`#organisations`). Red block. Heading "56 social-profits made their mark". The full list as an inline, comma-free flow of names, each a small cream pill. The "hundreds of creatives have made social-profits look fabulous" sentence as intro.
7. **FAQ** (`#faq`). Cream. Five questions as native `details` elements styled like the international accordion, numbered 01 to 05. The Google Doc question stays, answered: "Not any more. It was, for seven years." The Bruzz and global-site links stay.
8. **About the organisers** (`#about`). Cream, two columns on desktop. Gwen Dubois and Piet Lambrecht with their links, the contact email as a pill button, the hand-drawn logo at the side.
9. **Footer.** Red. Wordmark, the four nav links, Instagram, email, "Part of the global Make a Mark movement" linking to letsmakeamark.org.

Mobile stacks everything in the same order. The stories strip becomes a horizontal scroller with snap points.

## Edition status

The edition file carries one `status` field. Everything about the call follows from it.

| status | Pill text | Pill link |
|---|---|---|
| `open` | Apply as a Maker | `applyUrl` from the edition file |
| `closed` | Applications are closed for 2026 | none, rendered as a label |
| `selected` | Makers have been selected, see you in March | none |
| `done` | Read about the 2026 edition on Instagram | Instagram |

For 2026 the status is `closed`. The organisers change one word to reopen the call next year, and add a new edition file to change the year.

## Content model

Astro content collections under `src/content/`, typed with `zod` schemas.

- `editions/2026.md`. Frontmatter: `year`, `title` ("Make a Mark for the Planet"), `kicker` ("Special edition 2026"), `dates` (human string), `startDate`, `endDate`, `status`, `applyUrl` (optional), `case` (name, url), `photo` (path). Body: the edition paragraphs. The site renders the edition with the highest year.
- `organisations.json`. Array of `{ name, url? }`. 56 entries from the doc.
- `faq.json`. Array of `{ question, answer }` with markdown allowed in answers.
- `site.json`. `name`, `domain`, `instagram`, `email`, `firstEditionYear`, `organisers: [{ name, role, url }]`, `hero: { headline, intro }`, nav labels, footer strings.
- `stories/`. The photo files plus `stories.json` with `{ file, credit, alt }` per photo. Launch with the nine stories from the doc as placeholders; the strip reads the JSON, so swapping photos is a file change.

Language readiness: `site.json`, `faq.json` and the edition body hold every visible string. A French version later is `site.fr.json` and `editions/2026.fr.md`, with Astro's i18n routing switched on. No component holds copy.

## Visual system

Tokens, as CSS custom properties in one `tokens.css`:

- Colours: cream `#F5E2CA` page, red `#C33F40` blocks, red-2 `#D14B4C` headings on cream, ink `#1E1A16` for body text and links on cream, cream for headings and pills on red, cream-light `#FFF6EA` for body text on red. Photo tint `rgba(121,26,27,.3)`.
- Type: Big Shoulders Display 600 for all headings, uppercase, letter-spacing 0.01em, line-height 0.9 to 0.95. Weight 300 for the big numbers on cards. Plus Jakarta Sans 400 for body, 600 uppercase with 0.12em tracking for nav, pills and kickers. Loaded with one `link` to `https://fonts.bunny.net/css?family=big-shoulders-display:300,600|plus-jakarta-sans:400,600` plus a preconnect. Fallback stack: Impact and system-ui.
- Scale: hero headline clamp 56px to 118px, section headings clamp 40px to 78px, body 17px, small 13px.
- Shape: blocks radius 18px with 24px outer margin, cards 10px, pills fully round, polaroid 8px cream padding with a soft brown drop shadow.
- Spacing: sections 96px vertical on desktop, 56px on mobile. Container max 1360px with 40px gutters.

Photo rules, one class each: `.duo` applies grayscale and the red multiply layer; the stories strip never uses it. No other filters.

Components, one Astro file each under `src/components/`: `Nav`, `Hero`, `StoriesStrip`, `EditionBlock`, `StatusPill`, `MakersInfo`, `Organisations`, `Faq`, `About`, `Footer`, `Pill`, `Polaroid`. One layout, `Base.astro`, holding head, fonts, tokens and skip link.

## Technical setup

- Astro 5, static output, TypeScript strict, no UI framework. Client JavaScript only for the mobile nav toggle; the FAQ uses `details` and needs none.
- Images through `astro:assets` with `Image` components, widths generated for the strip and polaroids, `loading="lazy"` below the fold, `alt` from `stories.json`.
- `@astrojs/sitemap` and a hand-written `robots.txt`. Meta title, description, Open Graph image (a rendered 1200 by 630 card in the edition colours, static file).
- `netlify.toml`: build `npm run build`, publish `dist`, Node 22. Domain and HTTPS configured in Netlify's UI, outside the repo.
- Package scripts: `dev`, `build`, `preview`, `check` (astro check).

## Accessibility and performance

- Skip link, landmarks, one `h1`, headings in order, nav as `nav` with `aria-current` when a section is in view is out of scope; plain anchors are enough.
- Colour contrast, measured: cream `#F5E2CA` on red `#C33F40` is 4.05, enough for headings and pills (large text needs 3.0) but under the 4.5 body text needs. Body copy inside red blocks therefore uses a lighter cream `#FFF6EA` (4.78 on red). Red-2 `#D14B4C` on cream is 3.45, so it is used for headings only; links and body on cream use ink. Small uppercase labels on red use weight 600.
- `prefers-reduced-motion` disables the rotation transitions on the strip.
- Target: no layout shift from fonts (use `font-display: swap` from bunny plus size-adjusted fallbacks), total page under 1 MB with the nine placeholder photos.

## Verification

Done means all of the following hold:

- `npm run build` and `npm run check` pass with no warnings.
- The page at 1440px matches the direction D mockup in section order, colours and type. The page at 390px shows the strip as a scroller and no horizontal page scroll.
- Every link in the doc resolves: Klimaatzaak, Instagram, Bruzz, letsmakeamark.org, Gwen Dubois, Piet Lambrecht, the email.
- Changing `status` in the edition file changes the pill without touching anything else.
- Netlify deploy preview builds green from the repo.

## Out of scope for this build

French and Dutch pages, an application form on the site, an organisations sign-up section, an editions archive or per-edition pages, a photo library, an SVG logo, a CMS. Each has a place to land: a new content file, a new section component, or a new route.
