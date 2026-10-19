---
title: Weekday-first date lockup variations
tags: [design, typography, makers, review]
sources:
  - "../../raw/2026-10-19-date-lockup-d06-d-selection.md"
  - "../../raw/2026-10-19-date-lockup-d06-refinements-request.md"
  - "../../raw/2026-10-19-date-lockup-variations-request.md"
  - "../../raw/2026-10-19-date-lockup-color-request.md"
  - "../../raw/2026-10-19-date-lockup-color-request.png"
  - "../../raw/2026-10-19-date-lockup-current.png"
  - "../../../resources/views/audience.blade.php"
  - "../../../resources/css/makers.css"
  - "../../../resources/css/tokens.css"
  - "../../../resources/css/fonts.css"
  - "../../../resources/content/editions/2027.json"
  - "../../../.lavish/date-lockups.html"
phase: Build
updated: 2026-10-19
status: D06-D selected, implemented and browser-verified
---

# Weekday-first date lockup variations

The owner finds the makers date lockup difficult to read and wants Saturday
to come first. They requested alternatives to choose from.
[Request](../../raw/2026-10-19-date-lockup-variations-request.md)

## Observation and interpretation

Before this refinement, the lockup placed a large 27 beside smaller Saturday / March 2027.
The date comes from the edition data and sits above the application action in
the hero's right column. [Screenshot](../../raw/2026-10-19-date-lockup-current.png);
[template](../../../resources/views/audience.blade.php);
[styles](../../../resources/css/makers.css)

The owner requested cream text and the cream divider for this lockup. Its text
uses `--cream`, matching the maker introduction, and its weekday rule uses
`--cream-rule`. This update is scoped to the makers date lockup.
[Request](../../raw/2026-10-19-date-lockup-color-request.md);
[reference screenshot](../../raw/2026-10-19-date-lockup-color-request.png);
[styles](../../../resources/css/makers.css)

The production asset build passed, and the refreshed running makers page was
visually reviewed to confirm the text and divider colors.

