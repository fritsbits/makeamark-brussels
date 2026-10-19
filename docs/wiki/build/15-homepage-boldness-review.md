---
title: Homepage boldness and motion review
tags: [design, homepage, motion, review]
sources:
  - "../../raw/2026-10-18-homepage-carousel-request.md"
  - "../../raw/2026-10-18-homepage-boldness-selection.md"
  - "../../raw/2026-10-18-homepage-boldness-request.md"
  - "../../../resources/views/home.blade.php"
  - "../../../resources/css/tokens.css"
  - "../../../resources/js/photo-reveal.js"
  - "../../../.lavish/homepage-boldness.html"
  - "https://www.letsmakeamark.org/resources"
phase: Build
updated: 2026-10-18
status: all four suggestions selected, implemented and browser-verified
---

# Homepage boldness and motion review

Subsequent update: the single group photograph is now a four-slide carousel
using duplicate placeholders. H01's separate growth effect is retained and
verified across five widths. [Carousel receipt](17-homepage-photo-carousel.md)

The owner requested a bolder homepage, possible animation, group-photo
growth on scroll and inspiration from the global resources page, followed by
a clear Lavish overview. [Request](../../raw/2026-10-18-homepage-boldness-request.md)

## Review proposals

The [Lavish overview](../../../.lavish/homepage-boldness.html) uses current-site
captures, the existing palette and self-hosted fonts, composition studies and
an isolated scrolling photo demo. It offers four independent selections.
These are proposals; the public homepage is unchanged. [Artifact](../../../.lavish/homepage-boldness.html)

| ID | Proposal | Recommendation and cost |
| --- | --- | --- |
| H01 | Heading above a wide group photo; scroll growth from 75% to full width on desktop, 90% on phones; resource links below. | Recommended first. Taller section; needs larger responsive image assets. Reduced motion shows the final size. |
| H02 | Larger poster-style opening with deliberate headline breaks and photos at its edge. | Recommended with H01. Needs breakpoint-specific composition without obscuring text. |
| H03 | Larger, equally prominent audience invitations and matching action arrows. | Recommended if H02 is chosen. Taller audience panels. |
| H04 | Short staggered entrance for the hero photos and small arrow movement on hover. | Optional. Keep separate from existing photo reveal transforms and honour reduced motion. |

The recommendation is the agent's interpretation. No suggestion is
preselected. Selection controls queue one final batch for the owner to send
through Lavish. [Artifact and feedback controls](../../../.lavish/homepage-boldness.html)

## Evidence and boundaries

The current homepage places the group photo beside its evidence links; the
existing photo interaction handles original-colour reveal and keyboard/touch
operation. Proposed scroll growth belongs on a separate outer wrapper.
[Homepage](../../../resources/views/home.blade.php);
[photo interaction](../../../resources/js/photo-reveal.js)

