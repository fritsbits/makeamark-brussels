---
title: Production mail setup confirmations and verification
tags: [source, mail, resend, cloudflare, forge, production]
captured: 2026-11-05
---

# Production mail setup confirmations and verification

## Owner decisions

- Sender: `hello@letsmakeamark.brussels`, display name "Make a Mark Brussels".
- No separate reply-to address; replies go to the sender.
- The domain should receive mail: `hello@` forwards to `makeamarkbxl@gmail.com` through Cloudflare Email Routing.
- A Resend account already existed.
- The owner has no access to `makeamarkbxl@gmail.com`; Gwen must click Cloudflare's destination verification mail.

## Owner confirmations

- Resend domain `letsmakeamark.brussels` was added on 2026-11-05 at 12:47, DNS verified at 12:49 and domain verified at 12:51 (Resend screenshot).
  Region: Ireland (eu-west-1). Provider detected: Cloudflare. "Resend Forge" sending infrastructure: enabled.
- Resend's requested records: TXT `resend._domainkey` (`p=MIGfMA0GCSqG...o6m7BEwIDAQAB`), CNAME `rsend` to `rsend-euw1.forge.rmta.net`, CNAME `send` to `send.forge.rmta.net`, both DNS only, and optional TXT `_dmarc` `v=DMARC1; p=none;`.
- The owner added `_dmarc` in Cloudflare.
- Resend open and click tracking were asked to be off.
- Forge environment now sets `MAIL_MAILER=resend`, `RESEND_API_KEY` (value entered in Forge only), `MAIL_FROM_ADDRESS="hello@letsmakeamark.brussels"` and `MAIL_FROM_NAME="Make a Mark Brussels"`.
  The SMTP-only `MAIL_*` lines remain and are unused.
- `QUEUE_CONNECTION=database` in production.
  The owner created a Forge background process: `queue:work database --queue=default --backoff=10 --sleep=3 --tries=3`, timeout 60, memory 128.
- The Forge deploy script ends with `$RESTART_QUEUES()` after `$ACTIVATE_RELEASE()` and runs `artisan optimize`, so environment changes need a deploy.
- The production environment, including secrets, was pasted into the setup chat once.
  The owner deleted and replaced the Resend API key and regenerated `APP_KEY`.
  Rotating the database password was recommended but not confirmed.
- Cloudflare Email Routing for `hello@` waits on Gwen's destination verification.

## Code observations

- `config/services.php` maps `services.resend.key` to `RESEND_API_KEY`.
- `ApplicationSubmissionController` sends the receipt synchronously only when the default mailer is `resend`, a key exists and the sender is not `hello@example.com`.
- `Filament\Auth\Notifications\ResetPassword` implements `ShouldQueue`, so admin password resets need a running queue worker.
- The application has no `encrypted` casts or `Crypt::` calls, so the `APP_KEY` rotation only invalidated sessions and pending signed links.

## DNS verification

Queried at 2026-11-05 12:29 UTC against `brenda.ns.cloudflare.com` and `8.8.8.8`, identical results:

```
MX    letsmakeamark.brussels             0 .
TXT   letsmakeamark.brussels             (none)
TXT   _dmarc.letsmakeamark.brussels      "v=DMARC1; p=none;"
CNAME send.letsmakeamark.brussels        send.forge.rmta.net.
CNAME rsend.letsmakeamark.brussels       rsend-euw1.forge.rmta.net.
TXT   resend._domainkey.letsmakeamark.brussels  "p=MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQClB207xUmytboRuNZUN...o6m7BEwIDAQAB"
```

## Test sends

### Filament password reset

The owner requested a reset at `/admin/login` for their superadmin account.
Gmail received it on 2026-11-05 at 13:20 CET in the inbox, marked Important.
Header excerpt (reset token omitted):

```
Authentication-Results: mx.google.com;
  dkim=pass header.i=@letsmakeamark.brussels header.s=resend;
  dkim=pass header.i=@amazonses.com;
  spf=pass smtp.mailfrom=...@rsend.letsmakeamark.brussels;
  dmarc=pass (p=NONE sp=NONE dis=NONE) header.from=letsmakeamark.brussels
Return-Path: <...@rsend.letsmakeamark.brussels>
Received: from a3-13.smtp-out.eu-west-1.amazonses.com [54.240.3.13] (TLS1_3)
From: Make a Mark Brussels <hello@letsmakeamark.brussels>
Subject: Reset your password
```

The reset links point directly to `https://letsmakeamark.brussels/admin/password-reset/reset?...` without a tracking redirect.

### Applicant receipt

The owner ran this in Forge Commands, creating no application record:

```
php artisan tinker --execute="\Illuminate\Support\Facades\Mail::mailer('resend')->to('<owner address>')->send(new \App\Mail\ApplicationReceived('Frederik', 'makers', 2027));"
```

Gmail showed it in the inbox at 13:29 CET (screenshot): subject "We received your Make a Mark application", from "Make a Mark Brussels <hello@letsmakeamark.brussels>", body greeting the name, naming the maker application for Make a Mark Brussels 2027, and no application answers.
