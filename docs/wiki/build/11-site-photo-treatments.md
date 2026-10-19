---
title: Selected site photo treatments
tags: [build, photography, content]
sources:
  - "../../raw/2026-10-18-homepage-carousel-request.md"
  - "../../raw/2026-10-18-all-photo-treatment-selection.md"
  - "../../raw/2026-10-18-homepage-carousel-photo-batch.md"
  - "../../raw/2026-10-19-carousel-same-aspect-ratio.md"
  - "../../../resources/views/components/photo-reveal.blade.php"
  - "../../raw/2026-10-18-monochrome-group-photo-selection.md"
  - "../../raw/2026-10-18-site-risoprint-selection.md"
  - "../../raw/2026-10-18-homepage-photo-reveal-selection.md"
  - "../../../resources/content/photo-treatments/riso.json"
  - "../../../scripts/build-photo-treatments.mjs"
  - "../../../resources/views/components/photo.blade.php"
  - "../../../resources/views/sections/edition.blade.php"
  - "../../../resources/js/photo-reveal.js"
  - "../../../resources/css/app.css"
  - "https://sharp.pixelplumbing.com/api-output/"
phase: Build
updated: 2026-10-19
---

# Selected site photo treatments

The owner requested the hero styling and the group photo's expanding color
reveal on every photo. The two hero images and eleven carousel images now use
the shared `<x-photo-reveal>` component. It applies neutral
monochrome, the hero's 10px rounded corners and Clipping shadow, and the
original-color rollover. The group photo's cream border is removed. The logo
is unchanged. [Owner selection](../../raw/2026-10-18-all-photo-treatment-selection.md);
[component](../../../resources/views/components/photo-reveal.blade.php);
[styles](../../../resources/css/app.css).

## Current monochrome treatment

The homepage now uses two hero photos and an eleven-slide evidence carousel.
The carousel starts with the existing 2026 group photo and continues through
seven supplied 2020 landscape photos. All ten homepage photos retain this
treatment and reveal. The portrait supplied photo stays catalogued but is not
used in the carousel because it does not suit the shared 4:3 frame. The earlier
single-photo counts below describe the implementation before the carousel.
[Carousel request and verification](17-homepage-photo-carousel.md)

`photoTreatment` in the edition record is `monochrome`. The reusable photo
component applies `.photo--monochrome`: `grayscale(1) contrast(1.04)`. Both
hero images and all story images use this same treatment. Every original-color overlay
has no treatment or filter. Its source is the same original responsive image,
so no processed image generation or extra image copy is needed.
[Component](../../../resources/views/components/photo.blade.php);
[styles](../../../resources/css/app.css);
[edition](../../../resources/content/editions/2027.json).

Use `<x-photo-reveal>` for public photos so styling and rollover stay consistent.
The lower-level `<x-photo>` still supports standalone original and treated
images for other uses. The build ignores
monochrome selections because CSS provides their treatment. Riso remains
available as local creative tooling; no public homepage image currently
selects it. The saved preset is retained for later use.
[Generator](../../../scripts/build-photo-treatments.mjs);
[saved preset](../../../resources/content/photo-treatments/riso.json).

The existing 700ms expanding reveal, 40px feather, 1.025 zoom, straightening,
click/tap/Enter pinning and Escape restore now apply to all photos. Each photo
has independent local Alpine state and a descriptive control label. Reveal
origins use the actual photo bounds instead of assuming an 8px border. Reduced-motion mode
reveals immediately without zoom or tilt. If the color layer cannot load or
JavaScript is disabled, the monochrome base remains visible. The button's
return label now names the desaturated version.
[Reveal](../../../resources/js/photo-reveal.js);
[markup](../../../resources/views/sections/edition.blade.php).

Current all-photo verification: three PHP photo tests and five JavaScript
tests pass, and the production build passes. The running browser check
passes for all visible photos on desktop, mobile and reduced motion, including
hover or tap, restore, keyboard pinning and independent photo state. All 12
monochrome bases remain visible with JavaScript disabled. There are no browser
errors or horizontal overflow. Desktop/mobile homepage and group-color
screenshots were inspected. The design hook's existing tool typography,
radius and shadow advisories are incumbent styles outside this change;
no suppression or unrelated design changes were made.
[Browser check](../../../scripts/photo-treatment-check.cjs);
[PHP tests](../../../tests/Feature/PhotoTreatmentTest.php);
[build](../../../package.json).

Earlier group-only verification: three PHP photo tests and five JavaScript tests pass;
the production build passes with zero selected baked treatments. Browser
checks pass for desktop hover, click, Enter and Escape, mobile tap/re-tap,
reduced motion and JavaScript disabled. They confirm identical hero/group
filters, an unfiltered color layer, no Riso asset references, no overflow and
no browser errors. The two desktop state screenshots were inspected.
[Browser check](../../../scripts/photo-treatment-check.cjs);
[PHP tests](../../../tests/Feature/PhotoTreatmentTest.php).

The sections below preserve the earlier risoprint implementation and
verification history. They do not describe the current homepage selection.

## Earlier risoprint selection and reuse

The earlier homepage group photo was enabled in
[`resources/content/editions/2027.json`](../../../resources/content/editions/2027.json):

```json
"photoTreatment": "riso"
```

To opt a story photo in later, add `"treatment": "riso"` to that item's record
in `resources/content/stories.json`. No story is currently selected. Remove
the relevant treatment key to restore that photo's original rendering.
[Content records](../../../resources/content/stories.json);
[photo module](../../../resources/views/components/photo.blade.php)

