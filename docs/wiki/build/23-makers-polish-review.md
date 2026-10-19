---
title: Makers page design suggestions
tags: [design, polish, makers, review]
sources:
  - "../../raw/2026-10-18-makers-polish-selection.md"
  - "../../raw/2026-10-18-makers-polish-request.md"
  - "../../../.lavish/makers-polish.html"
  - "../../../.lavish/assets/makers-polish/observations.json"
  - "../../../resources/views/audience.blade.php"
  - "../../../resources/views/applications/maker.blade.php"
  - "../../../resources/css/app.css"
  - "../../../DESIGN.md"
  - "20-application-intake.md"
phase: Build
updated: 2026-10-18
status: M01 and M03–M08 selected, implemented and browser verified
---

# Makers page design suggestions

The owner later [extended the approved makers treatment to organisations](26-public-typography-and-organisation-parity.md).
The makers-only references below record the original selection scope.

The later [hardening pass](24-makers-hardening.md) keeps the selected visual
direction while replacing its controls with Flux and fixing narrow selects,
error recovery and replay handling. The original Lavish previews remain the
record of the selection stage.

The owner requested polish, a bolder appearance and more colour, presented as
suggestions in Lavish. The [overview](../../../.lavish/makers-polish.html) contains
eight selectable proposals, current/proposed screenshots at desktop and mobile
widths, and standalone snapshots of the current and combined proposed page.
The overview preserves the proposal stage. The owner subsequently selected
M01 and M03–M08; these seven changes are now implemented on `/makers`.
M02 was unselected and remains unchanged.
[Selection](../../raw/2026-10-18-makers-polish-selection.md)
[Request](../../raw/2026-10-18-makers-polish-request.md)

## Observations

At 1440px the current form measures 760px wide and its first field is 620px.
The back link has a 96px bottom margin on desktop and 48px on mobile. The send
button has square corners. Current label grids place required asterisks on a
separate line. These are browser measurements and source observations, not
usability-test findings.
[Measurements](../../../.lavish/assets/makers-polish/observations.json);
[form source snapshot](../../../.lavish/assets/makers-polish/sources/maker.txt);
[CSS snapshot](../../../.lavish/assets/makers-polish/sources/css.txt)

## Proposed choices

Benefits and recommendations below are design interpretations.

| ID | Suggestion | Recommendation |
| --- | --- | --- |
| M01 | Red poster opening with a cream headline and deliberate date/action grouping. | Recommended |
| M02 | Darker-cream process band with red step numerals. | Optional |
| M03 | Compact pay question and volunteer answer. | Recommended |
| M04 | Sticky desktop application introduction beside the grouped form; stacked on mobile. | Recommended |
| M05 | Inline required markers and responsive rows for short answers. | Recommended |
| M06 | Defined field boundaries, existing corner radius, clear focus and short textareas. | Recommended |
| M07 | Full-label choice targets with red selected states and native checkboxes. | Optional |
| M08 | Separate send panel, pill button, and finished sending/error states. | Recommended |

The combined preview demonstrates all eight, including checked theme and date
controls as examples. Preview selections are not proposed defaults. Some crops
show only the start of a form group. The full snapshot allows native typing and
selection, and intercepts form submission to prevent sending an application.
[Overview and previews](../../../.lavish/makers-polish.html)

The project design system supplies the palette, typography and shapes. No new
brand colour, factual claim, field or application step is proposed. Selection
controls explicitly scope implementation to `/makers`; the shared organisation
template must not cause accidental visual changes on `/organisations`. Existing
inline intake, requiredness, privacy, validation and receipts remain constraints.
[Design snapshot](../../../.lavish/assets/makers-polish/sources/design.txt);
[intake decision](20-application-intake.md#forms-on-the-information-pages)

## Verification

### Selected implementation receipt

| ID | Outcome and evidence |
| --- | --- |
| M01 | Addressed: red poster hero, cream headline, grouped date/action and responsive columns; hero text aligns with the centered copy columns below. |
| M03 | Addressed: pay question/answer form a compact desktop row and stack on mobile. |
| M04 | Addressed: the application introduction is sticky above 860px and stacks normally below; form, privacy and receipt remain inline. |
| M05 | Addressed: small red required markers stay within labels; name/email share a desktop row; all three language selectors remain correctly nested and responsive. |
| M06 | Addressed: cream-light fields have darker-cream boundaries (`--cream-rule`), 10px corners and ink focus; short textareas are shorter and error text uses ink. Form-group divider rules use the same darker cream. |
| M07 | Addressed: full-label checkbox targets with darker-cream borders, red selected states, visible ticks and keyboard focus. No choices are preselected. |
| M08 | Addressed: inset send panel, pill button, validated sending state, pageshow recovery, focused error summary with working field links and inline required-field errors. |

The running page passes checks at 1440, 900, 390 and 320px with zero horizontal
overflow. Tests compare every maker field name/type/requiredness/maximum length
and language option against the preserved current-page snapshot. Keyboard
checkbox operation, native validation, retained invalid answers, summary focus
and links, sending/reset state and a no-JavaScript form pass. Local submission
state checks cancel navigation to avoid saving a browser fixture; PHP feature
tests separately exercise actual persistence and receipts for both audiences.
Organisation hero/button computed styles and the unselected process treatment
remain unchanged. Desktop/mobile screenshots were inspected in one batch.
[Browser checks](../../../scripts/makers-polish-check.cjs);
[feature tests](../../../tests/Feature/ApplicationFlowTest.php)

All 70 PHP tests pass (429 assertions), as do Pint and the production build.
The build retains existing self-hosted font URL warnings. Independent review
found no material correctness, accessibility or scope issues. The new scoped
stylesheet passes the design hook; the shared CSS still reports pre-existing
token/sidecar differences outside this change, which were not suppressed.
[Makers components](../../../resources/css/makers.css)

### Proposal verification

Current/proposed page renders have zero horizontal overflow at 1440px and 390px.
Desktop/mobile crops were generated in one batch and a representative contact
sheet was inspected. The overview passes browser checks for eight choices,
the recommended six, device toggles, and one structured batch prompt per queue.
Local snapshots load their copied stylesheet/fonts, retain the form and have
zero overflow at both widths. Full product-state and accessibility verification
is required when the selected proposals are implemented.
[Preview builder](../../../.lavish/assets/makers-polish/build-previews.cjs);
[overview checks](../../../.lavish/assets/makers-polish/check-overview.cjs)
