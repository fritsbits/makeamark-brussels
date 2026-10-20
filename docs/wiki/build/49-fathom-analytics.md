---
title: Fathom analytics
tags: [analytics, privacy, implementation]
sources:
  - "../../raw/2026-10-20-fathom-analytics-request.md"
  - "../../raw/2026-10-20-fathom-production-deployment.md"
  - "https://usefathom.com/docs/start/policy"
  - "https://usefathom.com/legal/dpa"
  - "https://usefathom.com/docs/start/dpa"
phase: Build
updated: 2026-10-20
status: deployed; account agreement and lawful-basis review remain
---

# Fathom analytics

The production public pages load the owner-provided Fathom script with site ID
`UDIIHURW`. The shared Blade layout gates it on production and the public-site
typography flag, so local environments, internal previews, the risoprint tool,
and admin pages do not load the analytics script.

The public privacy notice now explains the analytics purpose, the request data
Fathom processes, and its stated EU isolation. Fathom's policy guidance says
its embed uses no tracking cookies and briefly processes IP address and
user-agent data to create site-specific signatures and produce statistics.
Its DPA describes temporary processing and EU-side anonymisation of EU visitor
IP addresses before they reach US-controlled infrastructure. [Fathom sample
privacy wording](https://usefathom.com/docs/start/policy); [DPA](https://usefathom.com/legal/dpa)

## Remaining owner action

Fathom recommends that GDPR-subject customers sign its DPA. The controller
must also confirm the lawful basis for the website-improvement purpose and
retain the balancing assessment. Until those are confirmed, B-039 remains
partly resolved. [Fathom DPA guidance](https://usefathom.com/docs/start/dpa)

Forge deployed commit `2a64388`. The production homepage response contains the
configured script and site ID; the public privacy response contains the
analytics disclosure. The Fathom dashboard itself has not been checked. The
GitHub Actions run failed before tests because the workflow selects PHP 8.3
while the lockfile requires PHP 8.4.1 or newer for Symfony 8.1 packages.
[Production observation](../../raw/2026-10-20-fathom-production-deployment.md)
