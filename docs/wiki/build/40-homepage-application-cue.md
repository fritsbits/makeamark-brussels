---
title: Homepage applications-open cue
tags: [homepage, applications, navigation, implementation]
sources:
  - "../../raw/2026-10-19-homepage-applications-open-cue-request.md"
  - "../../raw/2026-10-19-homepage-apply-now-scribble-request.md"
  - "../../../.bloom/attachments/Xy4Daz/scribble.pdf"
  - "../../../.bloom/attachments/z9mWGC/Pasted 2026-10-19 at 10.53.35.png"
  - "../../../resources/views/home.blade.php"
  - "../../../resources/css/app.css"
  - "18-audience-arrangement.md"
phase: Build
updated: 2026-10-19
status: superseded by the owner-provided vector scribble direction
---

# Homepage applications-open cue

This first hand-drawn underline and arrow cue was implemented and verified,
then superseded when the owner supplied a complete “Apply now!” scribble to
replace it. The source artwork and selected color treatment are documented in
[the follow-up](42-homepage-apply-now-scribble-options.md).

The owner asked for a bolder “Applications open” moment and suggested a
scribbled underline and arrows toward the two application routes.
[Request](../../raw/2026-10-19-homepage-applications-open-cue-request.md)

The social graphic uses loose, uneven marker strokes and hand-drawn speech
tails. The homepage cue borrows that line quality with an offset, double-pass
red underline and two asymmetric downward arrows. The existing edition-state
label stays intact and links to `#take-part`, where the equal Makers and Social
organisations invitations begin. The SVG is decorative, while the link names
the action for assistive technology and keeps a visible keyboard focus ring.
It uses the existing red and ink tokens.
[Markup](../../../resources/views/home.blade.php);
[styles](../../../resources/css/app.css);
[social artwork](../../../.bloom/attachments/z9mWGC/Pasted%202026-10-19%20at%2010.53.35.png);
[audience arrangement](18-audience-arrangement.md)

Chromium review on the running homepage at 1440px confirmed the doubled red
marker underline and paired arrows render below the dynamic status and above both audience
panels. Activating the cue reaches `#take-part`; the accessibility tree exposes
it as “Applications open. Explore ways to take part.” `npm run build` passes.
Vite reports the existing unresolved runtime font references, which it leaves
for the app server to resolve.

## Concern status

B-032 is closed: the homepage status cue now directs visitors to the two
audience routes while preserving the existing dynamic edition label.
