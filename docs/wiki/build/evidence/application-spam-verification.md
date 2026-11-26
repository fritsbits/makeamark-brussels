---
title: Application spam verification
tags: [applications, spam, verification]
sources:
  - "application-spam-production-rollout.md"
  - "application-spam-account-setup.md"
  - "../../../../tests/Feature/ApplicationSpamProtectionTest.php"
  - "../../../../tests/js/application-spam-protection.test.js"
  - "../../../../scripts/application-spam-check.cjs"
phase: Build
updated: 2026-11-26
status: locally verified; production activated; follow-up recorded separately
---

# Application spam verification

## Baseline and automated checks

Execution on 26 November 2026 in the isolated workspace:

- Locked dependencies installed successfully. No new package was added.
- Baseline `php artisan test`: 180 passed, 1,445 assertions.
- Baseline `npm run build`: passed.
- Local guard tests: 63 passed. With the honeypot/timing guard temporarily
  removed, missing-honeypot tests failed for all three audiences.
- Server verification tests: 106 passed. Before verification was implemented,
  40 new assertions failed on unverified acceptance or missing provider calls.
- Affected feature classes after browser integration: 199 passed,
  1,683 assertions. Volunteer 419/429 recovery tests failed before the audience
  was included in the exception handler.
- `node --test tests/js/*`: 11 passed, including script readiness/timeout,
  isolated forms, single native submission, callbacks and fresh retries.
- Privacy rendering test failed before the notice was added.

The first local test run encountered missing view metadata in the fixture.
After correcting that dependency, the guard-removal check above confirmed that
the rejection tests catch omitted protection. These are local test observations,
not evidence of live provider behavior.

## Running product

The browser check uses a new isolated SQLite file, generated local admin
fixtures, array mail and public official test keys. Closed public audiences are
tested through authenticated admin preview. The local harness fakes only the
external provider boundary; production code still verifies every fresh token.
The browser uses `ignoreHTTPSErrors: true`. Existing application data and edition
settings are unchanged.
[Check script](../../../../scripts/application-spam-check.cjs).

Final automated checks: `php artisan test` passed 295 tests with 2,311
assertions after freezing the timing fixture before form rendering.
`node --test tests/js/*` passed 11 tests; `npm run build` passed.
`git diff --check` passed. The privacy rendering check passes as part of the
full PHP suite. An earlier full run passed 288/289; its single failure was the
timing-fixture clock drift, now corrected and covered by the green full run.

Focused security review: no Critical/Important issue. The reviewer independently
found the same low-priority clock-fixture issue; it was corrected before the
final suite. The review traced token binding, configuration failures, exact
hostname/action, saved retry identity, unique-key races and the one-time mail
gate, metadata/log exclusions, browser locks and all-audience recovery.
Live widget settings, actual managed challenges, proxy/IP configuration and
production concurrency remain operational checks, not conclusions from stubs.

A direct Siteverify request with Cloudflare's official public test secret and
dummy response returned `success: true`, `hostname: example.com` and test-key
metadata. This confirms local access to the endpoint; it does not establish
production widget configuration or action matching.
[Official test-key documentation](https://developers.cloudflare.com/turnstile/troubleshooting/testing/).

The running browser matrix passed all nine audience/viewport combinations at
1440px, 390px and 320px. Each checked replay-token length, honeypot visibility,
keyboard exclusion, focused failure guidance, retained answers, fresh retry,
submission locking and zero horizontal overflow. Desktop submissions saved
through Laravel and were opened in admin. Maker field validation preserved
answers and allowed another verified attempt. Mail was disabled. The local
provider API and script were stubbed; actual managed challenge appearance is
still unverified. Screenshots are retained at `/tmp/makeamark-spam-check/`.

The first matrix run then stopped at a harness selector: Playwright's text
locator excludes `noscript`, although the notice renders. The corrected check
inspects the rendered paragraph directly. The separate recovery run passed: the notice/contact link was visible, a
direct unverified submission was rejected, and a form held open for 301 seconds
requested verification only at submission and saved successfully.
[Machine-readable results](application-spam-browser-results.json). Matrix output is retained at
`/tmp/makeamark-browser-matrix.log`; final recovery output is in
`/tmp/makeamark-browser-recovery.log`.

Historical audience-hardening helpers now stub Turnstile and distinguish
server errors from the shared verification region. The dedicated spam check
owns this change's browser matrix; their unrelated historic snapshot assertions
were not rerun. All three browser scripts pass Node syntax checks.

## Production dependencies

The later [production rollout](application-spam-production-rollout.md) supersedes
the activation dependencies below. Enforcement is live; the remaining limits
are observation and an identical live POST replay. The text below records the
pre-release state of this verification pass.

Initial implementation stopped at Cloudflare sign-in. The subsequent
[account setup](application-spam-account-setup.md) created the Managed widget,
checked billing and stored credentials and the canonical allowlist in Forge.
Enforcement remains disabled; production release and live form behavior are
unverified. No deployment, production application or record deletion occurred
as part of this spam-protection work.

Public HTTP checks on 26 November 2026 returned 200 from
`https://letsmakeamark.brussels/` and 301 from the `www` root and
`www.letsmakeamark.brussels/volunteers` to the matching canonical host/path.
This supports a production widget for the canonical hostname only; recheck at
activation. The local route audit found one shared application POST route with
the existing `throttle:5,1`; effective production proxy/client-IP behavior still
needs operator verification. [Route](../../../../routes/web.php).

Before release: review the concrete change with the owner, check current public
hosts and proxy/IP handling, configure Managed mode with Rest of World and no
pre-clearance, and store production credentials only in Forge. Verify a controlled
application and a direct unverified rejection, then observe aggregate reasons,
provider errors and new spam for 48 hours. Keep B-049 open until these checks
are recorded. [Plan](../../../superpowers/plans/2026-11-26-application-spam-protection.md).