Run `npm run photos:build` after changing selections or the saved treatment.
`npm run build` also runs this step automatically. The generator makes treated
versions of the selected photo's existing responsive WebP sizes under
`public/images/treatments/riso/`. Original images remain intact. New photos
need their ordinary responsive WebP sources in `public/images` first.
[Generator](../../../scripts/build-photo-treatments.mjs);
[build scripts](../../../package.json)

For an image elsewhere in Blade, use the same reusable photo module:

```blade
<x-photo file="edition-2026-group.jpg" :widths="[440, 880]"
    treatment="riso" alt="Makers and organisers in a courtyard"
    width="440" height="330" loading="lazy" />
```

If the photo is outside edition/story content, select it in content as well
so the build generates its treated files. Omitting `treatment` in the module
uses the original sources. Alt text, sizes, loading and dimensions stay normal
image attributes. [Module](../../../resources/views/components/photo.blade.php)

## Earlier risoprint rendering decision

The build reuses the configurator's pixel engine and a 1200px long-edge master,
then creates responsive WebP copies. Sharp is a development dependency for
decoding, resizing and encoding; the effect itself remains in the shared
engine. The site needs no browser canvas or JavaScript to show the treatment.
The saved treatment has no added paper border; the existing polaroid frame
continues to provide the photo's border.
[Generator](../../../scripts/build-photo-treatments.mjs);
[Sharp output documentation](https://sharp.pixelplumbing.com/api-output/)

Selected photos bypass the old grayscale/red overlay, preserving the saved
ink colours. URLs include a digest of the rendered asset so regenerated
images receive a new cache key. Other photos retain their prior rendering.
[Edition markup](../../../resources/views/sections/edition.blade.php);
[module](../../../resources/views/components/photo.blade.php)

## Original-photo reveal implementation history

The owner requested a larger photo and selected the expanding reveal after
trying the rollover playground. The effect choice is confirmed. The agent
applied the preset defaults of 600px maximum frame width, 700ms duration,
40px feather, 1.025 original-photo zoom, and straightening from the existing
3-degree tilt. These numbers are implementation defaults, not individual
owner selections. [Owner selection](../../raw/2026-10-18-homepage-photo-reveal-selection.md)

The homepage frame uses the available width up to 600px. On desktop, the
edition grid gives the text 0.85fr and the photo 1.15fr; on mobile the photo
uses the available width with the same maximum. The original photograph is
layered over the print only when the edition has a photo treatment.
[Edition markup](../../../resources/views/sections/edition.blade.php);
[styles](../../../resources/css/app.css)

The interaction expands a reveal from the pointer's entry position.
Click, tap, or Enter toggles the original; Escape restores the print. If the
original cannot load, the print stays visible. Without JavaScript, the static
print remains. Reduced-motion mode reveals immediately and removes zoom and
tilt. Alpine manages this local state.
[Reveal component](../../../resources/js/photo-reveal.js);
[edition markup](../../../resources/views/sections/edition.blade.php)

## Homepage reveal verification

- `npm run build` passes. It reports the existing font URL resolution warnings;
  the running page renders its fonts and images.
- Playwright checks pass against the running HTTPS site at 1440px desktop,
  390px mobile and 1440px with reduced motion. The photo frame measures 600px
  on desktop and 286px within the mobile layout's available width.
- The checks cover progressive hover from the entry position, full reveal,
  restoration on pointer leave, click/tap pinning, Escape reset and Enter
  toggling. All three contexts have no browser errors or horizontal overflow.
  The print also remains visible with JavaScript disabled.
- Reduced-motion verification found existing photo-strip labels overflowing
  when transforms were removed. Making `.strip__item` positioned contains its
  absolutely positioned screen-reader labels. The repeat check has no overflow.
  [Styles](../../../resources/css/app.css)
- The PHP suite reports 15 of 17 tests passing. The two failures are earlier
  application-state expectations in `EditionWebsiteTest`: the homepage requires
  `role="status"`, and the audience-page test expects unpublished-application
  copy and unavailable application links. The edition content already has
  `status: open`. These failures are separate from the photo reveal checks.
  [Tests](../../../tests/Feature/EditionWebsiteTest.php);
  [edition content](../../../resources/content/editions/2027.json)
- The design detector reports 18 findings about existing typography, radii and
  shadows outside the new reveal CSS. For this refinement, those findings are
  treated as intentional parts of the incumbent design. No detector ignore
  configuration or design sidecar was changed.
  [Design record](../../../DESIGN.md)

## Earlier static-treatment verification

- Production build passes and generates only the group's 440px and 880px
  treated assets. The saved preset copy matches the owner's JSON exactly.
- Five JavaScript tests pass, including selection, output dimensions and
  preservation of original image bytes. Three photo integration tests pass.
- `scripts/photo-treatment-check.cjs` passes on desktop and mobile: exactly
  one treated photo, loaded responsive sources, no extra filter, unchanged
  story sources, no errors or overflow. The image also loads with JavaScript
  disabled. Screenshots in `/tmp/makeamark-site-riso/` were inspected.
- The full PHP suite initially reported 16 passing tests and one unrelated
  audience-page assertion failure: current edition content had changed from
  `preparing` to `open`, while the assertion expected preparation copy. The
  photo change does not alter edition status or application behavior.
- Security review found no actionable issue in the build or photo module;
  the locked npm dependency audit reported no vulnerabilities.
