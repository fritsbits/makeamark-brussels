---
title: Production rollout and remaining operations
tags: [deployment, production, operations, verification]
sources:
  - "../../raw/2026-10-19-production-rollout-observations.md"
  - "../../raw/2026-10-18-application-provider-handling.md"
  - "20-application-intake.md"
  - "21-application-privacy.md"
  - "31-admin-workspace.md"
  - "../../raw/2026-11-05-production-mail-setup.md"
phase: Build
updated: 2026-11-05
status: partially verified; outgoing mail works; incoming mail and recoverable backups remain open
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

## Outgoing mail

Verified on 2026-11-05.
[Confirmations and verification output](../../raw/2026-11-05-production-mail-setup.md)

- Resend sends from `hello@letsmakeamark.brussels` as "Make a Mark Brussels", without a separate reply-to.
  The domain is verified in the Ireland (eu-west-1) region.
- Cloudflare publishes the Resend DKIM TXT at `resend._domainkey`, CNAMEs at `send` and `rsend` (DNS only), and `_dmarc` with `v=DMARC1; p=none;`.
  Cloudflare's nameserver and 8.8.8.8 return the same values.
- Forge sets `MAIL_MAILER=resend`, `RESEND_API_KEY`, `MAIL_FROM_ADDRESS` and `MAIL_FROM_NAME`.
  The code reads `RESEND_API_KEY`, not `RESEND_KEY`.
- Filament password resets are queued (`ResetPassword implements ShouldQueue`) and production uses `QUEUE_CONNECTION=database`.
  A Forge background process now runs `queue:work database` with three tries, and the deploy script restarts it.
- A Filament reset to the owner's admin account reached Gmail's inbox with DKIM, SPF and DMARC passing and aligned on `letsmakeamark.brussels`.
  Its link points directly to the site, so click tracking is off.
- An `ApplicationReceived` mail sent from Forge Commands, without creating an application, reached the inbox with only name, audience and year.
- The production environment was pasted into the setup chat once.
  The owner replaced the Resend key and regenerated `APP_KEY`; rotating the database password is still recommended.
- Interpretation: move DMARC to `p=quarantine` after a few weeks of clean sending.

## Still required before accepting and processing real applications

- **Incoming mail:** Cloudflare still publishes a null MX.
  The owner chose Cloudflare Email Routing from `hello@` to `makeamarkbxl@gmail.com`; it waits on Gwen's destination verification.
  Then the routing MX and SPF records replace the null MX, and a test mail to `hello@` must arrive.
  [Mail setup](../../raw/2026-11-05-production-mail-setup.md)
- **Backups and restore:** Forge reports that automated database backups
  require the Business plan. No Forge backup or restore has been verified.
  DigitalOcean automated backup status is also unknown; configure an available
  off-server backup method and complete a restore check before relying on it.
  The shared Forge server is at 96% disk use, with about 407 MB free, and needs
  space review. [Raw production observations](../../raw/2026-10-19-production-rollout-observations.md)
- **Form and account verification:** Do not send a production test application
  until applications open and an agreed test can be identified and removed
  safely. Confirm the other organizers' addresses and reset delivery, then verify
  both audience forms, application visibility in Filament, and receipt delivery.
  Internal organizer notifications are not implemented. [Application intake](20-application-intake.md)

No production application was submitted and no email was sent during the
2026-10-19 check. The 2026-11-05 mail setup sent two test mails to the owner
only. B-035 remains partly resolved until incoming mail, backup/restore, and
other release checks are closed.
