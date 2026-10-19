---
title: Homepage navigation wordmark scale and color
tags: [homepage, navigation, typography, implementation]
sources:
  - "../../raw/2026-10-19-homepage-wordmark-scroll-request.md"
  - "../../../resources/views/sections/nav.blade.php"
  - "../../../resources/css/app.css"
  - "../../../resources/css/tokens.css"
phase: Build
updated: 2026-10-19
status: implemented and browser-verified
---

# Homepage navigation wordmark scale and color

The owner asked for a prominent homepage navigation wordmark that reduces in
size as scrolling begins, and selected dark brown over red to keep the logo
from competing with the red homepage title. [Request](../../raw/2026-10-19-homepage-wordmark-scroll-request.md).

The shared navigation wordmark now uses the primary Ink token (`#29231D`). On
the homepage, it scales visually to 2.5 times its compact size on wide screens,
scales down as soon as the page scrolls more than 24px, and returns to the
large state at the top. The navigation row keeps a steady height; the wordmark
uses a transform so the scroll transition does not animate layout. Narrow
screens use a smaller starting scale to preserve room for the menu control.
The scroll-scale behavior is limited to the homepage; other pages retain the
compact wordmark. The transition is disabled for reduced-motion preferences.
[Implementation](../../../resources/css/app.css) and [navigation state](../../../resources/views/sections/nav.blade.php).

Chromium review on the running homepage confirmed the visual scale changes
from 2.5× to 1× on wide desktop, 1.8× to 1× at tablet widths, and 1.45×/1.25×
to 1× on phones after scrolling. At 1440, 1021, 1020, 800, 390 and 320px, the
wordmark uses Ink (`rgb(41, 35, 29)`), does not overlap navigation controls, and
causes no horizontal overflow or browser errors. The nav row keeps its height
while the wordmark scales. Reduced-motion preference removes the transition.
Initial desktop and mobile captures were reviewed.