The date block's desktop height now matches the first title row; see the
[hero arrangement refinement](28-maker-hero-arrangement.md#date-and-first-title-row-alignment).

**Design interpretation:** the dominant numeral encourages reading 27 before
Saturday, while the split columns make the complete date less direct to assemble.
The proposals use spatial order and typographic emphasis to explore the owner's
preferred sequence. Readability preferences remain to be confirmed by the owner.

## Proposals

The [first-round board](../../../.lavish/date-lockups-round-one.html) uses the project's
existing self-hosted fonts and red/cream palette, shows the supplied screenshot,
and previews each option with the current hero copy. These are design proposals;
the product template and CSS have not been changed.
[Tokens](../../../resources/css/tokens.css); [fonts](../../../resources/css/fonts.css)

| ID | Proposed lockup | Design judgement |
| --- | --- | --- |
| D01 | Saturday / 27 March 2027 in body type | Recommended for a calm, direct top-to-bottom read. |
| D02 | Saturday 27 March 2027 as a sentence | Compact; wraps after the weekday when space requires. |
| D03 | Condensed Saturday / date in body type | Recommended if the weekday should carry more poster character. |
| D04 | Both lines in condensed display type | Cohesive with the headline; less separation between headline and metadata. |
| D05 | Saturday at left, 27 March / 2027 after a fine rule | Editorial reading order; retains a two-column date structure. |
| D06 | Saturday above a rule, then the existing day stamp | Closest to the incumbent; the numeral may still attract attention first. |
| D07 | Saturday / 27 March / 2027 | Quiet narrow stack; separates the year onto its own line. |
| D08 | Cream slip with weekday above the full date | Strong grouping; adds a cream shape close to the CTA. |

Each option retains the full date and allows a desktop/390px phone hero preview.
Selection updates the preview locally; the separate queue control submits one
choice and optional adjustment to Lavish. [Board](../../../.lavish/date-lockups.html)

## D06 selected; subtle refinements

The owner selected D06 as the family and requested subtler variations on it.
[Follow-up](../../raw/2026-10-19-date-lockup-d06-refinements-request.md)
The [active comparison](../../../.lavish/date-lockups.html) now shows six
specimens of that same stamp, including the unchanged starting point:

| ID | Adjustment from D06 | Design judgement |
| --- | --- | --- |
| D06-A | Original D06 as reference | Saturday 22px, full-width rule, semibold 27 at 58px. |
| D06-B | Hide only the rule; preserve spacing | Quieter and more open. |
| D06-C | Set only the numeral in genuine Light 300 | Keeps its scale with less typographic weight. |
| D06-D | Saturday 24px, numeral 52px | Recommended refinement: a modest shift in emphasis towards the weekday. |
| D06-E | Put March and 2027 together beside the numeral | Wider and shorter, with one fewer date line. |
| D06-F | Shorten the rule to 40px | Smaller separator while preserving the original type sizes. |

All keep Saturday above the day/month/year stamp. The hero preview and queue
controls use these stable IDs; the first-round comparison remains available
from the board. The owner subsequently selected D06-D for implementation.
[Review board](../../../.lavish/date-lockups.html)

## D06-D selected and implemented

The owner selected D06-D. The running makers hero now places the 24px semibold
weekday above a full-width 1px rule, followed by the 52px semibold day numeral
beside separate 18px month/year lines. The 10px row gap and 14px day-to-copy
gap match the selected specimen. The complete date still derives from the
edition's `startDate`, with its ISO `datetime` and full accessible date label.
[Selection](../../raw/2026-10-19-date-lockup-d06-d-selection.md);
[template](../../../resources/views/audience.blade.php);
[styles](../../../resources/css/makers.css)

The production asset build succeeded. Running-page checks at 1440, 390 and
320px confirmed the 24px weekday, 52px numeral, 1px rule, weekday-before-day
visual order, complete date text and accessible label. All three widths had
zero horizontal overflow and no browser or asset errors. Desktop and phone
hero captures were visually reviewed.
[Desktop](../../../.lavish/assets/date-lockups/implemented-desktop.png);
[phone](../../../.lavish/assets/date-lockups/implemented-phone.png)

The date concern is now B-025, reassigned from the provisional B-024 because
concurrent documentation allocated B-024 to warm ink and commitment typography.
B-025 is closed by this implementation and verification.

## Review checks and next step

The first-round board was rendered at 1440, 390 and 320px. All eight variants in both preview
modes had no measured horizontal overflow; no browser errors or rendered text
contrast failures were found. A local queue stub confirmed that selection sends
nothing and submission sends exactly one chosen ID with its note.
Visual inspection covered the eight specimens and D03's phone hero.
[Desktop capture](../../../.lavish/assets/date-lockups/desktop-options.png);
[phone capture](../../../.lavish/assets/date-lockups/phone-context.png)

The six D06 specimens were also rendered at 1440, 390 and 320px in both preview
modes, with no measured overflow, text contrast failures or browser errors.
Selection remained local and submitting D06-E sent exactly one ID with its note.
The six-specimen capture was visually inspected.
[D06 desktop capture](../../../.lavish/assets/date-lockups/desktop-options-d06.png);
[D06-D phone capture](../../../.lavish/assets/date-lockups/phone-context-d06.png)

The font aliases were replaced by their documented family names. Small review
labels and the preview introduction use accessible foreground colours. The
artifact's intentionally varied type sizes have an artifact-scoped detector
exception; a remaining static contrast warning was classified against the
rendered contrast check, rather than changing valid specimen colours.
The pre-existing DESIGN.md/sidecar drift is outside this comparison's scope.

Follow-up hook triage retained the established Plus Jakarta Sans family and
recorded an artifact-scoped `overused-font` exception. The `cramped-padding`
flag was also scoped to this artifact after visual review: coloured specimens
have 24–32px insets; flush edges are editorial rules, not enclosing boxes. The
weekday's bottom rule has 9–10px separation. No additional visual change was
needed. [Specimen capture](../../../.lavish/assets/date-lockups/desktop-options-d06.png);
[font authority](../../../resources/css/fonts.css)

B-025 is closed. D06-D supersedes the earlier date's visual reading order;
the broader hero arrangement is recorded in
[the earlier decision](28-maker-hero-arrangement.md).
