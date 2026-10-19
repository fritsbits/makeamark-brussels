---
title: Messaging distillation and selected homepage
tags: [messaging, decision, implementation]
sources:
  - "../../raw/2026-10-18-messaging-selection.md"
  - "../../../resources/views/home.blade.php"
  - "../../../resources/views/audience.blade.php"
  - "../../../resources/content/site.json"
  - "../../../resources/content/editions/2027.json"
  - "../../../resources/content/previews/original-homepage.json"
  - "../../ux/_structure.md"
  - "../../ux/_skeleton.md"
  - "../strategy/10-tone-of-voice.md"
  - "../../../app/Http/Controllers/MessagingPreviewController.php"
  - "../../../tests/Feature/MessagingPreviewTest.php"
  - "../../../scripts/messaging-preview-check.cjs"
phase: Build
updated: 2026-10-18
status: owner selected distilled version; canonical implementation verified
---

# Selected homepage

The owner selected the second, distilled version and requested omission of
the homepage "How it works" section. The selected home and audience pages now
use the canonical `/`, `/makers` and `/organisations` routes. Application
forms remain at their existing destinations.
[Owner selection](../../raw/2026-10-18-messaging-selection.md).

## Applied decisions

- D01: one edition block combines the date, youth focus and venue status.
  The obsolete application-preparation caveat is removed.
- D02: the existing hero headline stays. Audience cards have one invitation
  each, without repeated introductory headings. The owner then removed the
  homepage process section entirely.
- D03: audience pages explain contribution, costs, preparation, selection and
  event steps. They retain application buttons at the top and bottom.
- D04: one Brussels photo and descriptive BRUZZ/Instagram links replace
  the story strip. Cost answers live on audience pages. About keeps names,
  short roles and contact. The footer carries the global organiser link.

The established identity, two equal audiences and attributed international
hero placeholders remain. The latter still need sourced Brussels replacements
under B-007. There is no new youth signup route or guaranteed finished-product
claim. [Home](../../../resources/views/home.blade.php);
[audience pages](../../../resources/views/audience.blade.php);
[content](../../../resources/content/site.json);
[voice guide](../strategy/10-tone-of-voice.md).

## Comparison retained locally

`/tools/messaging/original` renders the previous homepage against a preserved
public-content snapshot, with its original navigation/footer. The comparison
at `/tools/messaging/compare` embeds that page beside the canonical homepage
at matching desktop/mobile widths. Existing preview URLs render the selected
canonical templates, with links pointing to public routes. Tools remain
local/testing only, noindex and outside the public sitemap.
[Controller](../../../app/Http/Controllers/MessagingPreviewController.php);
[snapshot](../../../resources/content/previews/original-homepage.json);
[comparison](../../../resources/views/previews/messaging/compare.blade.php).

The [Lavish artifact](../../../.lavish/messaging-review.html) preserves the initial
analysis. Its observations about repetition and conflicting status copy apply
to the previous version, now retained in the snapshot. The owner initially
requested analysis only, then a duplicate, then selected the duplicate.
[Instruction record](../../raw/2026-10-18-messaging-selection.md).

## Verification

All 21 PHP tests pass, including updated canonical-page expectations, retained
photo treatment, preserved original content, sitemap exclusion and production
denial for tools. The earlier closed-edition assertions were updated to the
selected open-edition behavior as part of promotion. The production build
passes with the existing runtime-font URL warnings.
[Tests](../../../tests/Feature/EditionWebsiteTest.php);
[photo tests](../../../tests/Feature/PhotoTreatmentTest.php);
[preview tests](../../../tests/Feature/MessagingPreviewTest.php).

Running desktop/mobile checks pass for navigation, images, keyboard reveal,
audience/application routes, viewport comparison controls and overflow.
The homepage process and global FAQ/story strip are absent. Desktop/mobile
screenshots were visually inspected in one batch.
[Public check](../../../scripts/screenshot.cjs);
[comparison check](../../../scripts/messaging-preview-check.cjs).

The current homepage main text measures 165 words against 489 in the preserved
original, a 66% reduction. This excludes navigation/footer and does not measure
comprehension or conversion. B-011 is resolved by owner selection and verified
canonical implementation.
[Measurement](../../../scripts/messaging-preview-check.cjs).
