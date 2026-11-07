---
title: Volunteer page sitemap
tags: [volunteers, sitemap, navigation, structure, gate]
sources:
  - "../../raw/2026-11-07-volunteer-sitemap-request.md"
  - "../../raw/2026-11-07-volunteer-sitemap-approval.md"
  - "../../raw/2026-11-05-volunteer-decisions.md"
  - "52-volunteer-form-scoping.md"
  - "../../ux/_structure.md"
  - "../../../.lavish/volunteers-sitemap.html"
phase: Build
updated: 2026-11-07
status: approved by the owner on 2026-11-07 (A1, B1, C1, D1); wireframe next
---

# Volunteer page sitemap

Stage 1 of building the volunteer form (G-10, B-047): where `/volunteers` sits in the site.
`AGENTS.md` asks for an agreed sitemap before a new page is built.
The owner approved this sitemap on 7 November 2026.
[Approval](../../raw/2026-11-07-volunteer-sitemap-approval.md)
The owner asked to keep it in this wiki instead of Notion, and made that the rule for later pages (D1).
[Request](../../raw/2026-11-07-volunteer-sitemap-request.md)
The full site sitemap is in [`docs/ux/_structure.md`](../../ux/_structure.md#site-map); this page records the volunteer change and its reasons.
The review page with mocks and screenshots of the site as it was is [`.lavish/volunteers-sitemap.html`](../../../.lavish/volunteers-sitemap.html).

## Approved sitemap change

```text
Make a Mark Brussels
├── Home (/)
│   ├── Take part: Makers panel, organisations panel
│   │   └── volunteers line under the pair (new, A1)
│   └── About (/#about)
├── For Makers (/makers) + embedded form
├── For organisations (/organisations) + embedded form
├── For volunteers (/volunteers) + embedded form (new)
│   └── /volunteers/apply → redirects to /volunteers#application
└── Privacy (/privacy)
```

## The page

| | |
| --- | --- |
| URL | `/volunteers`, form at `#application`, decided by V-01 ([decisions](../../raw/2026-11-05-volunteer-decisions.md)) |
| Title | For volunteers · Make a Mark Brussels |
| Description | Photograph, film or run social media for the Make a Mark Brussels 2027 edition. |
| Purpose | Find a photographer, a videographer and a social media manager for the day; explain the roles, the commitment and the difference from Makers; host the form. |
| Closed state | As on the other audience pages: status `preparing` shows "Applications open soon" and the Instagram link ([50](50-applications-closed.md)). |
| Links out | Privacy notice, Makers page (C1), Instagram, contact email. |
| XML sitemap | Add `/volunteers` to `/sitemap-0.xml` (`routes/web.php:48`). |

## Decisions

| ID | Question | Decision | Effect |
| --- | --- | --- | --- |
| A | Homepage entry | A1: a line under the Makers and organisations pair | The pair stays the two co-primary routes ([structure](../../ux/_structure.md)); one full-width line with a "Volunteer details" pill below it. The Apply now scribble ([42](42-homepage-apply-now-scribble-options.md)) is rechecked in the wireframe. |
| B | Header and footer | B1: "For volunteers" as the fourth header link, before About | The footer loops the same list (`resources/views/sections/footer.blade.php:10-12`). The owner's condition: links must not wrap over several lines and must read as distinct items, with enough space between them. |
| C | Cross-links | C1: from `/volunteers` to `/makers` only | One line on `/volunteers`: "Want to work on a challenge in a team? Apply as a Maker." `/makers` is unchanged. |
| D | Where sitemaps live | D1: in `docs/ux/_structure.md` | `AGENTS.md` changed in the same commit; Notion is no longer the sitemap's home. |

The owner also answered one of the questions for Gwen: volunteers don't pick themes.
[Approval](../../raw/2026-11-07-volunteer-sitemap-approval.md)

## Checks for the wireframe

From the owner's note on B1, the header is checked at 1440px, around 1024px and 900px, and in the 390px menu.
In the review page's mock the four links wrapped and ran together; that mock was narrower than the real header, but the owner asked for the real one to be checked.

## Where Gwen's answers would change this

- If the brackets around photographer and videographer mean those roles are filled, or Nena is confirmed, the page mostly recruits a social media manager; the description, homepage line and role list would narrow.
- Minors and insurance: no structural change; possibly one line on the page.
