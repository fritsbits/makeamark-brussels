# Application spam protection implementation plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking. Keep implementation in this existing isolated workspace; request one security review before release.

**Execution status (26 November 2026):** Tasks 1–4 implemented and verified; 297 PHP tests, 11 JavaScript tests and the build pass. Owner approved release. Production enforcement is enabled; a real volunteer application and receipt, unverified rejection, answer recovery and both-host IP handling passed. The 48-hour observation and identical live POST replay remain. See [production rollout](../../wiki/build/evidence/application-spam-production-rollout.md).

**Goal:** Reject automated spam on all three application forms while preserving legitimate applications, answers and retry receipts.

**Architecture:** Keep the shared Laravel submission controller and existing replay identity. Add local honeypot/timing checks, a narrow name rule and server-verified Turnstile, with a shared browser helper for all three forms. Use existing Laravel HTTP/validation and Alpine; add no dependencies.

**Tech stack:** Existing PHP 8.4/Laravel, Blade, Flux, bundled Alpine, Vite, PHPUnit and Playwright.

**Spec:** [Technical design and delivery record](../../wiki/build/54-application-spam-protection.md). Owner selected the three measures, approved the release and agreed the marked live test. Production activation and remaining observation are recorded in the evidence.

## Global constraints

- Cover Makers, organisations and volunteers, including authenticated admin previews.
- Preserve the existing replay-token format and lifetime.
- Require an age of at least 3 seconds for the separate signed timing token.
- Keep the existing 255-character limit on person-name fields.
- No new Composer or JavaScript package is needed.
- Keep secrets outside Git and browser output. Do not read or print `.env` values during discovery.
- Retain current field validation, consent, status gates, per-tab identity and rate limiting.
- Reject unverified new submissions before database writes or receipts. An authenticated retry identity for an existing submission may return its existing receipt.
- Do not auto-delete old applications or change edition/application-open settings.
- Product text stays English. Use Flux for ordinary controls and named CSS classes for cosmetic styles.
- Planning is complete when this document and wiki references are saved; product completion requires running-product and production verification during execution.

## Review focus

- Long forms and validation retries need a fresh Turnstile token without losing answers.
- Double clicks, two tabs and an already-saved retry must not duplicate records or email.
- Missing scripts, provider timeouts or missing keys must leave an actionable retry/contact route.
- Name checks must accept Unicode, single names and ordinary punctuation.
- Volunteers need the same CSRF/throttle recovery as the other audiences.

## Task 1: Local spam guard and person-name rule

**Files:**
- Create `app/Support/ApplicationSpamProtection.php` and `app/Rules/NoLinksInPersonName.php`.
- Modify `app/Support/ApplicationForm.php`, `app/Support/Audiences.php`, `app/Http/Controllers/AudiencePageController.php`, `app/Http/Controllers/ApplicationSubmissionController.php`.
- Create `resources/views/components/application/spam-protection.blade.php`; include it in `resources/views/applications/maker.blade.php`, `organisation.blade.php`, `volunteer.blade.php`.
- Create `tests/Feature/ApplicationSpamProtectionTest.php` and a shared helper `tests/Concerns/SubmitsApplicationForms.php`.
- Update POST fixtures in `tests/Feature/ApplicationFlowTest.php`, `MakerSubmissionHardeningTest.php`, `OrganisationSubmissionHardeningTest.php`, `VolunteerApplicationTest.php`, `AdminFormPreviewTest.php`, `PrintViewsTest.php`, `SharedThemesTest.php`.

**Interfaces:**
- `ApplicationSpamProtection::issue(Request $request, string $edition, string $audience, string $submissionToken): string` returns signed `_form_started` metadata.
- `ApplicationSpamProtection::validateLocal(Request $request, string $edition, string $audience, string $submissionToken): void` throws a validation error under `submission` on failure.
- `NoLinksInPersonName` implements Laravel `ValidationRule`; apply only to Maker/volunteer `name` and organisation `contact_name`.
- View data adds `spamStartedToken`; hidden fields are `_form_started` and `website_confirmation`.

