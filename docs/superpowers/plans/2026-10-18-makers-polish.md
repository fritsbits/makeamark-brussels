# Makers polish implementation plan

> **For agentic workers:** Use native execution for this single shared-template change, then request independent review.

**Goal:** Implement the seven selected Lavish suggestions on `/makers`.

**Architecture:** Add a makers-only page modifier and scoped component rules.
Keep the shared intake state/receipt logic, exposing an introduction slot for
the makers layout. Native form fields and Alpine submission state retain the
existing POST contract and work without JavaScript.

**Tech stack:** Laravel Blade, Flux headings/text, Tailwind component layer,
bundled Alpine, Playwright and PHPUnit. No dependencies added.

**Spec:** [Selection](../../raw/2026-10-18-makers-polish-selection.md),
[visual review](../../wiki/build/23-makers-polish-review.md).

## Constraints

- Implement M01, M03, M04, M05, M06, M07 and M08; leave M02 unchanged.
- Scope styling to makers; preserve organisation layout and intake behavior.
- Preserve every field, required attribute, stored answer and privacy statement.
- Use the existing red, ink and cream tokens, fonts and shapes.

## Review focus

- Invalid submissions retain answers and link summary errors to actual fields.
- A submission disables the button only after native validation passes.
- Back navigation restores the sending state; no-JavaScript forms still submit.
- Sticky introduction stacks on mobile and does not hide fields or receipts.
- Organisation computed styles and field structures remain unchanged.

## Task 1: Selected layout and form polish

Files: `resources/views/audience.blade.php`, `applications/intake.blade.php`,
`applications/maker.blade.php`, and `resources/css/app.css`.

- [x] Extend `ApplicationFlowTest` to assert maker error links target fields;
  run it and observe the missing link failure.
- [x] Add the makers-only page modifier and introduction wrapper; extract the
  shared privacy paragraph once so its wording remains identical.
- [x] Apply selected preview rules, excluding all process-band rules.
- [x] Group short fields, wrap labels, add stable input/error IDs and linked
  summary errors. Use native checkboxes with full-label selected states.
- [x] Add the send panel and Alpine busy/back-navigation state.
- [x] Run PHP tests, production build and focused browser checks at 1440, 900,
  390 and 320px; inspect one desktop/mobile screenshot batch.
- [x] Request independent review; resolve material findings and update the
  wiki with an outcome for every selected ID and verification evidence.
