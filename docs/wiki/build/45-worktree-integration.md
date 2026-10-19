---
title: Worktree integration into main
tags: [integration, verification, build]
sources:
  - "../../raw/2026-10-19-worktree-integration-request.md"
  - "../../raw/2026-10-19-worktree-merge-tests.json"
  - "../../raw/2026-10-19-main-integration-verification.json"
  - "../../../tests/Feature/EditionWebsiteTest.php"
  - "../../../tests/Feature/MessagingPreviewTest.php"
  - "43-launch-audit.md"
phase: Build
updated: 2026-10-19
status: local main integration verified; production checks remain
---

# Worktree integration into main

The owner requested all work from `fritsbits/setup-cascade-digital-product`
on local `main` so the Bloom worktree can close. The integration includes the
Laravel site, application intake, Filament administration, design reviews,
Cascade documentation, original design assets and Solumob research.
[Request](../../raw/2026-10-19-worktree-integration-request.md).

## Verification and the stale copy tests

The initial source check reproduced the four copy/markup failures recorded
as L-07 in the [launch audit](43-launch-audit.md). Assertions in
`EditionWebsiteTest` and `MessagingPreviewTest` still expected earlier
headlines, the longer footer city link, removed payment FAQs and the previous
homepage status markup. Their replacements check the approved titles,
pro-bono commitment, free organisation participation, current footer link and
accessible application action. Product behavior is unchanged.
[Updated homepage and audience checks](../../../tests/Feature/EditionWebsiteTest.php),
[preview checks](../../../tests/Feature/MessagingPreviewTest.php).

The full PHP suite now passes **115 tests and 887 assertions**. `npm run build`
also passes, with the existing root-relative public-asset URL warnings.
The [saved test output](../../raw/2026-10-19-worktree-merge-tests.json) records
the source-tree result. L-07 is resolved; the other release concerns in B-035
retain their existing status.

## Local files and historical evidence

`/.bloom/` is ignored so downloaded speech models, the Python environment and
workspace scratch files do not enter Git. Before closure, the original
attachments and working analysis are preserved in the main checkout's local
`.bloom` directory. Local environment settings, the SQLite database, private
admin-preview files and installed dependencies are also preserved outside Git.

The two documents unique to the old `main` scaffold remain part of the
integration: the original public-site capture and its historical synthesis.
The current worktree's later phase plans and concern registers remain
authoritative. The preserved earlier synthesis must not be mistaken for the
current edition plan.

## Integration verification

The merged main checkout passes all 115 PHP tests, 887 assertions, five
JavaScript tests and the production build. A running Chromium check passes
the four public routes at 1440, 390 and 320px, the mobile menu, embedded forms
and admin login. It records no overflow, browser errors or failed local
resource responses and submits no applications.
[Merged-checkout verification](../../raw/2026-10-19-main-integration-verification.json).

The existing `makeamark-southern-ocean.test` Herd hostname now resolves to
`/Users/frederikvincx/Documents/makeamark`. Both original historical documents
from main are preserved; the earlier public-site synthesis is explicitly
historical. The main checkout contains the worktree's source and local files
before archive is requested. Production deployment and its operational
release checks remain outside this local integration request.
