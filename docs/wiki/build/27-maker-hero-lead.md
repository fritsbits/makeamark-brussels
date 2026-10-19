---
title: Maker hero lead
tags: [typography, makers, design]
sources:
  - "../../raw/2026-10-19-maker-hero-lead-date-swap-request.md"
  - "../../raw/2026-10-19-maker-hero-lead-request.md"
  - "../../raw/2026-10-19-maker-hero-title-alignment-request.md"
  - "../../../resources/css/tokens.css"
  - "../../../resources/css/makers.css"
  - "../../../resources/views/audience.blade.php"
phase: Build
updated: 2026-10-19
status: implemented and browser-verified
---

# Maker hero lead

The makers-page introduction keeps its existing copy and now uses the named
`--fs-lead` type token with Paper Cream text. Its fluid 20–24px size and 1.4
line height establish a lead role above the shared 17px reading size. The
date now precedes the lead, with the lead and action grouped below it. This
supersedes the earlier title/lead top alignment. The organisation hero keeps
its existing body-text role.
[Current arrangement and verification](28-maker-hero-arrangement.md#current-lead-and-date-order)

The running page computes to 24px/33.6px on desktop and 20px on mobile, with
Paper Cream text at both widths and no horizontal overflow. At 1440px, the
title and lead bounding boxes originally shared a 172.2px top coordinate;
the later [order request](../../raw/2026-10-19-maker-hero-lead-date-swap-request.md)
supersedes that placement. Mobile stacks the date before the lead.

The owner selected this treatment through the [request](../../raw/2026-10-19-maker-hero-lead-request.md).
The stylesheet was rebuilt after the shared reading rule was found to override
the initial selector at equal priority. The makers-specific selector now wins.
The owner then requested top alignment between the maker title and lead. The
lead was top-aligned within its desktop grid row to match the title. The latest
arrangement places the date at that top edge and groups the lead with its action.
