---
title: Production rollout and remaining operations
tags: [deployment, production, operations, verification]
sources:
  - "../../raw/2026-10-19-production-rollout-observations.md"
  - "../../raw/2026-10-18-application-provider-handling.md"
  - "20-application-intake.md"
  - "21-application-privacy.md"
  - "31-admin-workspace.md"
phase: Build
updated: 2026-10-19
status: partially verified; email and recoverable backups remain blockers
---

# Production rollout and remaining operations

## Verified on Forge

The production `APP_URL` is now `https://letsmakeamark.brussels`; Laravel's
configuration cache was refreshed. The app reports production mode with debug
off, and all six database migrations have run. The production URL, migration
state, account roles, and direct-origin responses are recorded in the
[rollout observations](../../raw/2026-10-19-production-rollout-observations.md).

The active production database already contains the four named organizer
accounts: Frederik is an active superadmin, and Gwen, Piet, and Bernard are
active admins. Their email fields are populated, but the addresses and their
deliverability still need confirmation with each organizer before password
reset or login email can be relied on. [Admin account setup](31-admin-workspace.md#account-setup)

The Forge site has the production hostname and an active Let's Encrypt
certificate. Requests pinned to the Forge IP served `/`, `/admin/login`, and
`/makers`; `www` redirected. Cloudflare's authoritative zone and a public
resolver point the apex and `www` at Forge. The workstation's default resolver
still returned old one.com/Netlify data during this check, so some networks may
continue to see stale routing until their resolver cache expires. The raw
observations distinguish these paths.

## Still required before accepting and processing real applications

- **Outgoing mail:** Production still uses Laravel's `log` mailer, has no
  Resend API key, and has the example sender address. Configure the Resend key
  and verified `hello@letsmakeamark.brussels` sender in Forge, then test a
  receipt and password reset. The application is designed to send a short
  receipt only after Resend is configured. [Application intake](20-application-intake.md)
- **Incoming mail:** Cloudflare still publishes a null MX. Configure Email
  Routing after choosing a destination mailbox for `hello@`, then verify
  delivery. [Provider handling evidence](../../raw/2026-10-18-application-provider-handling.md)
- **Backups and restore:** Forge reports that automated database backups
  require the Business plan. No Forge backup or restore has been verified.
  DigitalOcean automated backup status is also unknown; configure an available
  off-server backup method and complete a restore check before relying on it.
  The shared Forge server is at 96% disk use, with about 407 MB free, and needs
  space review. [Raw production observations](../../raw/2026-10-19-production-rollout-observations.md)
- **Form and account verification:** Do not send a production test application
  until email is configured and an agreed test can be identified and removed
  safely. Confirm organizer addresses and sign-in/reset delivery, then verify
  both audience forms, application visibility in Filament, and receipt delivery.
  Internal organizer notifications are not implemented. [Application intake](20-application-intake.md)

No production application was submitted and no email was sent during this
check. B-035 remains partly resolved until the mail, backup/restore, and other
release checks are closed.
