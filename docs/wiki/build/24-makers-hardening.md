---
title: Makers form hardening and Flux controls
tags: [implementation, forms, hardening, flux, makers]
sources:
  - "../../raw/2026-10-18-public-typography-and-organisation-parity-request.md"
  - "../../raw/2026-10-18-makers-hardening-request.md"
  - "../../../resources/views/applications/maker.blade.php"
  - "../../../resources/views/components/application/field.blade.php"
  - "../../../resources/views/components/application/checkbox.blade.php"
  - "../../../resources/css/makers.css"
  - "../../../app/Support/MakerApplicationForm.php"
  - "../../../app/Http/Controllers/ApplicationSubmissionController.php"
  - "../../../tests/Feature/MakerSubmissionHardeningTest.php"
  - "../../../scripts/makers-hardening-check.cjs"
phase: Build
updated: 2026-10-18
status: implemented and verified in the running product
---

# Makers form hardening and Flux controls

The owner requested hardening and proper Flux controls, reporting a visually
broken select. [Request](../../raw/2026-10-18-makers-hardening-request.md)

## Observed causes and controls

The prior language layout kept three columns at 390px, leaving controls about
111px wide with 13px text. The migration also exposed a class collision: the
site's decorative `.block` component applied large section padding to Flux
elements using Tailwind's `.block` utility. The decorative class is now
`.section-block`; existing section appearance is retained. The legacy native
control theme excludes Flux controls so its background shorthand cannot erase
the select chevron.
[Shared CSS](../../../resources/css/app.css);
[makers CSS](../../../resources/css/makers.css)

The maker form uses Flux field, label, input, textarea, native select, checkbox,
error, text, heading and button components. Reusable field/checkbox wrappers
preserve names and requiredness, associate labels/errors, and render safe old
values. Selects use the existing type size (17px), retain chevron clearance and
stack when the available width cannot fit a complete control. An ink chevron
overrides Flux's unlayered pale default. Errors and long text wrap, focus targets
clear the fixed navigation, and the sticky introduction can scroll in a short
desktop viewport.
[Field wrapper](../../../resources/views/components/application/field.blade.php);
[checkbox wrapper](../../../resources/views/components/application/checkbox.blade.php)

Flux checkboxes are JavaScript controls. Required commitments receive client
validation with linked explanations, while Laravel remains authoritative.
Native checkbox fallbacks render inside `noscript` so the form remains usable
without JavaScript, without duplicate named controls in the JavaScript form.
Text inputs, selects, textareas and the button remain native HTML through Flux.
[Maker form](../../../resources/views/applications/maker.blade.php)

## Recovery and replay protection

- Malformed scalar/array answers previously redirected into a rendering error.
  Recovery now flashes bounded, known answer fields, and controls defensively
  normalize old values. All collected business fields remain present.
- Expired-session and rate-limit submissions return to the inline form with
  safe answers and a clear message. CSRF checks and the five-per-minute limit
  remain enforced. A closed edition still refuses storage and explains why.
- Each maker form carries a signed, session-bound nonce. A unique nullable
  database submission key prevents the same form from creating multiple rows,
  including racing requests. Separate forms can use the same email address.
- A replay does not resend mail. Receipt status records actual delivery;
  interruption between storage and mail can still leave a saved application
  without a receipt. This change adds no delivery queue.
- Confirmations are scoped to the matching audience, preventing a maker receipt
  from hiding the organisation form in another tab.
- Theme answers accept at most the ten available, distinct choices. This closes
  the security review's finding that repeated valid themes could inflate a row.

Implementation and evidence:
[helper](../../../app/Support/MakerApplicationForm.php),
[submission controller](../../../app/Http/Controllers/ApplicationSubmissionController.php),
[exception recovery](../../../bootstrap/app.php),
[database migration](../../../database/migrations/2026_10_18_020000_add_maker_submission_replay_protection.php),
[regressions](../../../tests/Feature/MakerSubmissionHardeningTest.php).

The migration was applied to the running local database. Deployment needs
`php artisan migrate` before the hardened form accepts submissions.

## Verification

All 89 PHP tests pass (595 assertions), including actual storage, replay,
malformed-input, expired-session, rate-limit, closed-edition and cross-audience
regressions. Five JavaScript tests, Pint and the production build pass. The
build retains existing self-hosted font URL warnings. Security review found
no critical/high/medium findings; its theme-cardinality finding was fixed and
covered by failing-then-passing regressions.
[Component tests](../../../tests/Feature/MakerFormComponentsTest.php);
[submission tests](../../../tests/Feature/MakerSubmissionHardeningTest.php)

Running Chromium checks cover 1440, 900, 720, 390 and 320px, select text and
chevrons, the preserved field contract, multilingual drafts, native FormData,
keyboard choices, required commitments, inline error focus/links, sending/reset
state, no-JavaScript fallbacks and forced colours. Desktop/mobile screenshots
were inspected in one batch. The homepage section class rename and organisation
styling are checked separately by the same helper. This does not claim a full
screen-reader or multi-browser audit.
[Browser checks](../../../scripts/makers-hardening-check.cjs)

The shared CSS hook still reports pre-existing token/sidecar differences outside
this change. No findings were suppressed. Existing field-audit and mail-provider
operations remain open under B-001/B-002.
