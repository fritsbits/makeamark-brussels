---
title: Applications closed until opening
tags: [applications, edition, operations, implementation]
sources:
  - "../../raw/2026-10-21-applications-closed-decision.md"
  - "46-production-rollout.md"
  - "../strategy/10-tone-of-voice.md"
  - "../../raw/2026-10-21-applications-closed-deployment.md"
  - "../../raw/2026-11-05-production-mail-setup.md"
  - "../../raw/2026-11-26-admin-form-preview-decision.md"
phase: Build
updated: 2026-11-26
status: deployed; opening date unknown
---

# Applications closed until opening

## Decision

The owner closed 2027 applications until an opening date is set.
The edition date stays Saturday 27 March 2027.
[Decision](../../raw/2026-10-21-applications-closed-decision.md)

Before the change, production served both forms with edition status `open`, while mail was disabled and backups were unverified.
[Production rollout](46-production-rollout.md)

## Implementation

- `resources/content/editions/2027.json` uses status `preparing`.
  The submission controller rejects every POST unless the status is `open`.
- The `preparing` label reads "Applications open soon" on the homepage hero, the homepage edition band and both audience pages.
- Audience heroes show the label and a "Follow us on Instagram" pill.
  The solid pill stays reserved for applying.
- The application notice explains that applications open in the coming weeks, links to Instagram and keeps the contact email.
  Other closed states keep the generic notice.
- A form left open in a tab and submitted while `preparing` explains that applications aren't open yet and that nothing was sent.
- Tests pin the edition status in `tests/TestCase.php` through `withEditionStatus()`, so the suite no longer follows the live content file.
- Volunteers are the exception since 2026-11-07: their applications are open while the status is `preparing` and close after the event day ([52 §8](52-volunteer-form-scoping.md#8-build)).

## Admin preview

Logged-in active admins see the Maker and organisation forms while the public still sees them closed, so Gwen can try the forms and the admin before opening.
[Decision](../../raw/2026-11-26-admin-form-preview-decision.md)

- `App\Support\AdminPreview` grants the preview to any user who can open the Filament panel.
  The audience page then renders the form with an "Admin preview" notice under the privacy note.
- The submission controller accepts posts from those admins while the audience is closed.
  Preview applications are ordinary applications with a real receipt; admins delete them in the admin afterwards.
- Preview pages send `Cache-Control: private, no-store`, so no cache serves the open form to the public.
- How to preview: log in at `/admin`, then open `/makers` or `/organisations`.
- `AdminFormPreviewTest` covers guests, inactive and non-admin users, admins, open editions and a guest post while closed.
  All 180 PHP tests pass.
  A local browser run at 1440px and 390px showed the closed page to a guest and the notice and form to an admin, with no browser errors or horizontal overflow.
  Deployed on 2026-11-26: a push to `main` created a Forge release within about 80 seconds.
  In production a guest still sees "Applications open soon" and no form on both pages; the admin view awaits Gwen's login.

## Verification

All 116 PHP tests pass.
A local run at 1440px and 390px showed the homepage, makers and organisations pages without browser errors or horizontal overflow.

## Production

`13d772f` runs in production from a manual release.
Forge received the push webhooks but created no release, so push to deploy needs checking in Forge.
Production held zero applications when intake closed.
[Deployment observation](../../raw/2026-10-21-applications-closed-deployment.md)

## Opening-day checklist

Opening stays a content change plus a push to `main`. Confirm afterwards that Forge actually created a release.

1. Outgoing mail works in production and a test receipt arrives (B-035).
   Done on 2026-11-05: a password reset and a receipt reached the owner's inbox with DKIM, SPF and DMARC passing.
   Incoming mail to `hello@` still waits on Email Routing.
   [Mail setup](46-production-rollout.md#outgoing-mail)
2. An off-server database backup exists and one restore is tested (B-035).
3. Server disk use has room for intake (B-035).
4. Set `status` to `open` in `resources/content/editions/2027.json`, commit and push to `main`.
5. On production, check the homepage "Apply now!" cue and both forms, then send one test application per audience and delete it in the admin.
6. Post the opening on Instagram, since the closed pages point visitors there.
