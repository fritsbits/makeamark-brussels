---
title: Makers typography review
tags: [typography, review, makers, design]
sources:
  - "../../raw/2026-10-18-makers-typeset-selection.md"
  - "../../raw/2026-10-18-makers-typeset-request.md"
  - "../../../.lavish/makers-type.html"
  - "../../../.lavish/assets/makers-type/observations.json"
  - "../../../.lavish/assets/makers-type/fonts/SOURCE.md"
  - "../../../.lavish/assets/makers-type/type-scan.json"
  - "../../../resources/css/fonts.css"
  - "../../../resources/css/makers.css"
  - "../../../DESIGN.md"
phase: Build
updated: 2026-10-18
status: five selected refinements implemented and verified; B-015 closed
---

# Makers typography review

The owner subsequently [extended these roles to the other public pages](26-public-typography-and-organisation-parity.md),
alongside the whole-word heading correction. Makers-only references below
record the initial selection and verification stage.

The owner requested refinement of body text, a subtler hero back link and a
lighter default weight with more contrast against bold, presented in Lavish.
[Request](../../raw/2026-10-18-makers-typeset-request.md)

The [overview](../../../.lavish/makers-type.html) shows six selectable ideas,
fresh desktop/mobile current/proposed captures, the supplied screenshot and a
live weight specimen. Back-link style and shared-type scope are explicit
choices. The owner selected T01 and T03–T06, makers-only, with arrow + Edition.
Those five changes are now implemented; T02 was not selected.
[Selection](../../raw/2026-10-18-makers-typeset-selection.md)

## Baseline evidence

- Before this change, reading text was Plus Jakarta Sans Regular 400 at 17px / 27.2px.
  Chrome confirms the custom Regular face is actually used. The hero return
  link uses the same size and weight.
- Form labels are 600 at 17px / 25.5px; helper/privacy text is 400 at 13px /
  20.8px. Field answers use 400 at 17px / 23.8px.
- The installed Jakarta files are static 400 and 600. A CSS declaration of 300
  alone would fall back to the existing 400 asset rather than show lighter
  letterforms.

[Browser values](../../../.lavish/assets/makers-type/observations.json);
[captured declarations](../../../.lavish/assets/makers-type/sources/fonts.txt);
[font provenance](../../../.lavish/assets/makers-type/fonts/SOURCE.md)

For a truthful comparison, a static Light 300 WOFF2 was instantiated from the
[official Google Fonts source](https://github.com/google/fonts/tree/main/ofl/plusjakartasans).
It matches the incumbent version (2.071) and vertical metrics. The font, source,
license and checksums are kept inside the review assets. The selected Light
face is now self-hosted under `public/fonts`, alongside its OFL license.
[Source record](../../../.lavish/assets/makers-type/fonts/SOURCE.md)

## Suggestions

These are design judgements, not measured usability outcomes. Preserve the
existing brand faces, palette, wording and hardened Flux application flow.

| ID | Proposal | Recommendation |
| --- | --- | --- |
| T01 | Real Light 300 reading copy at the existing 17px / 1.6; retain Regular 400 for practical UI. | Recommended |
| T02 | Semibold 600 emphasis on one useful existing phrase per paragraph. | Recommended |
| T03 | 16px back arrow with 14px Edition label and 44px target; icon-only alternative available. | Recommended |
| T04 | 16px Semibold labels, 17px Regular field answers and 14px Regular supporting copy. | Recommended |
| T05 | Date/location as 14px Regular metadata. | Recommended |
| T06 | Balanced commitment heading and a 68px application-title ceiling; retain hero/group scales. | Optional |

The specimen isolates 400 versus 300 with the same 600 emphasis. Comparison
images show all six proposals together and are labelled accordingly. Scope
defaults to makers only; the owner can instead select shared public-site type
rules, with page-specific back-link/emphasis/wrapping still tied to makers.
[Review controls](../../../.lavish/makers-type.html#review)

## Verification and limits

Desktop (1440px) and mobile (390px) proposal renders have zero horizontal
overflow. Body/strong compute to 300/600 and the back target is 44px high.
Representative comparisons were inspected in one batch. The overview passes
checks for six ideas, five-item recommendation, font loading, device toggles,
icon choice, scope choice, one structured batch prompt per queue, and no page
errors or horizontal overflow.
[Preview builder](../../../.lavish/assets/makers-type/build-previews.cjs);
[overview checks](../../../.lavish/assets/makers-type/check-overview.cjs)

The independent type assessment found the role/weight refinements. The
mechanical scan reports 13 legacy size advisories, three novelty warnings about
the pinned Jakarta family and four fallback-family/sidecar differences. These
are classified as inherited or contextually intentional here; no detector
waivers or unrelated font replacements were made. Font loading, fallback
coverage and running product-state checks are recorded below for the selected
implementation.
[Scan results](../../../.lavish/assets/makers-type/type-scan.json)

## Selected implementation receipt

The owner selected makers-only scope and arrow + Edition. The five selected
IDs are accounted for below; T02 adds no emphasis phrases. This is a visual
refinement, with no change to copy, palette, fields or submission behavior.
[Owner selection](../../raw/2026-10-18-makers-typeset-selection.md)

| ID | Implemented result |
| --- | --- |
| T01 | Real Jakarta Light 300 for makers reading paragraphs, retaining 17px / 1.6 and Regular 400 controls. The makers route alone preloads the new face; its matching fallback preserves visible text if loading fails. |
| T03 | A decorative 16px Flux back arrow plus 14px Edition label, accessible name “Back to the edition”, 44px target and visible keyboard focus. |
| T04 | Flux field labels at 16px / 600, field answers at 17px / 400, supporting copy and errors at 14px / 400. Choice-tile text retains its existing control styling. |
| T05 | Hero date/location metadata at 14px / 400. |
| T06 | Balanced commitment heading and responsive application heading capped at 68px; existing hero and form-group scales retained. |

[Scoped styles](../../../resources/css/makers.css);
[font declarations](../../../resources/css/fonts.css);
[makers markup](../../../resources/views/audience.blade.php);
[conditional preload](../../../resources/views/components/layouts/app.blade.php)

Production build and all 89 PHP tests (595 assertions) pass. Browser checks at
1440, 900, 390 and 320px verify loaded Light 300, the selected text roles,
heading limits, accessible back navigation and no horizontal overflow. Checks
also pass with 200% root text scaling and a deliberately failed Light-font
request. Homepage and organisation routes retain their previous typography
and request no Light face. Desktop/mobile implementation captures were
inspected together. Independent implementation review found no material
scope or accessibility issues.
[Typography checks](../../../scripts/makers-type-check.cjs);
[observations](../../../.lavish/assets/makers-type/implementation-observations.json)

The existing five-width makers hardening checks still pass: native form data,
keyboard controls, required validation, inline errors, no-JavaScript fallback,
forced colours and other-page regression checks. Pint passes. The build keeps
the existing runtime-resolved self-hosted font URL notices.
[Form checks](../../../scripts/makers-hardening-check.cjs)