- [x] Install locked dependencies if absent with `composer install` and `npm ci`, using existing Composer authentication. Record baseline `php artisan test` and `npm run build` results before changing behavior.
- [x] Add failing tests for each audience: missing/array/numeric/non-empty honeypot; missing, tampered or cross-session/audience/edition/submission timing token; future timing and ages 0, 2 and 3 seconds. At 3 seconds a legitimate application with a present empty honeypot must save once, including after Laravel converts its empty string to `null`. Failed checks assert no application and no mail.
- [x] Add name-rule tests: reject `https://casino.example`, uppercase `HTTP://`, `www.example.com`; allow `José`, `O'Neill`, `Jean-Luc`, `李明` and `Prince`. Organisation URLs and portfolio/answer links remain valid. Keep the existing length test.
- [x] Run `php artisan test --filter=ApplicationSpamProtectionTest`; confirm failures are the missing protection, not dependency or fixture errors.
- [x] Implement HMAC-signed, server-issued timing metadata bound to the existing submission token and its request context. Use `now()` for testable time. Validate types and signature before reading claims. The existing submission token and submission-key derivation do not change.
- [x] Add the honeypot with `tabindex="-1"`, `aria-hidden="true"`, autocomplete disabled and hiding that excludes it from assistive navigation. Check key presence separately from its value; accept only `''` or `null` after Laravel's empty-string normalization, and reject arrays or other values. Exclude both bot metadata and Turnstile responses from answers, draft storage and flashed old input; deliberately reissue valid timing metadata on recovery.
- [x] Implement the narrow name check with case-insensitive detection of `http://`, `https://` or `www.` after trimming. Use the existing string/required/max rules, without spelling, alphabet or surname heuristics. Error: `Please enter a person's name without a link.`
- [x] Add a fixture helper that fetches real form metadata and advances Carbon by 3 seconds, rather than bypassing local validation. Update immediate-POST tests using it. Add assertions that metadata never persists in `answers`.
- [x] Run the new tests and the seven affected feature classes. Verify old replay-token compatibility and separate-tab tests still pass.
- [x] Make a local checkpoint with explicit paths. Example subject: `feat(applications): add local spam checks`. Follow the repository's body bullets and `Why:` convention.

## Task 2: Turnstile server verification

**Files:**
- Create `app/Services/TurnstileVerifier.php`.
- Modify `config/services.php`, `.env.example`, `app/Http/Controllers/ApplicationSubmissionController.php`.
- Extend `tests/Feature/ApplicationSpamProtectionTest.php` and the shared fixture helper.

**Interfaces:**
- `TurnstileVerifier::verify(string $token, string $audience): void` validates a new application or throws a `submission` validation error.
- `services.turnstile`: `enabled`, `site_key`, `secret_key`, `allowed_hostnames`.
- Environment names: `TURNSTILE_ENABLED=false`, `TURNSTILE_SITE_KEY=`, `TURNSTILE_SECRET_KEY=`, `TURNSTILE_ALLOWED_HOSTNAMES=`. Blank placeholders only in Git. Explicit enablement controls rollout; enabled with missing configuration rejects new submissions.
- Expected action is `application_<audience>`. Browser response field is `cf-turnstile-response`.

- [x] Add failing tests using `Http::fake()` and `Http::preventStrayRequests()`: missing/array/over-2048-character tokens, `success=false`, malformed JSON, wrong hostname/action, non-2xx, timeout and missing enabled configuration all reject with no save/mail. Successful matching verification accepts each audience.
- [x] Add regression tests that field/local failures never call Cloudflare; valid saved retries return existing confirmation without a fresh verification, updated answers or another receipt. Distinct fresh submissions still require verification. Parallel saves remain protected by the existing unique database submission key.
- [x] Run `php artisan test --filter=ApplicationSpamProtectionTest` and confirm the new cases fail for missing verification.
- [x] Implement a form-encoded POST to `https://challenges.cloudflare.com/turnstile/v0/siteverify` with the server secret and browser token, 3-second connection timeout and 5-second total timeout. Do not send applicant answers or optional `remoteip`; do not retry automatically. Require boolean `success === true` and exact allowlisted hostname and expected action. Do not trust the request's Host header as the allowlist.
- [x] Controller order: validate current status/session-bound submission identity; return existing saved identity when applicable; run local and field checks; verify Turnstile; run existing atomic save/mail flow. Preserve database deduplication even though external verification has its own token lifecycle.
- [x] Use `We couldn't verify your submission. Please try again.` for verification failures and `Verification is temporarily unavailable. Please try again or contact us.` for configuration/provider failures. Keep answers through existing bounded recovery. Log reason codes only, with no form payload, raw IP, secret or token.
- [x] Configure the test suite to enable verification with placeholder settings and fake the provider's success response by default for legitimate fixtures. Negative tests override the fake. Explicitly cover disabled rollout mode; never silently disable in production because keys are missing.
- [x] Run the affected feature tests and verify that the secret cannot appear in rendered HTML.
- [x] Commit explicit Task 2 paths with subject `feat(applications): verify Turnstile before saving`.

