---
title: Applications-closed production deployment observation
tags: [source, deployment, applications]
captured: 2026-10-21
---

# Applications-closed production deployment observation

- GitHub delivered push webhooks for `bdcbbf6` and `13d772f` to Forge with HTTP 200, but Forge created no release; production stayed on `82b26ac` (release `80669587`).
- A manual release `releases/manual-20261021170059` was built over SSH from `main` at `13d772f`, mirroring Forge's script: clone, link `.env`, `auth.json` and `storage`, `composer install --no-dev`, `npm ci`, `npm run build`, `artisan optimize`, `storage:link`, `migrate --force` (nothing to migrate), then an atomic `current` symlink swap and PHP-FPM reload.
- After the swap, `https://letsmakeamark.brussels/` served "Applications open soon", `/makers` and `/organisations` served no application form and showed the Instagram follow link, and `/admin/login` returned 200. Desktop and phone captures showed no browser errors or overflow.
- Production held zero applications, so nothing was submitted while the forms were open without mail.
- Server disk use was 40%.
