# Launch security and operations review

Read-only source and local runtime review, 19 October 2026. No credentials
were printed, no real submissions were created and no email was sent.

## Confirmed source behavior

- Applicant receipts require Resend, a configured key and sender. Sending is
  synchronous and only attempted for a newly created application. Failures
  are logged and have no retry path. Source:
  `app/Http/Controllers/ApplicationSubmissionController.php:106–112`.
- Internal new-application alerts remain unimplemented. Source:
  `docs/wiki/build/20-application-intake.md:127–129`.
- Real personal administrator provisioning remains pending in B-009.
- The public privacy notice promises annual review and deletion or
  anonymisation within three years. The documented procedure remains manual
  and needs an owner. Sources: `resources/views/privacy.blade.php:20,42` and
  `docs/wiki/build/01-build-concerns.md` B-002.
- Source guards prevent preview setup and demo seeding outside local/testing.
  Copying an existing local database would bypass those creation guards.

## Local observations, not production conclusions

- Environment local; debug enabled; mail uses the log driver, without a
  Resend key and with a placeholder sender.
- One active administrator, which is the preview administrator; 24 demo
  application records. No personal account addresses or passwords retained
  in this capture.
- Local HTTPS response lacks CSP, HSTS, anti-framing and nosniff headers.
  Session cookies have Secure, HttpOnly and SameSite=Lax. A production proxy
  may add headers; the production host was not examined.

## Checks that passed

Source review found server-side field allowlists and limits, CSRF middleware,
five-per-minute submission throttling, signed session-bound submission nonces,
unique atomic replay keys, role policies, super-admin account-management
checks, hashed passwords, login/reset throttling, restricted applicant-link
protocols and escaped Filament text rendering. No public applicant-data route
or confirmed critical/high exploitable vulnerability was found. This was not
a penetration test.

`composer audit --locked` reported no advisories or abandoned packages.
`npm audit --omit=dev` reported zero production dependency vulnerabilities.

Production environment settings, real mail delivery, real accounts, backup
and restore, and deployment headers remain unverified.
