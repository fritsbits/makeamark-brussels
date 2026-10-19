---
title: Homepage polish decision review
tags: [design, polish, review, homepage]
sources:
  - "../../raw/2026-10-18-homepage-polish-selection.md"
  - "../../raw/2026-10-19-footer-city-link-copy.md"
  - "../../../.lavish/homepage-polish.html"
  - "../../../.lavish/assets/homepage-polish/observations.json"
  - "../../../resources/views/home.blade.php"
  - "../../../resources/views/sections/nav.blade.php"
  - "../../../resources/views/sections/footer.blade.php"
  - "../../../resources/css/app.css"
  - "13-messaging-review.md"
  - "01-build-concerns.md"
phase: Build
updated: 2026-10-19
status: P01–P06 selected and implemented; later footer invitation shortened; browser verified
---

# Homepage polish review

The owner selected P01–P06 and additionally requested removal of the duplicate
hero audience links. P07 was unselected and remains unchanged.
[Owner instruction](../../raw/2026-10-18-homepage-polish-selection.md).

The owner initially requested an Impeccable polish review and a visual Lavish decision
page to choose improvements. The [decision page](../../../.lavish/homepage-polish.html)
contains seven independent choices with current/proposed screenshots, source
snapshots, tradeoffs and structured selection controls. Proposed renders use
temporary browser DOM/CSS overrides. That artifact preserves the proposal
stage; the selected changes are now implemented in the running product.

## Observed evidence

- Adjacent hero/audience padding is 88px + 96px at 1440px, and 48px + 56px
  at 390px.
- Evidence links render without an underline at rest.
- The mobile menu control is 40px square; its glyph remains a hamburger
  while open.
- Footer link boxes measure approximately 19px high at both inspected widths.
  This alone does not establish a WCAG failure.
- The edition action labelled "Choose your application" links to the audience
  panels, whose actions lead to information pages.

Measurements: [browser observations](../../../.lavish/assets/homepage-polish/observations.json).
Content and destinations: [homepage](../../../resources/views/home.blade.php).
Interaction: [navigation](../../../resources/views/sections/nav.blade.php).

## Proposed choices

These benefits are design interpretations, not measured user outcomes.
Scope labels in the artifact are estimates.

| ID | Proposal | Review recommendation |
| --- | --- | --- |
| P01 | Reduce combined hero/audience padding to 80px desktop and 48px mobile. | Recommended |
| P02 | Give evidence links underlines, resource rows and outward arrows. | Recommended |
| P03 | Separate organiser names/roles into aligned desktop rows and stacked mobile rows. | Taste choice |
| P04 | Use a 48px mobile menu control and a close glyph while open. | Recommended |
| P05 | Group footer navigation and give links 44px minimum height. | Taste choice |
| P06 | Rename the edition action to "Explore ways to take part", retaining #take-part. | Recommended copy choice |
| P07 | Add an input-aware hint for the existing photo colour reveal. | Optional |

The [selected homepage](13-messaging-review.md) remains the boundary: existing
headline, equal audiences, section order, palette, fonts and photo treatment.
The review proposes no new public page, process section or FAQ. B-007 remains
separate because replacing the international hero placeholders needs sourced
Brussels photography. [Concerns](01-build-concerns.md).

## Verification

### Selected implementation receipt

| ID | Outcome and evidence |
| --- | --- |
| P01 | Addressed: combined adjacent padding measures 80px at 1440px and 48px at 390px; intermediate widths remain fluid. |
| P02 | Addressed: both evidence links have underlines, hairline resource rows and Flux outward-arrow icons. |
| P03 | Addressed: organiser rows use two columns on desktop and stack roles under names through 860px. |
| P04 | Addressed: menu control measures 48px square; the open state draws a close glyph. Escape/link-close behaviour remains verified. |
| P05 | Addressed: three footer groups; all seven links measure at least 44px high. Footer About links resolve to the homepage from audience pages. |
| P06 | Addressed: "Explore ways to take part" points to #take-part and the anchor works. |

The additional hero-link removal is addressed: the hero contains no audience
action group, while both audience-panel actions remain. The unselected P07
photo hint is absent. Existing monochrome/reveal behaviour is unchanged.

The production build passes with existing runtime font URL warnings; all
70 PHP tests pass, with 427 assertions. Running browser checks pass at
390, 768, 860, 1024 and 1440px for the selected work, no horizontal overflow,
and the shared footer on both audience pages. The existing desktop/mobile
screenshot helper also passes navigation, keyboard photo reveal and the
audience/application paths. Final desktop/mobile screenshots were inspected.
A scoped independent code review found no actionable issues.

Fine borders and hairlines now use the shared Paper Cream Rule token
(`--cream-rule`), including the homepage evidence links and organiser rows.
The earlier Rule Red hairline token was retired; red headings and actions
remain unchanged.

## Later footer copy refinement

The owner asked to shorten “Organise Make a Mark in your city.” The implemented
label is “Organise in your city”; the adjacent global-movement statement
provides its context. Destination, new-tab behavior, footer grouping, and link
hit area are unchanged. The running page was reviewed after the update.
[Owner instruction](../../raw/2026-10-19-footer-city-link-copy.md);
[footer](../../../resources/views/sections/footer.blade.php).
Initial hook notices concerned existing Riso-tool literals and the documented
photo shadow, outside this change; these were retained. Later hooks reported
no deterministic quality findings. DESIGN sidecar freshness is separate
tooling drift and was not repaired as a side effect of this polish task.

Implementation: [homepage](../../../resources/views/home.blade.php),
[patterns](../../../resources/css/app.css),
[rule token](../../../resources/css/tokens.css),
[footer](../../../resources/views/sections/footer.blade.php).
Final screenshots: [desktop](../../../.lavish/assets/homepage-polish/implemented/desktop.png),
[mobile](../../../.lavish/assets/homepage-polish/implemented/mobile.png).

### Initial review verification

The existing `scripts/screenshot.cjs` helper passed at 1440px and 390px:
navigation, photo keyboard activation, heading count, audience/application
routes, browser error checks and zero horizontal overflow. Proposed browser
previews also had zero horizontal overflow at both widths.

The decision artifact passed image/font loading, seven-choice coverage,
desktop/mobile preview switching, recommended-set selection, clearing,
single structured queue submission and zero overflow/browser errors at both
widths. Real screenshots are embedded. This does not constitute a complete
accessibility or performance audit. The owner subsequently selected the items
recorded above.

## Footer lockup and link rhythm

The owner found the lockup too detached from the right-side navigation and
called out loose spacing within the lockup and between links, plus uneven
padding. The footer now uses a two-part desktop grid, with the lockup in a
defined first column and three link groups sharing the remaining width. The
lockup's vertical gaps and link-row gaps are tighter; link targets retain a
44px minimum height. Vertical padding uses the same responsive gutter as the
horizontal inset. At 760px and below, the lockup stacks above a two-column link
layout. The red background spans the viewport and has square corners.

The running page was reviewed at 1306px and 390px. The desktop columns align
without a large dead gap; the mobile columns stack without overlap.
[Owner request](../../raw/2026-10-19-footer-arrangement-request.md);
[desktop capture](../../../.lavish/assets/footer-arrange-desktop.png),
[mobile capture](../../../.lavish/assets/footer-arrange-mobile.png);
[footer styles](../../../resources/css/app.css).
