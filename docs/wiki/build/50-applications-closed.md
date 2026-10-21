---
title: Applications closed until opening
tags: [applications, edition, operations, implementation]
sources:
  - "../../raw/2026-10-21-applications-closed-decision.md"
  - "46-production-rollout.md"
  - "../strategy/10-tone-of-voice.md"
phase: Build
updated: 2026-10-21
status: implemented; opening date unknown
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

## Verification

All 116 PHP tests pass.
A local run at 1440px and 390px showed the homepage, makers and organisations pages without browser errors or horizontal overflow.

## Opening-day checklist

Opening stays a content change plus a push to `main`, which Forge deploys.

1. Outgoing mail works in production and a test receipt arrives (B-035).
2. An off-server database backup exists and one restore is tested (B-035).
3. Server disk use has room for intake (B-035).
4. Set `status` to `open` in `resources/content/editions/2027.json`, commit and push to `main`.
5. On production, check the homepage "Apply now!" cue and both forms, then send one test application per audience and delete it in the admin.
6. Post the opening on Instagram, since the closed pages point visitors there.
