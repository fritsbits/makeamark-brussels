---
title: Full-screen mobile menu
tags: [navigation, mobile, visual-design, implementation]
sources:
  - "../../raw/2026-10-19-mobile-menu-request.md"
  - "../../PRODUCT.md"
  - "../../DESIGN.md"
phase: Build
updated: 2026-10-19
---

# Full-screen mobile menu

The mobile menu now fills the viewport with Marker Red, using large cream
Big Shoulders Display links. The wordmark and close control remain visible over
the red field. Opening the menu prevents background scrolling; Escape and
following a link close it. Desktop navigation is unchanged.

The treatment follows the established broadsheet palette and type system.
Marker Red is the main ink, and Paper Cream Light carries small text with
4.78:1 contrast on Marker Red. At 390×844, the open menu covered the viewport,
background scrolling was locked, and the links rendered at 54.6px. At 320×568,
Escape and link activation both closed the menu. `npm run build` passed.
[Design system](../../DESIGN.md).

Implementation: `resources/css/app.css`, `resources/views/sections/nav.blade.php`.
