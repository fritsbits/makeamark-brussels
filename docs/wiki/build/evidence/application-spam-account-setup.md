---
title: Application spam account setup
tags: [applications, spam, cloudflare, deployment, verification]
sources:
  - "../../../raw/2026-11-26-application-spam-setup-request.md"
  - "../../../raw/2026-11-26-application-spam-release-approval.md"
  - "https://dash.cloudflare.com/"
  - "https://forge.laravel.com/impact-studio/deharmonie/3434375"
  - "https://developers.cloudflare.com/turnstile/get-started/widget-management/dashboard/"
  - "https://developers.cloudflare.com/turnstile/additional-configuration/hostname-management/"
phase: Build
updated: 2026-11-26
status: widget and production credentials configured; enforcement disabled pending release
---

# Application spam account setup

The owner opened Cloudflare and asked to continue the spam work.
[Request](../../../raw/2026-11-26-application-spam-setup-request.md).

## Dashboard observations

On 26 November 2026, the signed-in Cloudflare account containing
`letsmakeamark.brussels` had no Turnstile widget. Created one named
`Make a Mark Brussels — application forms` and reopened its settings to verify:

- One hostname, `letsmakeamark.brussels`.
- Managed mode selected.
- Pre-clearance off.
- The dashboard allows 10 hostnames for this widget. It did not show a region
  selector; no China-specific endpoint or option was selected.

The selected account's Billing > Subscriptions listed Workers Free and four
Free domain plans. It listed no paid Turnstile subscription. Widget creation
required no purchase or upgrade. The Turnstile page did not explicitly label
the widget's plan, so these observations do not establish a separate contractual
entitlement. [Account dashboard](https://dash.cloudflare.com/).

Public checks returned 200 from the canonical `/volunteers` page and 301 from
`https://www.letsmakeamark.brussels/volunteers` to the canonical path. Cloudflare
also documents that a root hostname authorizes its subdomains; the application's
server-side allowlist will remain the exact canonical hostname.
[Hostname behavior](https://developers.cloudflare.com/turnstile/additional-configuration/hostname-management/).

## Forge configuration and SSH verification

Stored the widget credentials in the existing Forge site's environment editor:

```dotenv
TURNSTILE_ENABLED=false
TURNSTILE_SITE_KEY=<configured in Forge only>
TURNSTILE_SECRET_KEY=<configured in Forge only>
TURNSTILE_ALLOWED_HOSTNAMES=letsmakeamark.brussels
```

Forge reported `Environment settings updated.` Its config-cache option was on.
The secret was transferred directly between the account dashboards without
printing it or saving it in this repository.
[Forge site](https://forge.laravel.com/impact-studio/deharmonie/3434375).

Forge's command viewer returned a missing output-file error, so verification
used the existing SSH access instead. A PHP check parsed the deployed `.env`
and printed only presence flags and non-secret configuration:

```json
{
  "app_key_present": true,
  "database_present": true,
  "turnstile_enabled": "false",
  "site_key_present": true,
  "secret_key_present": true,
  "allowed_hostnames": "letsmakeamark.brussels",
  "enabled_definition_count": 1,
  "deployed_revision": "4491e93bcb54f16b9fd57bbdc97528c71c85c5ed",
  "turnstile_code_present": false
}
```

From the production server, a Siteverify request using the stored secret and
the deliberately invalid response `invalid-setup-verification-token` returned
HTTP 200, `success: false`, and `error-codes: ["invalid-input-response"]`.
This checks server connectivity and provider rejection with the configured
credential. It does not establish successful browser verification or the new
application controller's production behavior.

DNS resolved the canonical hostname directly to the Forge server's public IP
at this check. Turnstile setup did not change DNS or enable Cloudflare proxying.
The deployed route list has one shared application POST, `/{audience}/apply`,
with `ThrottleRequests:5,1`. The alternate Forge hostname also returns 200 for
`/volunteers`; it does not redirect to the canonical hostname. Local Laravel
code automatically trusts proxies on `.on-forge.com` hosts, whereas the app has
no explicit proxy configuration. This is a release check: verify effective
client IPs and forwarded-header handling on both accessible hosts before
claiming that the rate limit applies per real client.
[Application middleware](../../../../bootstrap/app.php);
[route](../../../../routes/web.php).

## Fresh local verification and release boundary

The existing branch passed `php artisan test` again: 295 tests, 2,311 assertions.
`node --test tests/js/*` passed 11 tests. `npm run build` passed with the existing
unresolved static asset warnings. No application code changed during setup.

Production still runs the revision before the spam protection commit. No
release, enforcement activation, test application, receipt email or record
cleanup occurred during this setup. The release remains subject to the owner
review in [Task 5](../../../superpowers/plans/2026-11-26-application-spam-protection.md#task-5-production-activation-and-observation).
After release, enable verification, refresh configuration, verify acceptance,
unverified rejection and retries, and observe new submissions for 48 hours.
B-049 remains partly resolved.

## Release approval and IP hardening

The owner subsequently approved deployment and activation and the live test
receipt destination. [Approval](../../../raw/2026-11-26-application-spam-release-approval.md).

Read-only SSH inspection found that both public hosts serve the same app.
Nginx's global Cloudflare real-IP configuration trusts only its listed Cloudflare
networks, and FastCGI passes Nginx's resolved address as `REMOTE_ADDR`. Through
the deployed Laravel middleware, a synthetic direct request with a forged
`X-Forwarded-For` retained its peer address on the canonical host, but adopted
the forged address on the Forge host. Laravel's automatic Forge proxy trust
caused the difference.

The release therefore adds an explicit empty `trustedproxy.proxies` config,
which suppresses the framework's Forge fallback and leaves Nginx responsible
for resolving trusted Cloudflare clients. Two regression tests first failed
on the spoofed IP and bypassed throttle, then passed with the configuration.
They exercise the real middleware and rate limiter on both public hostnames.
[Configuration](../../../../config/trustedproxy.php);
[tests](../../../../tests/Feature/ClientIpTrustTest.php).
