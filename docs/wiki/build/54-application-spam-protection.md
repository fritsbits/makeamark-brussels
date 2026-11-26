---
title: Application spam protection
tags: [applications, spam, security, plan]
sources:
  - "evidence/application-spam-production-rollout.md"
  - "evidence/application-spam-account-setup.md"
  - "evidence/application-spam-verification.md"
  - "../../../app/Services/TurnstileVerifier.php"
  - "https://www.cloudflare.com/turnstile-privacy-policy/"
  - "../../raw/2026-11-26-application-spam-request.md"
  - "../../superpowers/plans/2026-11-26-application-spam-protection.md"
  - "../../../routes/web.php"
  - "../../../app/Http/Controllers/ApplicationSubmissionController.php"
  - "../../../app/Support/ApplicationForm.php"
  - "../../../app/Support/Audiences.php"
  - "https://developers.cloudflare.com/turnstile/plans/"
  - "https://developers.cloudflare.com/turnstile/get-started/"
  - "https://developers.cloudflare.com/turnstile/get-started/server-side-validation/"
phase: Build
updated: 2026-11-26
status: deployed and enabled; live acceptance and rejection verified; observation and live POST replay remain
---

# Application spam protection

The owner wants the proposed honeypot, Turnstile and targeted field validation,
and requested an execution plan. [Request](../../raw/2026-11-26-application-spam-request.md).
Tracked under B-049. The design below now has an implementation in the isolated
workspace and is now deployed with enforcement enabled. Live volunteer
acceptance, receipt delivery, unverified rejection and answer recovery passed.
Observation and an identical live POST replay remain open.
[Production rollout](evidence/application-spam-production-rollout.md).
[Execution evidence](evidence/application-spam-verification.md).

## Evidence and limits

The supplied screenshot shows gambling promotions in the Volunteers list and
5,692 total results. The screenshot does not establish that every record is
spam, or which requests created them. [Attachment and owner report](../../raw/2026-11-26-application-spam-request.md).

The baseline POST route has `throttle:5,1`. Submission tokens are session-bound
and deduplicate retries, but a fresh form load issues another nonce. The
baseline controller validates the token and audience fields before saving, without a
honeypot or external bot check. Maker and volunteer names accept strings up to
255 characters. [Route](../../../routes/web.php);
[controller](../../../app/Http/Controllers/ApplicationSubmissionController.php);
[tokens](../../../app/Support/ApplicationForm.php);
[audience rules](../../../app/Support/Audiences.php).

Interpretation: a bot that loads forms and maintains a session can create many
distinct applications despite retry protection. Production traffic and the
deployed revision have not been inspected for this request.

## Turnstile feasibility and cost

