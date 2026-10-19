---
title: Production rollout observations
tags: [source, deployment, production]
captured: 2026-10-19
---

# Production rollout observations

Read-only and configuration checks performed against Forge site `makeamark-brussels.on-forge.com` on server `159.223.214.204`, plus the explicitly requested production `APP_URL` update.

## Forge and application

- The release symlink pointed to release `80590578`.
- Before the update, shared `.env` had `APP_URL="https://makeamark-brussels.on-forge.com"`.
- Changed only `APP_URL` to `https://letsmakeamark.brussels`; `php8.4 artisan config:clear` and `config:cache` succeeded. Laravel then reported that production URL, production environment, and `debug=false` were active.
- The configured mail transport was `log`; the Resend key was absent and the sender address was `hello@example.com`.
- All six migrations reported `Ran`.
- The database contained active administrator accounts for Frederik (admin and superadmin), Gwen, Piet, and Bernard (admin). Each had a populated, non-`example.com` email field. Addresses and deliverability were not captured.
- Origin requests pinned to `159.223.214.204` returned HTTP 200 for `/`, `/admin/login`, and `/makers`; `www.letsmakeamark.brussels` returned HTTP 301.

## DNS and mail routing

- Cloudflare authoritative nameservers and `1.1.1.1` returned the apex A record `159.223.214.204` and `www` CNAME to the apex.
- A DNS trace also returned Cloudflare as the delegated nameservers. The workstation's default resolver still returned the previous one.com delegation and a Netlify destination; an ordinary curl through that resolver reached Netlify. This indicates stale resolver data on that path, while the pinned Forge-origin checks and Cloudflare/public-resolver checks reached the Forge setup.
- Cloudflare's authoritative MX response remained the null MX `0 .`.
- The Forge verification CNAME for `_acme-challenge.letsmakeamark.brussels` was present.

## Forge plan and server capacity

- The Forge Backups page showed database backups locked until upgrading the organization to the Business plan. No Forge database backup configuration or restore was verified.
- The Forge server overview reported disk use at 95.22%; a subsequent `df -h /` check showed 96% use with 407 MB available on the 8.7 GB filesystem.
- No production form was submitted and no email was sent.

## Unknowns

- Whether DigitalOcean automated droplet backups are enabled and restorable was not confirmed.
- Whether the four organizer email addresses are correct and can receive sign-in/reset messages was not confirmed.
- Cloudflare Email Routing requires a destination mailbox; none was supplied during this check.
- Resend credentials and a verified sender are not configured.
