---
title: Audience form typography normalization
tags: [typography, forms, audience-pages]
sources:
  - "../../raw/2026-10-19-form-typography-request.md"
  - "../../raw/2026-10-19-form-typography-feedback.md"
  - "../../../resources/css/public-typography.css"
  - "../../../resources/css/tokens.css"
  - "../../../DESIGN.md"
phase: Build
updated: 2026-10-19
status: implemented and verified
---

# Audience form typography normalization

The owner asked to reduce form type-size variation, follow the established hero
and reading-text treatment, and use brown for secondary copy at the same size
instead of shrinking it. [Request](../../raw/2026-10-19-form-typography-request.md)

## Decision

Keep section legends in the existing red display face and retain semibold field
labels. Normalize form reading text to the shared 17px size: field labels,
answers, hints, required-field guidance, privacy copy, sending status and
validation errors. Supporting guidance, privacy copy and sending status use Ink
Brown and the same 300 weight as reading copy; actionable validation errors
remain Ink at 400. This keeps secondary content readable while reducing size changes within the form. The form styles are shared
by makers and organisations.

Ink Brown was initially lightened from `#49372A` to `#554638`. After review, it
is now `#6B5645`. Contrast is 5.46:1 on Paper Cream, 6.45:1 on Paper Cream Light,
and 4.85:1 on Paper Cream Inset. [Palette](../../../resources/css/tokens.css)

## Implementation

The public typography role rules and shared warm-brown token are updated in
`resources/css/public-typography.css` and `resources/css/tokens.css`. The
production CSS build passes; only the repository's existing runtime font URL
notices remain. Running-page checks on `/makers` and `/organisations` at 1440px
and 390px confirm 17px labels, answers, hints, privacy copy and required
guidance. Labels remain 600 weight; secondary text computes to Ink Brown
`#6B5645` at 300 weight. The red section legends retain their existing display sizes (34px
desktop, 26px mobile). Desktop and mobile makers form captures were reviewed.

The context and privacy note both compute to 17px / 300 weight; the privacy
link remains 600. Ink Brown contrast is 5.46:1 on Paper Cream, 6.45:1 on Paper
Cream Light, and 4.85:1 on Paper Cream Inset.
