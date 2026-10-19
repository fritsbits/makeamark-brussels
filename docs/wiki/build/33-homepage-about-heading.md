---
title: Homepage About heading width
tags: [homepage, about, typography, layout]
sources:
  - "../../raw/2026-10-19-homepage-about-heading-width.md"
  - "../../../resources/views/home.blade.php"
  - "../../../resources/css/app.css"
phase: Build
updated: 2026-10-19
status: implemented and visually reviewed at desktop and mobile widths
---

# Homepage About heading width

The owner asked for “The people behind Make a Mark” to use the full width so
“Mark” does not sit alone on a second line. [Request and screenshot](../../raw/2026-10-19-homepage-about-heading-width.md)

The heading is now its own full-width grid row above the people list and logo.
Balanced wrapping preserves a readable mobile layout. [Homepage markup](../../../resources/views/home.blade.php);
[styles](../../../resources/css/app.css)

## Review

At 1440px the heading uses one line across the full content width. At 390px it
wraps into two balanced lines above the organiser list and logo. The production
asset build succeeds; no automated tests were run for this layout change.