## Task 3: Browser verification and recovery

**Files:**
- Create `resources/js/application-spam-protection.js`; import through `resources/js/app.js`.
- Modify the shared spam-protection Blade component and the three application Blade forms.
- Modify `bootstrap/app.php` for volunteer recovery; `resources/css/app.css` only if named shared styles are needed.
- Create `tests/js/application-spam-protection.test.js` using the existing JavaScript test conventions.
- Extend volunteer feature tests; update `scripts/makers-hardening-check.cjs`, `scripts/organisations-hardening-check.cjs` and add `scripts/application-spam-check.cjs`.

**Interfaces:**
- Shared module exports `verifyApplicationForm(form): Promise<void>`; it loads Turnstile once, renders one widget per form and writes the token into that form. Successful resolution allows exactly one native submit; rejection clears the submission lock and shows retry guidance.
- Render Managed mode explicitly with `execution: 'execute'`, action from the form's known audience and responsive widget size. Execute only after native and existing Flux/checkbox validation succeeds.
- Error UI uses a shared focusable `submission` error region, without a broken field anchor.

- [x] Write failing JavaScript tests with a stubbed Turnstile API for script loading once, double-click prevention, isolated forms, delayed API readiness, success, expiry, error and timeout. Verify error paths release the lock and preserve input values; a retry gets a fresh token and submits once.
- [x] Add volunteer 419/429 tests matching the existing Maker/organisation recovery semantics, including answers and `Retry-After`.
- [x] Run the new JS test through the project's existing Node test command and `php artisan test --filter=VolunteerApplicationTest`; confirm the missing behavior fails.
- [x] Implement the module and connect it to each existing Alpine submit handler. Keep checkbox checks and `pageshow` reset. Avoid recursive submit events; preserve the submitter with `requestSubmit` plus a one-shot verified state or an equivalent tested native-submit path. Never unlock while the final POST is in flight.
- [x] Load the Cloudflare script only where an actual application form renders, including admin preview. Use a visible retryable error if loading takes over 15 seconds or the provider reports failure. No silent bypass on blocked JavaScript.
- [x] Add a `noscript` notice: `This form needs JavaScript for spam protection. Enable JavaScript or contact us to apply.` Link the existing help route. Document this change from the earlier no-JavaScript submission behavior. Verify keyboard/screen-reader status and use compact widget sizing where 300px cannot fit at 320px viewport width.
- [x] Extend the recoverable audience set in `bootstrap/app.php` to include volunteers; keep safe input normalization and existing redirect fragments.
- [x] Run the JS tests, volunteer recovery tests and affected form feature tests. Update browser helpers to stub Turnstile or use official test keys and to keep intercepting successful production-like POSTs where the script previously did so. Keep assertions for 97-character replay tokens.
- [x] Commit explicit Task 3 paths with subject `feat(applications): add retryable browser bot verification`.

## Task 4: Account setup, privacy and local verification

**Files:**
- Modify `resources/views/privacy.blade.php`, `docs/wiki/build/21-application-privacy.md`, `docs/wiki/build/22-legitimate-interest-assessment.md` and the existing privacy feature test found during implementation.
- Update `docs/wiki/build/54-application-spam-protection.md`, `00-build-plan.md`, `01-build-concerns.md`, `docs/wiki/index.md`, `docs/wiki/log.md` with actual evidence.