The global resources page was inspected on 2026-10-18. Its oversized repeated
lettering, broad red and cream areas, and photographic section inform the
proposals. These are visual observations, not an official brand specification.
The reference capture is stored with the
[review assets](../../../.lavish/assets/homepage-boldness/global-reference.png).
[Global page](https://www.letsmakeamark.org/resources)

The artifact preserves existing public copy and uses temporary global-community
images already used by the homepage. B-007 still tracks replacement with
sourced Brussels photographs. [Current homepage](../../../resources/views/home.blade.php);
[photo provenance](12-global-design-review.md#initial-homepage-implementation)

## Verification

The isolated overview passes Chromium checks at 1440px and 390px for decoded
images, fonts, horizontal/text overflow, independent selection, a single queued
H01/H02 batch and motion replay. The photo demo passes checks for scroll growth,
its static toggle and reduced-motion mode. This verifies review tooling, not
implementation in the public product.

Design detector triage: actual font names replace aliases, and small red review
labels use reading ink to meet contrast. The prototype's proposed type sizes
are intentional review values; photo-shadow colour matches the existing
documented clipping shadow. Narrow artifact-specific detector exceptions
record these choices. The later hook audit below records the subsequent
sidecar refresh.

## Selected implementation receipt

The owner selected all H01–H04 for implementation and added that the H02
photographs should stay because they convey the atmosphere. The owner ended
the Lavish review; it is retained as the original proposal record.
[Selection](../../raw/2026-10-18-homepage-boldness-selection.md)

| ID | Outcome | Evidence |
| --- | --- | --- |
| H01 | Implemented: heading above a 1200px photo, reversible viewport-linked growth, caption and links below. | [Blade](../../../resources/views/home.blade.php), [Alpine component](../../../resources/js/scroll-photo.js), [browser check](../../../scripts/homepage-motion-check.cjs). |
| H02 | Implemented: homepage poster headline capped at 150px, sentence breaks and both hero photos retained on desktop and phones. | [Blade](../../../resources/views/home.blade.php), [scoped CSS](../../../resources/css/app.css), five-width browser checks and inspected desktop/mobile captures. |
| H03 | Implemented: larger equal audience invitations and matching Flux arrow actions. | [Blade](../../../resources/views/home.blade.php), [CSS](../../../resources/css/app.css), equal two-action browser check. |
| H04 | Implemented: 650ms outer photo entrances, 80ms stagger and 3px arrow movement. Reduced motion disables movement. | [CSS](../../../resources/css/app.css), runtime preference checks and focused independent code review. |

The new 1200px group WebP was encoded from the original 1200×900 JPEG in the
documented source checkout at
`/Users/frederikvincx/Documents/makeamark/site/src/assets/stories/edition-2026-group.jpg`.
It joins the existing 440px and 880px variants; no source was enlarged.
[Migration source](../../../README.md);
[asset](../../../public/images/edition-2026-group.1200.webp)

Verification: production build passes with existing runtime font-reference
warnings; all 89 PHP tests and all five JavaScript tests pass. Read-only
browser checks pass at 1440, 1024, 860, 640 and 390px, covering growth/reversal,
stable document height, full-size frame, both hero photos, keyboard colour
reveal, runtime reduced motion and zero horizontal overflow. The 390px check
also exercises touch pin/reset; no-JavaScript checks pass. Desktop and phone
screenshots were inspected together. A fresh reviewer found no material issues.
[Browser check](../../../scripts/homepage-motion-check.cjs)

Pint remains red on unrelated existing formatting in `bootstrap/providers.php`
and `routes/console.php`; neither file was changed in this work. The design
detector's new 150px finding is the approved homepage scale, now documented in
DESIGN.md. Other reported app.css values belong to existing tooling, navigation
and audience styling outside this change; they were left unchanged without new
global detector exceptions.

## Concern status

B-006 records this completed selection and verification alongside the previous
polish selection. The remaining broader global-site proposals and B-007 local
photo replacement are separate. [Concerns](01-build-concerns.md)

## Follow-up hook audit

The stop hook requested review of the overview's font and contrast findings.
Chromium measurements at 1440, 1024 and 390px found no text below its applicable
contrast threshold. The flagged red headings are large text at 42px or more
and have 3.45:1 contrast, above the 3:1 large-text requirement. The computed
body font is Plus Jakarta Sans, as documented in DESIGN.md; the detector's
shortened `jakarta sans` family is a parsing false positive. Section padding
measures 44–64px despite the detector's cramped-padding report.

The actual small mockup labels were increased to 12px. Matching audience
buttons now also use 12px, with stacked proposal panels at intermediate widths
to leave them space. These changes affect the review artifact only.
[Review CSS](../../../.lavish/assets/homepage-boldness/overview.css)

Narrow file-specific exceptions record the established body font, measured
large-text contrast, parsed font name and section padding. No rule was disabled
project-wide. The final detector run over all three flagged files returned
an empty finding list. [Detector config](../../../.impeccable/config.json)

The design sidecar was refreshed without rewriting DESIGN.md. It now records
the selected homepage display role, photo reveal, entrance, arrow and scroll
motion metadata, and replaces its obsolete duotone photo preview with neutral
monochrome. [Sidecar](../../../.impeccable/design.json)
