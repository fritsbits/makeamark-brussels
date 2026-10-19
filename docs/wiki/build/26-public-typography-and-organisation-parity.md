---
title: Public typography and organisation parity
tags: [typography, organisations, forms, hardening]
sources:
  - "../../raw/2026-10-18-public-typography-and-organisation-parity-request.md"
  - "../../../resources/css/public-typography.css"
  - "../../../resources/css/makers.css"
  - "../../../resources/views/audience.blade.php"
  - "../../../resources/views/applications/organisation.blade.php"
  - "../../../scripts/public-type-check.cjs"
  - "../../../scripts/organisations-hardening-check.cjs"
phase: Build
updated: 2026-10-18
status: implemented and verified; B-016 closed
---

# Public typography and organisation parity

The owner approved the makers result, requested the typography on other pages,
and then explicitly requested the same makers-page improvements for
organisations. The supplied screenshot also showed “Application” breaking
before its final letter.
[Owner request and screenshot](../../raw/2026-10-18-public-typography-and-organisation-parity-request.md)

## Shared public typography

The homepage, both audience pages and privacy notice opt into shared typography:
genuine Jakarta Light 300 reading copy at 17px / 1.6, Regular 400 practical
controls, 16px / 600 field labels and 14px / 400 supporting text. Return links
use the Flux arrow + Edition label, accessible name “Back to the edition”,
44px target and visible keyboard focus. Existing copy, palette and display
faces remain authoritative. Public pages preload the Light font; local tools
and admin do not opt into these rules.
[Shared roles](../../../resources/css/public-typography.css);
[layout](../../../resources/views/components/layouts/app.blade.php)

This extends the original makers-only scope of T01 and T03–T06. T02's proposed
extra emphasis remains unselected; existing semantic emphasis is retained.
[Original selection](25-makers-typography-review.md#selected-implementation-receipt)

## Underlying heading defect

At 1440px, the makers heading's `9ch` cap measured 248.77px, while the bare
“Application” glyphs already needed 249.76px before letter spacing. Its parent
had 471.1px available. An inherited `overflow-wrap: anywhere` declaration then
split the final letter. Whole-page overflow checks could not detect this.
The regression checker first failed on the split word before the fix.
[Glyph-range regression check](../../../scripts/public-type-check.cjs)

Application headings now use the available width and wrap at spaces. Emergency
wrapping belongs to reading copy, long links and errors. Their responsive
32–68px range accommodates narrow columns and enlarged text. The organisations
hero's longer “communication” also sizes against its actual title column,
with container units and the established hero-size ceiling, so whole words fit
without colliding with adjacent text.
[Shared styles](../../../resources/css/public-typography.css);
[hero container](../../../resources/views/audience.blade.php)

## Organisation page parity

Both audience pages share the approved red hero, quiet return action, compact
FAQ, sticky application introduction, cream fields, checked-choice treatment,
inset send area and pill submit button. Their commitments and all application
questions remain audience-specific. The previous M02 process redesign remains
unselected.
[Audience patterns](../../../resources/css/makers.css);
[original visual selection](23-makers-polish-review.md#selected-implementation-receipt)

Organisation fields now use the existing Flux wrappers for labels, inputs,
textareas, checkboxes and errors. Required commitments validate before sending;
languages remain a group requiring at least one choice, rather than making
each language mandatory. Keyboard focus, group error descriptions, native
no-JavaScript checkboxes, busy state and back-navigation recovery follow the
makers form. Field names, limits and requiredness are retained.
[Organisation form](../../../resources/views/applications/organisation.blade.php);
[component tests](../../../tests/Feature/OrganisationFormComponentsTest.php)

The shared form backend extends bounded draft recovery, session/audience-bound
signed nonces, atomic replay protection, single-attempt receipts and closed,
expired-session and throttled recovery to organisations. Language arrays are
limited to the three distinct supported choices. The original maker token and
replay-key protocol is retained. Existing database columns support both
audiences; no additional migration is required.
[Shared form support](../../../app/Support/ApplicationForm.php);
[organisation hardening tests](../../../tests/Feature/OrganisationSubmissionHardeningTest.php)

## Verification

All 111 PHP tests pass (861 assertions). The production build and Pint over
changed PHP paths pass. Repository-wide Pint also examined untouched files and
reports existing issues in `bootstrap/providers.php` and `routes/console.php`;
those unrelated files were not changed.

Public typography checks pass at 1440, 1024, 900, 860, 390 and 320px, including
intact heading words, headings fitting their own columns, 200% root text
scaling, font fallback, visible focus and local-tool isolation. The final
typography detector reports no findings. The 32px application-title minimum
is an intentional responsive role recorded in DESIGN.md, addressing narrow
columns and enlarged text rather than an arbitrary type-ramp addition.
[Browser checker](../../../scripts/public-type-check.cjs);
[recorded observations](evidence/public-type-observations.json);
[design roles](../../../DESIGN.md)

Desktop and mobile organisation/application/privacy captures were inspected
in one batch. Application titles retain complete words and the shared form
layout remains readable at both sizes.

Both form browser checks pass at five widths: Flux controls, original field
contracts, keyboard/FormData, required commitments, the organisation language
group, focused inline errors, retained values, busy/duplicate-submit guard,
back-navigation recovery, native no-JavaScript fallbacks and forced colours.
The organisation checker intercepts valid sends; its only Laravel POST uses
an invalid email, so verification creates no real application records.
[Makers checks](../../../scripts/makers-hardening-check.cjs);
[organisation checks](../../../scripts/organisations-hardening-check.cjs)

Independent backend review found no security/regression defects. It caught an
initial language-group description that referenced a hidden client error;
the group now references that error only after validation fails.
[Conditional group description](../../../resources/views/applications/organisation.blade.php)

Operational field and delivery decisions remain B-001 and B-002; this visual
and resilience work does not close them. The older shared replay-protection
migration remains required when deploying the earlier makers hardening change.
