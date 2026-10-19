---
title: Application intake
tags: [implementation, applications, build]
sources:
  - "../../raw/2026-10-19-application-payoff-request.md"
  - "../../raw/2026-10-18-makers-hardening-request.md"
  - "../../raw/2026-10-18-inline-application-request.md"
  - "../../raw/2026-10-18-admin-build-instruction.md"
  - "../../raw/2026-10-18-admin-review-request.md"
  - "../../raw/2026-10-18-airtable-forms.md"
  - "../../raw/2026-10-18-airtable-data-model.md"
  - "../../raw/2026-10-18-laravel-data-migration-direction.md"
  - "../../raw/2026-10-18-application-privacy-requirements.md"
  - "../../raw/2026-10-18-application-operations-update.md"
  - "../../ux/_scope.md"
phase: Build
updated: 2026-10-19
status: implementation built; 2027 submissions open; privacy notice implemented; mail and retention operations remain
---

# Application intake

The maker and organisation information pages render grouped forms when the
current edition status is `open`. The form fields follow the groups visible in
the captured Airtable screenshots: maker identity, motivation, languages,
work, preferences, and practical checks; organisation identity, mission,
challenge, contact, languages, and participation commitments. The screenshots
show some prior required fields, but they do not establish current validation
or requiredness. [Airtable forms capture]

Each POST route validates its audience's answers and stores them in the Laravel
`applications` table with the audience and edition year. This follows the
owner's decision for Laravel to become the ongoing system of record. [Migration
direction; Scope]

The form renders and accepts submissions while the edition record is `open`;
the owner opened the 2027 edition on 2026-10-18. The selected rule is an annual
review and deletion or anonymisation no later than three years after an
applicant's latest application; no automated deletion runs. Future account
functionality needs a separate purpose review. The public [privacy notice](/privacy)
is linked beside the forms. Confirmation email uses
Laravel's Resend mailer when the API key and a verified sender are configured.
Until then, submission is saved and the page explains that email receipts are
unavailable. No response timeline is promised. [Owner decisions; Application
privacy and retention]

## Forms on the information pages

The owner requested the maker form directly on `/makers`, beside the information
that explains applying. [Owner request](../../raw/2026-10-18-inline-application-request.md)

Implementation choice: use the same pattern for both audiences. `/makers` and
`/organisations` include their existing form after the commitment and cost
explanations. The hero action jumps to `#application`; old GET `/apply` links
redirect there. POST routes, field rules, throttling, storage and receipt logic
remain in place. Both validation failures (with entered answers) and successful
receipts return to that audience's `#application` section. The collection notice
and edition-status gate remain beside the form.
[Audience view](../../../resources/views/audience.blade.php);
[submission controller](../../../app/Http/Controllers/ApplicationSubmissionController.php)

Verification: 70 PHP tests and five JavaScript tests pass, as does the production
build (with existing self-hosted font URL warnings). Feature tests cover both
audiences' saved submissions, confirmation, error/answer preservation and legacy
redirects. Running desktop (1440px) and mobile (390px) checks verify embedded
forms, anchor navigation, legacy links, maker validation and retained answers;
both layouts have zero horizontal overflow and no browser errors. Screenshots
of the maker form were inspected at both widths.
[Feature coverage](../../../tests/Feature/ApplicationFlowTest.php);
[browser checks](../../../scripts/screenshot.cjs)

The design hook reported existing token/sidecar differences in the shared CSS.
These are outside the two added inline-section rules, which reuse the existing
heading scale and colors; no design-system migration or suppression was made.

## Application payoff

The owner requested a more delightful confirmation and a fix for the blue
border around the application section. [Request and screenshot](../../raw/2026-10-19-application-payoff-request.md)

Implementation choice: both audiences receive a red-and-cream receipt, a
brief stamped-check entrance and audience-specific thanks. The form title
and collection notice give way to the receipt after submission. The receipt
confirms the edition, explains review without promising selection or a date,
and provides an organising-team email link and a return to the edition.
Actual receipt delivery still controls the email message; the fallback says
no email was sent and applying again is unnecessary.
[Receipt view](../../../resources/views/applications/intake.blade.php);
[audience wrapper](../../../resources/views/audience.blade.php)

The blue border was the browser's native outline on the fragment-focused
`#application[tabindex="-1"]` section. Only this reading region's outline is
removed; fragment focus and visible keyboard rings on links and controls
remain. The stamp runs once for 420ms and is static under reduced motion.
[Component styles](../../../resources/css/app.css)

Verification: production build and all 39 application PHP tests pass (418
assertions). Real local POSTs verified both audiences at desktop (1440px)
and phone (390px) widths, plus reduced motion and JavaScript disabled.
Checks covered receipt rendering, absent form/sidebar, zero horizontal
overflow, retained section focus and visible keyboard focus on the help link.
Desktop organisation and mobile maker captures were inspected at
`/tmp/makeamark-payoff/`; all seven temporary application records were
removed afterwards. A focused code review found no blocking issues.
Mail setup remains B-002; B-019 is closed.

Design-hook review: the earlier literal-value warnings concern pre-existing
Riso tool styles outside this change; these were left unchanged. The receipt
uses the existing palette, display face and component radii, with a responsive
display size and circular check stamp specific to this completion moment.
No hook suppressions or sidecar refresh were made; the reported newer
`DESIGN.md`/older sidecar state predates this task.

## Current implementation limits

The subsequent [makers hardening](24-makers-hardening.md) replaces its native
control styling with Flux components, adds safe draft recovery and a signed form
nonce backed by a unique database submission key. Maker replays save once and
do not resend receipts; confirmations are now audience-specific. Theme answers
are limited to ten distinct available choices. The local migration is applied;
deployment must migrate before the hardened maker form accepts submissions.

- The Filament admin now provides organisation review/briefing refinement,
  maker selection and team matching over these Laravel applications. Four real
  organising-team accounts await their email addresses; a local preview login
  is available. [Admin workspace](31-admin-workspace.md)
- Resend credentials and a verified sender address are not configured in this
  workspace. Gmail alerts are intended to contain a notification/link only;
  the authenticated admin destination now exists, but alerts remain unimplemented. [Owner
  decisions]
- The owner removed the maker dietary-requirements field. [Owner decisions]
- The privacy basis and retention rule are recorded in the public notice.
  Provider account settings, verified sender configuration, and exact mailbox
  destination remain to be confirmed before production email. The owner has
  supplied Airtable review/selection screenshots and then instructed the admin
  build. The [workspace record](31-admin-workspace.md) links its Notion sitemap,
  implementation choices and remaining operations.
  [Application privacy and retention]
