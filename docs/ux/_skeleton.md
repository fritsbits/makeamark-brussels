---
title: Make a Mark Brussels website — Skeleton
tags: [ux, skeleton, site-level]
sources:
  - "../raw/2026-10-18-homepage-polish-selection.md"
  - "../raw/2026-10-18-inline-application-request.md"
  - "_strategy.md"
  - "_scope.md"
  - "_structure.md"
  - "../raw/2026-10-18-astro-site.md"
  - "../raw/2026-10-18-airtable-forms.md"
  - "../raw/2026-10-18-structure-feedback.md"
  - "../raw/2026-10-18-skeleton-feedback.md"
  - "../raw/2026-10-18-messaging-selection.md"
phase: Strategy
updated: 2026-10-18
status: owner-selected layout and order; Skeleton complete
---

# Skeleton

## Current edition home — signup-open state

```text
┌─────────────────────────────────────────────────────────────┐
│ BRAND / NAV: Current edition · For makers · For organisations│
├─────────────────────────────────────────────────────────────┤
│ EDITION STATUS: Applications open · edition/date             │
│ HERO: What Make a Mark does for Brussels social organisations │
│       Headline, introduction and current status              │
├─────────────────────────────────────────────────────────────┤
│ TWO AUDIENCE CARDS                                            │
│ Makers: fit + commitment → [Read maker information]           │
│ Organisations: fit + commitment → [Read organisation info]    │
├─────────────────────────────────────────────────────────────┤
│ EDITION DETAILS / key date / how to prepare                   │
├─────────────────────────────────────────────────────────────┤
│ PAST EDITION: one Brussels photo + BRUZZ / Instagram links   │
├─────────────────────────────────────────────────────────────┤
│ ABOUT / CONTACT                                              │
└─────────────────────────────────────────────────────────────┘
```

## Same edition home — closed and after-event states

```text
SIGNUPS CLOSED: clear status + what happens to applicants
  [Maker future interest] [Organisation future interest]

AFTER EVENT: results and work lead; future interest stays optional
  [Maker future interest] [Organisation future interest]
```

The page boundary and state-driven home are owner-selected. The hero leads with
the edition purpose; the two audience cards give both audiences equal actions. Case
studies are deferred until content exists and will each have their own page
later. [Structure feedback; owner selected this hierarchy]

The owner subsequently selected the distilled version and omitted the
homepage process section. Selection/preparation detail and cost answers stay
on audience pages. One past-edition evidence moment replaces the story strip.
[Selection](../raw/2026-10-18-messaging-selection.md);
[implementation](../wiki/build/13-messaging-review.md).

The owner subsequently removed duplicate audience actions from the hero.
The equal cards below it remain the audience choices.
[Polish selection](../raw/2026-10-18-homepage-polish-selection.md).

## Audience information page pattern

```text
NAV / link back to edition
WHO IT'S FOR: audience and the kind of challenge/contribution
WHAT IT OFFERS: what a useful collaboration can make possible
COMMITMENT: time, preparation, assets, and expectations
PROCESS: what happens after applying → selection → event
FAQ: audience-specific questions
APPLICATION: grouped form directly on this page; hero action jumps here
```

Maker and organisation pages use the same pattern, with audience-specific
content. Exact copy and form fields depend on the reviewer-led field audit.
The supplied forms ask different questions and set different commitments.
[Airtable forms capture; owner selected this information order]

The form now sits on the information page, following the owner's request to
reduce the distance between explanation and application. Applying this to both
audiences is an implementation choice.
[Request](../raw/2026-10-18-inline-application-request.md)

## Application form skeleton

The owner selected a single page with grouped sections and a final review for
each application. The reviewer-led field audit determines the final groups and
fields; the supplied forms currently group identity, motivation/mission,
language, work or challenge, preferences/logistics, and commitment checks.
[Airtable forms capture; owner selected this layout]

```text
FORM INTRO: edition / audience / estimated commitment
GROUPED APPLICATION SECTIONS: fields determined after reviewer audit
REVIEW: submitted answers, required items, and any corrections
SUBMIT
CONFIRMATION: receipt + response timeline only if the team can meet it
```
