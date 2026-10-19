---
title: Warm ink and commitment lead
tags: [typography, colour, audience-pages]
sources:
  - "../../raw/2026-10-19-warm-ink-type-request.md"
  - "../../../resources/css/tokens.css"
  - "../../../resources/css/public-typography.css"
phase: Build
updated: 2026-10-19
status: implemented and verified
---

# Warm ink and commitment lead

The owner asked to soften the black typography with a little cream, add a
distinct dark-brown ink for selected sections, and use the maker hero's larger
lead treatment in brown below “What you commit to.” [Request and screenshot](../../raw/2026-10-19-warm-ink-type-request.md)

The shared ink changes from `#1E1A16` to the slightly warmer `#29231D`. The
initial Ink Brown (`#49372A`) was later lightened to `#554638` and then to
`#6B5645` after form typography feedback. Both remain distinct from the red
family. On Paper Cream (`#F5E2CA`), the current contrast ratios are 12.28:1 and
5.46:1.

The commitment lead paragraph uses the shared `--fs-lead` (20–24px), 300
weight, and 1.4 line height, matching the maker hero lead's type treatment. It
uses dark brown while the red display heading and the numbered step styles
remain unchanged. [Tokens](../../../resources/css/tokens.css); [public type roles](../../../resources/css/public-typography.css)

The running makers page computes the commitment lead to 24px / 33.6px on
desktop and 20px / 28px on mobile, with 300 weight and Ink Brown at both
widths. At 390px there is no horizontal overflow. See [form typography normalization](35-form-typography-normalization.md)
for the current token and latest verification.
