---
title: Homepage lead treatment
tags: [homepage, typography, implementation]
sources:
  - "../../raw/2026-10-19-homepage-lead-feedback.md"
  - "../../raw/2026-10-19-homepage-detail-feedback.md"
  - "../../../resources/css/makers.css"
  - "../../../resources/css/tokens.css"
  - "../../../resources/views/home.blade.php"
phase: Build
updated: 2026-10-19
status: implemented and browser-verified
---

# Homepage lead treatment

The owner requested removing the date from the homepage hero and applying the
Makers lead settings to the hero introduction and the text in the right column
of “The 2027 edition.” The Makers lead uses the shared `--fs-lead` size, 300
weight and 1.4 line height. [Makers styling](../../../resources/css/makers.css)
and [lead token](../../../resources/css/tokens.css).

The homepage hero date line is removed. The hero intro and edition copy use
the Makers lead size and line height; their existing public typography role
supplies the reading family and 300 weight. The hero lead uses Ink against the
cream background, while the edition copy uses Paper Cream on its red panel.
The edition date remains in its section. [Owner feedback](../../raw/2026-10-19-homepage-lead-feedback.md).

The running homepage and `/makers` were checked at 1440px and 390px. Both
leads compute to 24px/33.6px desktop and 20px/28px mobile, weight 300; colors
match their respective surfaces. The homepage hero date is absent, edition
date remains, and neither viewport has horizontal overflow. Desktop capture
reviewed.

## Homepage detail refinements

The owner asked to omit the hero archive caption and carousel counter, use the
Makers date component in the edition section, remove borders around evidence
links, and remove dividers around and between the About team members. The
homepage caption and its link were removed; its source metadata remains for
the local original-message preview. The date markup
now comes from the shared [`edition-date` component](../../../resources/views/components/edition-date.blade.php),
used by both homepage and Makers page. Evidence-link and team-list rules no
longer draw the requested borders. [Owner feedback](../../raw/2026-10-19-homepage-detail-feedback.md).

The running homepage and Makers page were checked at 1440px and 390px. Both
show the same “Saturday 27 March 2027” lockup. The homepage caption and counter
are absent, both carousel arrows remain, evidence-link and team-member borders
compute to zero, and neither width has horizontal overflow or browser errors.
Desktop and mobile captures were reviewed.
