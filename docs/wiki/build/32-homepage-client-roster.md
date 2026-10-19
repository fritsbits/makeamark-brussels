---
title: Homepage past-client roster
tags: [homepage, evidence, client-list]
sources:
  - "../../raw/2026-10-19-add-klimaatzaak.md"
  - "../../../.bloom/attachments/fNPoNP/Pasted 2026-10-19 at 08.52.09.png"
  - "../../raw/2026-10-18-astro-site.md"
  - "../../../resources/content/organisations.json"
  - "../../../resources/views/home.blade.php"
phase: Build
updated: 2026-10-19
status: rendered in the local running product
---

# Homepage past-client roster

The owner identified Klimaatzaak as the client from the previous year and
asked to add it to the past-client list. [Owner instruction](../../raw/2026-10-19-add-klimaatzaak.md)

The existing roster held 55 organisation records. Klimaatzaak is now the 56th.
The roster is a dedicated inset red band after “A feel for the day” and before
About, matching the live site's condensed cream headline, supporting paragraph
and outlined name pills. Its section is explicitly labelled by its heading.
It uses the shared `section-block block--red orgs` pattern. The historical
“social-profits” wording was replaced with the owner-selected “social
organisations” term. [Original site inventory](../../raw/2026-10-18-astro-site.md);
[current data](../../../resources/content/organisations.json);
[homepage](../../../resources/views/home.blade.php)

The local homepage rendered the red roster section, all 56 names and
Klimaatzaak at 1440px and 390px widths. The balanced headline settles into
three lines; the pill list wraps naturally at both sizes. The mobile roster is
long, reflecting all 56 names in the open page. No automated tests were run for
this content change.

## Suggestions for a later pass

- Link names to organisation websites only after verifying their official URLs.
- Add edition-year context for organisations when the participation records
  support it; the pills alone do not show when each group took part.
- If mobile scroll length proves burdensome, try a progressive disclosure that
  keeps the heading and a representative first row visible.
