---
title: Organisations page parity
tags: [organisations, typography, audience-pages, build]
sources:
  - "../../raw/2026-10-19-organisation-page-parity-selection.md"
  - "36-maker-form-copy.md"
  - "28-maker-hero-arrangement.md"
  - "35-form-typography-normalization.md"
  - "../../../resources/views/audience.blade.php"
  - "../../../resources/views/applications/context.blade.php"
  - "../../../resources/views/applications/organisation.blade.php"
  - "../../../resources/css/makers.css"
  - "../../../resources/css/public-typography.css"
phase: Build
updated: 2026-10-19
status: implemented and verified in the running product
---

# Organisations page parity

The owner selected all five optional changes in the visual comparison and
confirmed removal of the organisation payment FAQ. Organisation copy and
field-level required markers remain intact. [Selection](../../raw/2026-10-19-organisation-page-parity-selection.md)

## Decision and implementation

The organisation hero no longer has the Edition back link. Its right column
uses the shared D06-D date component above the introduction and application
action. The organisation introduction uses the Makers lead size, cream color,
300 weight and 1.4 line height; its wording is unchanged.

The organisation application introduction now uses the shared lead role. The
payment FAQ and the form's “* Required fields” footer note are removed.
Required fields continue to show their individual markers. The normalised
field, answer, hint, privacy and error typography remains shared across both
audience forms. [Maker copy decision](36-maker-form-copy.md);
[hero arrangement](28-maker-hero-arrangement.md);
[form typography](35-form-typography-normalization.md).

## Verification

The running page was reviewed at 1440px and 390px. Both widths show no hero
back link, one shared date stamp, no payment FAQ and no required-fields footer
note; all six required-field markers remain. The hero and application leads
compute to 24px/300 desktop and 20px/300 mobile. There is no horizontal
overflow or browser page error. [Desktop capture](../../../.lavish/assets/organisation-parity-desktop.png);
[mobile capture](../../../.lavish/assets/organisation-parity-mobile.png).