- [x] In the owner's Cloudflare dashboard, select the correct account and open Turnstile. Check subscription/entitlement without inferring it from a separate domain's website plan. Billing listed no paid Turnstile subscription; the widget was created without purchase and exposes 10 hostname slots. No explicit widget-plan label was shown. [Account evidence](../../wiki/build/evidence/application-spam-account-setup.md).
- [x] Create one production widget for `letsmakeamark.brussels`; rechecked that `www` redirects. Managed mode and no pre-clearance verified. The current dashboard exposed no region selector; no China option was selected. Store its sitekey and secret in Forge only, with `TURNSTILE_ENABLED=false` until release. Local configuration retains official test keys. [Account evidence](../../wiki/build/evidence/application-spam-account-setup.md).
- [x] Add a privacy disclosure naming Cloudflare Turnstile, its spam-prevention purpose and the relevant current provider privacy information. Check the Turnstile privacy addendum and update the assessment rather than treating the existing DNS/mail disclosure as sufficient. Test rendered wording. Source: [Cloudflare privacy addendum](https://www.cloudflare.com/turnstile-privacy-addendum/).
- [x] Run `php artisan test`, the full existing JS test command and `npm run build`. Capture commands and results; do not claim passing tests if dependencies or credentials prevent them.
- [x] Run one batched desktop/mobile browser pass on the running local app, using the project screenshot helper and HTTPS error handling. Cover all audiences, keyboard flow, 320px and 390px widths, genuine local save/admin record, error recovery, waiting over 5 minutes before submission, provider failure, JavaScript disabled and retry after field validation. Disable/capture mail locally. Preserve screenshots/evidence once after the batch.
- [x] Request one focused security review of token binding, hostname/action enforcement, replay handling, secret exposure and failure paths. Resolve material findings and rerun only affected checks.
- [x] Record verified local behavior and remaining production dependencies in B-049. Commit documentation and privacy changes with explicit paths.

## Task 5: Production activation and observation

**Interfaces:** release contains Tasks 1 to 4; production keys and allowlist are configured; deployment authority is checked at execution time. This planning request does not by itself perform a release.

- [x] Verify deployed revision, public hostnames, proxy trust and client IP handling. Found and fixed Laravel's automatic Forge-host proxy trust with explicit `trustedproxy.proxies=[]`; regression and deployed middleware checks pass on both hosts. One shared throttled application POST remains. [Rollout](../../wiki/build/evidence/application-spam-production-rollout.md).
- [x] Review the concrete release and local evidence with the owner at the project's deployment boundary. Owner approved deployment and activation, plus a marked live test and receipt to the agreed mailbox. [Approval](../../raw/2026-11-26-application-spam-release-approval.md).
- [x] Collapse this unit's own unpublished checkpoints into one curated commit before shared-branch integration, after checking authorship with `git log <upstream>..HEAD --format='%an'`. Never rewrite someone else's or already-pushed commits. Stage explicit paths and use the required body bullets plus `Why:` line.
- [x] Release with Turnstile explicitly disabled first and verify local protection markup, then enable the configured production keys and canonical allowlist and refresh Laravel config cache. Runtime sitekey matches the widget and a real Managed verification passed. [Rollout](../../wiki/build/evidence/application-spam-production-rollout.md).
- [x] Verify one agreed marked application, admin record 5808 and delivered receipt. Direct unverified POST returned 422; normal recovery preserved answers. Browser back/refresh kept one record and one receipt timestamp. Public Maker/organisation opening status was unchanged. [Rollout](../../wiki/build/evidence/application-spam-production-rollout.md).
- [ ] Verify an identical live POST replay if the original request can be retained safely. Do not use a fresh form identity or extract session secrets. Automated saved-retry/one-receipt/provider-call tests pass; the original live POST was not retained.
- [ ] Observe aggregate rejection reasons, provider errors and new spam for 48 hours. Compare new submissions rather than the old list's total count. Record the result; tune only when evidence identifies bypasses or false rejection.
- [ ] If Turnstile prevents legitimate applications, keep local checks active while restoring verified service or explicitly rolling back its enablement under operator control. Never turn off verification automatically on an error. Log the operational decision and keep B-049 open.
- [ ] Close B-049 only after production acceptance/rejection/retry checks and the observation result are recorded. Separately prepare a reviewable old-spam candidate list and backup/restore check if the owner requests cleanup; do not delete records in this plan.

## Sources for the executor

- [Free limits and standalone use](https://developers.cloudflare.com/turnstile/plans/)
- [Dashboard widget setup](https://developers.cloudflare.com/turnstile/get-started/widget-management/dashboard/)
- [Server verification and token lifecycle](https://developers.cloudflare.com/turnstile/get-started/server-side-validation/)
- [Execution, callbacks and sizes](https://developers.cloudflare.com/turnstile/get-started/client-side-rendering/widget-configurations/)
- [Official testing keys](https://developers.cloudflare.com/turnstile/troubleshooting/testing/)

Public documentation checked 26 November 2026. Account observations, configured keys, activation and initial production traffic are recorded in the linked evidence. Observation through 28 November and identical live POST replay remain open.
