---
title: Application spam production rollout
tags: [applications, spam, deployment, verification]
sources:
  - "../../../raw/2026-11-26-application-spam-release-approval.md"
  - "application-spam-account-setup.md"
  - "https://forge.laravel.com/impact-studio/deharmonie/3434375/deployments/84132638"
  - "../../../../config/trustedproxy.php"
  - "../../../../tests/Feature/ClientIpTrustTest.php"
  - "../../../../tests/Feature/ApplicationSpamProtectionTest.php"
phase: Build
updated: 2026-11-26
status: deployed and enabled; live acceptance and rejection verified; observation and live POST replay remain
---

# Application spam production rollout

The owner approved deployment, activation and one marked test application with
a receipt to the agreed mailbox.
[Approval](../../../raw/2026-11-26-application-spam-release-approval.md).

## Release and configuration

Pushed the curated application change `b30a847` and the separate Filament audit
configuration change `2ed3474` to `main`. Forge push-to-deploy released
`2ed3474f02e2243d4590afcee06d4d5445f19f2a` as release `84132638`.
[Deployment](https://forge.laravel.com/impact-studio/deharmonie/3434375/deployments/84132638).

SSH verified the running revision, both production keys present, the exact
canonical allowlist and `TURNSTILE_ENABLED=false` before activation. The public
volunteer HTML already contained the honeypot and signed timing token. Then
changed only the enablement setting to `true` and ran `php artisan config:cache`.
The environment file's modification time records activation at
**2026-11-26 18:35:12 UTC**. A subsequent runtime check confirmed enforcement
and a sitekey matching the configured widget, without printing either key.
[Account setup](application-spam-account-setup.md).

The new explicit proxy configuration passed the same synthetic request through
the deployed Laravel middleware on both canonical and Forge hostnames. Each
retained peer `198.51.100.21` despite forged `X-Forwarded-For: 203.0.113.99`.
Nginx remains responsible for trusted Cloudflare real-IP resolution.
[Configuration and regression tests](application-spam-account-setup.md#release-approval-and-ip-hardening).

Final local verification before release: 297 PHP tests and 2,323 assertions,
11 JavaScript tests, Vite build and `git diff --check` passed. The two new IP
tests failed before the explicit config, then passed. A focused security review
of that configuration and the real-middleware throttle tests found no issue.
[IP tests](../../../../tests/Feature/ClientIpTrustTest.php).

## Live checks

A direct HTTP session fetched genuine form metadata and waited over three
seconds. With otherwise valid volunteer answers and no Turnstile response:

- JSON submission returned 422 with a `submission` verification error.
- Normal submission recovered to the form with retry guidance and the name and
  email still present.
- Public Maker and organisation pages remained closed with their existing
  `Applications open soon` copy and no form metadata.

The browser then submitted the approved marked volunteer test through the
actual Managed widget. No provider stub or test key was used. The site displayed
`Application received` and its receipt-sent message. The admin opened record
**5808**, named `Spam protection verification (test)`, with the agreed mailbox
and explicit notes that organisers must not process it as an application.

SSH confirmed exactly one matching record, created at **18:35:47 UTC**, with
`receipt_sent_at` at the same time. No verification metadata was present in its
answers. Browser back/refresh did not create another record or change that
receipt timestamp. No existing record was deleted or edited.

The receipt was independently found and opened in the agreed Gmail mailbox,
from `hello@letsmakeamark.brussels`, at 19:35 Brussels time. Its body names the
marked test and the volunteer application for 2027. Gmail showed it in Trash
and said delivery followed the mailbox's filters. The check did not change its
labels, restore it or send another message.

The original browser POST was not retained for resending. A fresh form issues a
new identity, so it was not submitted again. Duplicate POST handling remains
covered by automated tests, but an identical live POST replay is still
unverified. [Replay coverage](../../../../tests/Feature/ApplicationSpamProtectionTest.php).

## Initial observation and follow-up

The first aggregate snapshot since activation showed:

```json
{
  "new_applications": {"volunteers": 1},
  "marked_test_records": 1,
  "rejections": {"honeypot": 7, "token": 2}
}
```

The two missing-token rejections were the controlled checks. The seven
honeypot rejections were other traffic; their names and answers were not read
or recorded. No other application had been saved after activation at this
snapshot. This is an initial observation, not evidence of 48-hour effectiveness.

Keep B-049 partly resolved until the observation through
**2026-11-28 18:35:12 UTC** is reviewed. Count new non-test applications,
rejection reasons and connection/provider/configuration errors. Investigate
false rejections or bypasses before tuning. The identical live POST replay
also remains an explicit verification limit. No background monitoring job was
created by this release; follow-up must read the production aggregates again.

The marked test record remains identifiable in admin. Historical spam cleanup
is a separate operation requiring a reviewed candidate list and backup.
