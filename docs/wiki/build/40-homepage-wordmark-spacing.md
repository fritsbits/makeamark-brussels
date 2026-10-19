---
title: Homepage wordmark and hero spacing
tags: [homepage, navigation, spacing, implementation]
sources:
  - "../../raw/2026-10-19-homepage-wordmark-spacing-request.md"
  - "../../../resources/views/sections/nav.blade.php"
  - "../../../resources/views/home.blade.php"
  - "../../../resources/css/app.css"
phase: Build
updated: 2026-10-19
status: implemented and browser-verified
---

# Homepage wordmark and hero spacing

The owner asked for more breathing room above and below the enlarged homepage
wordmark, and a slight reduction in the space before the hero title.
[Request](../../raw/2026-10-19-homepage-wordmark-spacing-request.md).

The homepage navigation row now has 104px of height while the wordmark is
expanded, providing about 27px above and below its visual 50.6px desktop form.
As scrolling begins, the row transitions to 56px on desktop while the logo
scales to its compact size. On phones the row transitions from 104px to 85px.
The height transition uses `grid-template-rows`; the header content remains
vertically centered. The hero top padding changes from 72px to 60px at 1440px,
and from 32px to 28px at 390px. [Implementation](../../../resources/css/app.css).

Chromium review on the running homepage confirmed a 105px expanded nav row,
reducing to 57px on desktop and 86px on mobile after scrolling. Hero top
padding is 60px at 1440px and 28px at 390px. Both widths have no horizontal
overflow or browser errors; reduced-motion preference disables the transition.
Desktop and mobile captures were reviewed.
