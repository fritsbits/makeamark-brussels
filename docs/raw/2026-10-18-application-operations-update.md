# Application privacy and operations — owner update

- Source: owner message in the Make a Mark Brussels site build thread, 2026-10-18.
- Capture: direct owner clarification of retention intent, hosting, and email setup.

## Retention and future use

Do not automatically delete application data. It may be useful to create proper
accounts later and allow applicants to use their application data or account
for later editions. Those features are future possibilities, not current
functionality. A retention/review period and future reuse rules remain to be
defined.

## Hosting

The app will be hosted on DigitalOcean at a server in Amsterdam and managed
through Laravel Forge. Domain/email services are via one.com.

## Email

The owner needs to set up `hello@letsmakeamark.brussels`. Cloudflare email
routing will be installed. Applicant receipts will be sent via Resend. Internal
Gmail email is for alerts only; it should link to the future admin area and not
include application answers. Admin pages will be scoped after the owner shares
Notion references.
