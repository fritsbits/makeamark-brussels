---
title: Make a Mark Brussels website — Strategy
tags: [ux, strategy, site-level]
sources:
  - "../wiki/discovery/10-discovery.md"
  - "../wiki/strategy/01-strategy-concerns.md"
  - "../raw/2026-10-18-airtable-forms.md"
  - "../raw/2026-10-18-airtable-data-model.md"
  - "../raw/2026-10-18-laravel-data-migration-direction.md"
  - "../raw/2026-10-18-bruzz-make-a-mark-2023.md"
  - "../raw/2026-10-18-additional-mam-materials.md"
phase: Strategy
updated: 2026-10-18
status: owner directions recorded; ready to guide Scope
---

# Strategy

## Digital mission

Help Brussels social-profit organisations and creative makers understand how to
apply to a Make a Mark edition, submit a useful application, and know what
happens next.

This is a working synthesis of the five directions selected by the owner in
Lavish, not a separately approved tagline.

## Audiences and needs

1. **Social-profit organisations and creative makers are co-primary applicants.**
   The owner chose balanced framing because both apply and the team outcome
   depends on both.
2. **Makers are the person-signup audience for this version.** A separate youth
   signup is outside this scope until the 2027 youth role is defined.
3. **Young people remain important stakeholders** in the 2027 edition, but the
   supplied project brief does not settle their participation route.

The current needs model is provisional. Historical reporting suggests makers
may value local social impact and peer connection, while organisations may
need help turning communication challenges into a focused brief; one maker
described an earlier event as overwhelming. These observations are historical,
not representative current research. Validate the jobs, concerns, and language
with current applicants before treating them as facts. [Discovery synthesis;
BRUZZ report; 2020 Maker Packet]

## Product outcome

The owner selected **review-ready applications across both tracks** as the
candidate North Star. Track maker and organisation counts separately by
edition. The current Airtable capture shows fields and linked records relevant
to applications and matching, but does not define what “review-ready” means;
Scope must define that criterion with the application reviewers. [Airtable data
model capture]

## Website role and lifecycle

The website is an **edition-cycle guide**: it supports signup-open, signups-
closed, and after-event states for an edition. During signup it serves separate
maker and organisation applications. When signups are closed it must explain
what happens to applicants. After the event it should show results and offer
future interest. Signup administration in the website is explicitly later.
The supplied forms and Airtable record model establish two application tracks,
edition relationships, a past organisation future-interest form, and maker
future-interest records; they do not establish current phase switching or
submission behavior. [Owner brief; form capture; data model capture]

## Success and limits

Near-term success is review-ready applications received by the edition deadline,
tracked separately for makers and organisations. Attendance, useful outputs,
and impact matter to the programme but are not attributable to the website
alone. No target values or reviewer definition are yet established.

This version uses the Airtable model as its migration starting point, imports
all Airtable content into Laravel, and retires Airtable as the ongoing system.
A website admin for managing signups is out of scope for the first version.
Consent, retention, edition-phase rules, exact form requiredness, and
submission confirmations remain Scope decisions. [Owner migration direction;
Airtable form capture; Airtable data model capture]

## Decisions recorded

- Person signup: makers only.
- Priority: organisations and makers are co-primary.
- Product role: edition-cycle guide.
- Candidate North Star: review-ready applications across both tracks.
- After-event priority: show results and invite future interest.

## Sources

- [Discovery synthesis](../wiki/discovery/10-discovery.md)
- [Airtable forms](../raw/2026-10-18-airtable-forms.md)
- [Airtable data model](../raw/2026-10-18-airtable-data-model.md)
- [BRUZZ report](../raw/2026-10-18-bruzz-make-a-mark-2023.md)
- [Additional local and international material](../raw/2026-10-18-additional-mam-materials.md)
