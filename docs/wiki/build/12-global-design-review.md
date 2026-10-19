---
title: Global Make a Mark design review
tags: [design, review, tokens, photography]
sources:
  - "../../raw/2026-10-18-global-design-observations.md"
  - "../../raw/2026-10-18-image-mapping-request.md"
  - "../../raw/2026-10-18-homepage-top-photo-selection.md"
  - "../../raw/2026-10-18-homepage-carousel-photo-batch.md"
  - "../../raw/2026-10-19-carousel-same-aspect-ratio.md"
  - "../../../DESIGN.md"
  - "../../../PRODUCT.md"
  - "../../../resources/css/tokens.css"
  - "../../../resources/views/home.blade.php"
  - "11-site-photo-treatments.md"
  - "../image-map.md"
  - "../image-map-new-site.md"
phase: Build
updated: 2026-10-19
status: homepage refinement, selected hero photos, and eleven-slide 4:3 archive carousel implemented; photographer credit and permissions pending
---

# Global Make a Mark design review

## Assessment

Interpretation: Brussels has a recognisable identity, but large uniform
fields and repeated text-led compositions flatten it. It does not read as
a generic software template. Its condensed type, warm palette, local logo
and event photography are worth keeping. The global site creates more depth
with tonal panels, near-monochrome photography, grain and a photo-filled hero.
[Local design](../../../DESIGN.md); [homepage](../../../resources/views/home.blade.php);
[global capture](../../raw/2026-10-18-global-design-observations.md).

The owner specifically identified tonal reds, tonal creams and black-and-white
photography as useful references.
[Feedback](../../raw/2026-10-18-global-design-observations.md).

## Proposed surface tokens

Values below are measured on the global homepage; roles are recommendations.
The first three values already exist locally, but lighter red is currently
reserved for display text. The darker cream is a proposed addition.
[Capture](../../raw/2026-10-18-global-design-observations.md);
[local tokens](../../../resources/css/tokens.css); [rules](../../../DESIGN.md#colors).

| Proposed token | Value | Role |
| --- | --- | --- |
| `red-base` | `#C33F40` | Major red sections and primary actions |
| `red-surface` | `#D14B4C` | Lighter panels inside deeper red sections |
| `paper-base` | `#F5E2CA` | Main page background |
| `paper-inset` | `#EBD5BB` | Recessed cream panels and grouped information |

Retain Ink for reading and Cream Light for readable text on red. Check
contrast for each new text/background pairing before implementation.
[Current contrast rules](../../../DESIGN.md#colors).

## Recommendations, in priority order

1. **Use both reds as surfaces.** Keep the deeper red for the process
   section; try the lighter red behind the numbered steps. Use thin cream
   rules and internal spacing instead of shadows. The global story montage
   demonstrates this red-on-red depth. This proposes revising the local
   restriction on Headline Red.
   [Global capture](../../raw/2026-10-18-global-design-observations.md);
   [local rules](../../../DESIGN.md#colors).

2. **Give cream a second surface.** Try `paper-inset` behind both audience
   panels with equal visual weight, or behind practical edition details.
   Make the youth-focus section a quieter editorial treatment to vary the
   sequence of red blocks. This is a tonal palette extension, not an approved
   change to the current two-ink rules.
   [Homepage](../../../resources/views/home.blade.php);
   [global capture](../../raw/2026-10-18-global-design-observations.md).

3. **Bring monochrome photography into the hero.** Propose two or three
   existing event images beside the desktop headline and one deliberate
   crop on mobile. Keep both audience actions visible. Charcoal shadows and
   photographic highlights would interrupt the cream field and show people
   making together before the text sections. The global treatment is actually
   near-monochrome with a warm wash; test a less warm local version to make
   the visual break clearer. Preserve the selected risoprint group photo as
   a special moment, and do not blanket-filter the Instagram story graphics.
   [Homepage](../../../resources/views/home.blade.php);
   [global treatment](../../raw/2026-10-18-global-design-observations.md);
   [photo decision](11-site-photo-treatments.md).

4. **Increase typographic contrast.** Keep Big Shoulders and Plus Jakarta
   initially. Test a larger desktop hero, substantially larger process
   numbers, and quieter supporting headings. The global site's Humane and
   General Sans are a separate font-comparison option; replacing local fonts
   revises an existing brand commitment. Font permissions remain unchecked.
   [Local tokens](../../../resources/css/tokens.css);
   [commitments](../../../PRODUCT.md#brand-commitments);
   [global typography](../../raw/2026-10-18-global-design-observations.md).

5. **Add restrained print texture.** Trial static grain behind content at
   roughly 3 to 5 percent. The global site's declared grain is 6 percent.
   Keep forms clean and preserve reading contrast. Upright arrows on
   navigational pills and a short reduced-motion-aware hover are lower
   priority refinements after the colors and image relationships work.
   [Global capture](../../raw/2026-10-18-global-design-observations.md).

## Next review

Recommendation: compare the existing homepage against one revised hero and
process section using the four surface colors and monochrome photos. Preserve
the approved sitemap, audience hierarchy, local logo and application flow.
B-006 tracks final selection. The initial review above is retained as the
rationale; the implementation below records what has since changed.

## Initial homepage implementation

Following the review, the owner asked to use international-site photographs
temporarily while sourcing Brussels images. The owner later supplied ten
photographs and selected two for the hero: participants mingling at a gathering
and a team planning around a table. These replace the temporary global images. See the
[catalogue](../image-map.md) and [slot map](../image-map-new-site.md).
Photographer credits and publication rights remain to be confirmed.
[Request](../../raw/2026-10-18-global-placeholder-photo-request.md).

The homepage now has two locally served international images in a neutral
monochrome collage, with a visible global-community source caption. Their
filenames, temporary flags and original URLs live in `hero.photos` in
`resources/content/site.json`. Replace each photo record and its 480px/960px
WebP files when local photography is available; update alt text and the source
caption at the same time. These images are placeholders and do not document
a Brussels edition. Individual photographer credits are not identified by
the inspected page. [Content](../../../resources/content/site.json)

The process numbers use lighter-red panels within the deeper-red section.
The body explanations stay on deep red because Cream Light on lighter red is
only 4.07:1. Both audience panels use `#EBD5BB`. The original implementation
retained the group-photo risoprint/reveal and existing story images. The owner
subsequently replaced the group's Riso base with the same monochrome treatment
as the hero, keeping its color reveal. [Current photo record](11-site-photo-treatments.md#current-monochrome-treatment).
Grain and a font replacement have not been applied.
[Tokens](../../../resources/css/tokens.css);
[homepage](../../../resources/views/home.blade.php);
[styles](../../../resources/css/app.css).

Browser verification passes at 1440px and 390px: images decode, the menu and
FAQ work, the story strip supports keyboard scrolling, audience/application
routes load, and there is no horizontal overflow. Desktop/mobile screenshots
were inspected. The production build passes with existing runtime font URL
warnings. The PHP suite reports 15 passing and two failing tests: one expects
a pre-existing missing `role="status"`; the other still expects preparation
copy although applications are open. Neither assertion concerns these photo
or surface changes. [Checks](../../../scripts/screenshot.cjs);
[existing assertions](../../../tests/Feature/EditionWebsiteTest.php).

The deterministic design audit's remaining advisories are existing Riso-tool
sizes/radii, existing FAQ/nav/hero sizes and already documented photo shadows.
They are contextually intentional or incomplete detector coverage, not new
visual defects from this preview. The new process size is a named documented
token; the hero reuses the Clipping shadow. No suppressions were added.