Cloudflare's Free plan includes up to 20 widgets and unlimited challenges.
One production widget can cover these three forms. Turnstile also works on
sites without Cloudflare proxying, so its adoption does not require a hosting
or DNS move. [Plans](https://developers.cloudflare.com/turnstile/plans/);
[integration](https://developers.cloudflare.com/turnstile/get-started/).

The signed-in account now has one Managed widget for the canonical hostname,
with pre-clearance off and the standard 10-hostname dashboard limit. Billing
listed no paid Turnstile subscription; creation required no purchase. The
dashboard did not show a region selector or an explicit widget-plan label.
[Account observations](evidence/application-spam-account-setup.md#dashboard-observations).

## Approach and scope

Implemented approach: all three measures use the shared application flow
for Makers, organisations and volunteers, using Laravel's existing HTTP and
validation facilities. No new Composer or JavaScript package is needed.

Honeypot alone has fewer dependencies but does not establish that a visitor is
human. Turnstile alone adds a provider dependency and leaves cheap local checks
unused. The combination follows the owner's request. Email verification would
add another applicant step and is outside this change.

Technical choices follow the owner-requested implementation plan:

- Add an empty `website_confirmation` honeypot, excluded from keyboard focus,
  assistive technology and draft storage. Require the field to be present and
  empty on the server. Accept Laravel's normal conversion of an empty string to
  `null`; reject arrays, missing fields and non-empty values.
- Add a separate signed `_form_started` token with session, edition, audience,
  nonce and server issue time. Require an age of at least 3 seconds. Preserve
  the existing replay-token format and lifetime; never trust a browser timestamp.
- Use one Managed Turnstile widget with execution at submission, rather than
  spending its token's lifetime while someone writes a long application. Set
  `action` to `application_<audience>` and verify it with the expected hostname
  and `success` on the server. Cloudflare requires server validation; its tokens
  expire after five minutes. [Validation](https://developers.cloudflare.com/turnstile/get-started/server-side-validation/);
  [widget configuration](https://developers.cloudflare.com/turnstile/get-started/client-side-rendering/widget-configurations/).
- Apply a shared no-link rule to the actual person-name fields. Keep the existing
  255-character limit. Reject `http://`, `https://` and `www.` after trimming,
  without a Latin-only character rule. Cover accented,
  apostrophised, hyphenated, single-part and non-Latin names in tests. Free-text
  answers and portfolio/organisation URL fields keep accepting legitimate links.
- Preserve the current rate limit, eligibility checks, consent, preview rules,
  draft recovery and database replay protection. A valid, already-saved retry
  returns its receipt without another Cloudflare request or email.
- Fail closed on missing required verification, invalid responses, missing keys
  when enabled, or Cloudflare timeouts. Preserve answers, offer retry and the
  existing contact route. Never bypass Turnstile automatically after failure.
- Log aggregate rejection reasons without names, answers, raw IPs, keys or tokens.
  Test no storage and no mail on rejected submissions.

Turnstile requires JavaScript. Add explicit no-JavaScript/blocked-script guidance
and the existing contact route. This changes the earlier no-JavaScript form
submission behavior; automated and running checks must cover that case.

## Delivery and remaining work

Local baseline: 180 PHP tests and the Vite build passed before changes. The
implementation adds a signed three-second timing guard, a present-empty honeypot,
the narrow person-name rule, server verification and a shared browser helper.
Saved authenticated retries return their existing confirmation before spending
a new provider token. Volunteer CSRF/throttle recovery now matches the other
audiences. Verification metadata is excluded from saved and flashed answers.
[Spam tests](../../../tests/Feature/ApplicationSpamProtectionTest.php);
[volunteer recovery tests](../../../tests/Feature/VolunteerApplicationTest.php);
[browser tests](../../../tests/js/application-spam-protection.test.js).

The privacy URL in the plan returned 404 during execution. The current provider
page is the [Turnstile Privacy Addendum](https://www.cloudflare.com/turnstile-privacy-policy/),
checked 26 November 2026. The public notice and assessment now cover Turnstile
separately from DNS and email. Initial implementation stopped at dashboard
sign-in. The later [account setup](evidence/application-spam-account-setup.md)
created the widget and stored credentials in Forge with enforcement disabled.
[Privacy assessment](22-legitimate-interest-assessment.md#application-spam-protection).

Follow the [implementation plan](../../superpowers/plans/2026-11-26-application-spam-protection.md)
for fixtures, server checks, widget integration, privacy wording and rollout.
Use official test keys locally and production keys only in Forge's environment.
Public sitekeys may render in HTML; secret keys must remain server-only.

Complete local tests and one desktop/mobile running-product pass before release.
Check real production hostnames and Cloudflare proxy/IP handling rather than
assuming forwarded headers are trustworthy. Configure and verify the widget,
then activate production enforcement and observe new submissions for 48 hours.

Existing-record cleanup is a separate operation. Prepare a count and reviewable
candidate list and confirm a recoverable backup before proposing any deletion.
This request does not authorize a blanket deletion of the 5,692 results.

B-049 stays open for 48-hour observation and the remaining identical live POST
replay check. The first production checks accepted one marked test, rejected
unverified submissions and preserved answers. A forwarded-IP spoofing regression
was also closed before release. [Results and limits](evidence/application-spam-production-rollout.md).
