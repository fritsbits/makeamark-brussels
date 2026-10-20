---
title: Fathom production deployment observation
tags: [source, analytics, deployment]
captured: 2026-10-20
---

# Fathom production deployment observation

- Laravel Forge showed deployment commit `2a64388` for
  `fritsbits/makeamark-brussels:main` as successful via Push to deploy.
- An HTTPS request to `https://letsmakeamark.brussels/`, pinned to the Forge
  origin `159.223.214.204`, returned the Fathom script tag with site ID
  `UDIIHURW`.
- The production `/privacy` response contained the Website analytics and Fathom
  disclosure.
- GitHub Actions run `39395829286` failed during Composer installation before
  tests. Its workflow selects PHP 8.3, while the lockfile includes Symfony 8.1
  packages that require PHP 8.4.1 or newer.
