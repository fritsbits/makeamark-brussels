---
title: Homepage wordmark scroll stability
tags: [homepage, navigation, motion, implementation]
sources:
  - "../../raw/2026-10-20-homepage-wordmark-jump-request.md"
  - "../../../resources/views/sections/nav.blade.php"
  - "../../../resources/js/site-navigation.js"
  - "../../../resources/css/app.css"
phase: Build
updated: 2026-10-20
status: implemented and browser-verified
---

# Homepage wordmark scroll stability

The owner reported repeated large/small logo jumps at one scroll position and
asked to prevent them. [Request](../../raw/2026-10-20-homepage-wordmark-jump-request.md).

The homepage originally switched compact state directly at `scrollY > 24`.
Small scroll changes on either side of that single boundary toggled the
wordmark scale and animated navigation height. The height transition can also
move the reported scroll position by 48px on desktop, so a narrow dead band
would still allow the state to reverse during the transition. The scroll
listener now enters compact above 64px and leaves it at or below 8px. The
56px dead band exceeds the desktop header-height change. The behavior remains
in the shared navigation controller; the homepage CSS continues to define the
responsive logo scale and reduced-motion treatment. [Navigation state](../../../resources/views/sections/nav.blade.php),
[controller](../../../resources/js/site-navigation.js),
[styles](../../../resources/css/app.css).

In the running homepage, repeated scroll positions near 64px leave the logo in
its current state through the height transition; returning to 8px restores the
large state. Checks at 1440px and 390px confirmed the responsive header layout
and no browser errors.
