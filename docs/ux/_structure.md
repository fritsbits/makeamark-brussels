---
title: Make a Mark Brussels website — Structure
tags: [ux, structure, site-level, sitemap]
sources:
  - "../raw/2026-10-18-inline-application-request.md"
  - "_strategy.md"
  - "_scope.md"
  - "../wiki/discovery/10-discovery.md"
  - "../raw/2026-10-18-astro-site.md"
  - "../raw/2026-10-18-airtable-forms.md"
  - "../raw/2026-10-18-scope-feedback.md"
  - "../raw/2026-10-18-structure-feedback.md"
  - "../raw/2026-10-18-messaging-selection.md"
  - "../raw/2026-11-07-volunteer-sitemap-approval.md"
  - "../raw/2026-11-08-solumob-link-approval.md"
  - "../raw/2026-10-19-solumob-build-approval.md"
phase: Strategy
updated: 2026-11-07
status: agreed sitemap; volunteers added 2026-11-07; Solumob case study added 2026-11-08
---

# Structure

## Site map

This is the agreed public sitemap.
New pages are added here and approved by the owner before they are built, as `AGENTS.md` requires.
The owner moved the sitemap's home from Notion to this file on 7 November 2026.
[Approval](../raw/2026-11-07-volunteer-sitemap-approval.md)

```text
Make a Mark Brussels
├── Home (/)
│   ├── Take part: Makers panel, organisations panel
│   │   └── volunteers line under the pair
│   └── About (/#about)
├── For Makers (/makers) + embedded form
├── For organisations (/organisations) + embedded form
├── For volunteers (/volunteers) + embedded form
├── Case study: Solumob (/case-studies/solumob)
│   └── linked from Home (feature block), /makers and /organisations
└── Privacy (/privacy)
```

Header and footer: For Makers, For organisations, For volunteers, About.
The footer adds the global site, Instagram, Privacy and email.
`/{audience}/apply` redirects to the form on each audience page.
Local tools under `/tools` and the Filament admin are outside the public sitemap.
The volunteer change and its reasons are in [volunteer page sitemap](../wiki/build/53-volunteer-sitemap.md).
The Solumob case page was approved on 19 October 2026 (P01, L01 to L03) and is recorded in [the case-study page](../wiki/build/47-solumob-case-study.md).
`/volunteers` has no invitation to it.

## Proposed site map (Strategy, 18 October)

The owner selected one stable phase-aware edition page, separate maker and
organisation information pages with dedicated forms, and a current-edition-only
scope for v1. The sitemap below records that direction; final routes are still
subject to implementation naming.

```text
Make a Mark Brussels
├── Current edition — phase-aware edition hub
│   ├── For makers — what to expect and how to apply
│   │   └── Maker application — embedded section
│   ├── For organisations — fit, challenge, and commitments
│   │   └── Organisation application — embedded section
│   ├── Signups-closed state
│   │   ├── Future interest: makers
│   │   └── Future interest: organisations
│   └── After-event state
│       ├── Results and work
│       └── Future interest: makers and organisations
├── Case studies — later, one page per case study when content exists
├── About — homepage section; process/cost answers on audience pages
└── Contact
```

### Phase behavior in the structure

The owner subsequently requested the form directly on `/makers`. Both audience
pages now include their own form; the old `/apply` GET routes redirect to the
embedded section. The organisation placement is an implementation choice using
the same pattern. [Request](../raw/2026-10-18-inline-application-request.md);
[decision](../wiki/build/20-application-intake.md#forms-on-the-information-pages).

- The edition hub is the stable entry point for the active edition.
- The edition's phase determines which primary action and supporting content
  the hub presents: application routes while open, future-interest routes when
  closed, and results plus future interest after the event.
- Maker and organisation journeys stay distinct because their forms and
  commitments differ. [Airtable forms capture]
- The migration direction makes Laravel the ongoing system of record; this
  sitemap does not imply Airtable routes or a website admin. [Scope feedback]

## Evidence and interpretation

The current site is a one-page layout with navigation, edition information,
maker and organisation sections, stories, FAQ, About, and contact. Its captured
edition-status behavior uses open, closed/selected, and done states, with no
date-based changes. [Astro source capture]

The owner's selected product role is an edition-cycle guide with two co-primary
application routes and after-event results. The phase-aware edition hub and
two separate audience information pages are owner-selected. The current site
remains the source for general About/FAQ/contact content. Case studies are
planned as individual pages later, when material is available. [Structure
feedback]

## Carried questions

- Content reduction is resolved by the selected distilled version: omit the
  homepage process section, combine edition/youth details and keep one evidence
  moment. Page boundaries are unchanged.
  [Selection](../raw/2026-10-18-messaging-selection.md).
- Case studies need their own page type in a later release; no case-study
  content is available for v1.
- Map the migrated Edition status values to signup open, signups closed, and
  after-event before relying on phase-driven navigation.
- Keep the status-to-phase mapping, privacy, and full data-import inventory
  open from Scope. [Scope]
