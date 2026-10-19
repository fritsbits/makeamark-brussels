---
title: Make a Mark Brussels website — Scope
tags: [ux, scope, site-level]
sources:
  - "_strategy.md"
  - "../wiki/discovery/10-discovery.md"
  - "../raw/2026-10-18-airtable-forms.md"
  - "../raw/2026-10-18-airtable-data-model.md"
  - "../raw/2026-10-18-laravel-data-migration-direction.md"
  - "../raw/2026-10-18-scope-feedback.md"
phase: Scope
updated: 2026-10-18
status: owner choices recorded; data dependencies carried forward
---

# Scope

## Strategic constraints

Scope follows the owner-selected Strategy: makers and organisations are
co-primary applicants; the site guides an edition through signup open, signups
closed, and after-event; the candidate North Star is review-ready applications
across both tracks; after-event should show results and invite future interest.
Signup administration in the website is later. See [Strategy](./_strategy.md).

## Known application material

The screenshots show distinct maker and organisation application forms. The
maker form asks about identity, motivation, languages, work and skills,
preferences, and availability/commitment. The organisation form asks about
eligibility, identity, mission, challenge, contact, languages, and participation
commitments. The screenshots do not establish current requiredness, validation,
or form-to-table mapping. [Airtable forms capture]

The inspected base has Editions, Makers, Organisations, Teams, and Screenshots;
maker/organisation records link to edition/team relationships, with separate
future-interest views visible for makers and a past organisation interest
form. The owner confirmed Airtable should be retired and all its content
imported into the Laravel site. Working interpretation: all records and their
field data and relationships across these five tables; verify whether attached
media, complete historical values, or saved-view behavior are included during
the migration inventory. Laravel becomes the ongoing source of truth and the
two signup forms write to Laravel. [Owner migration direction; Airtable data
model capture; Airtable forms capture]

## Confirmed scope decisions

- **Data destination:** Laravel is the new system of record. Airtable is a
  migration source only; no ongoing Airtable writes or manual reconciliation.
  [Owner migration direction]
- **Application fields:** audit each applicant-facing field with reviewers
  before finalizing visibility and requiredness, while preserving the existing
  model. [Owner selection in Lavish]
- **Closed phase:** offer separate opt-in forms for makers and organisations
  to hear about future editions. [Owner selection in Lavish]
- **Edition phase source:** use the migrated Edition status field.
  [Owner selection in Lavish]
- **Submission confirmation:** confirm receipt and state a response timeline
  only when the team can meet it. [Owner selection in Lavish]

## Open scope choices

1. Which fields are required or collected later after the reviewer audit.
2. How the migrated Edition status values map to the three public phases. The
   inspected values (“In progress” and “Done”) do not distinguish all three.
3. Future-contact consent wording, privacy notice, and retention boundary.
4. Exact import inventory, including attachment completeness and historical
   values, once a full Airtable export is available.

The Laravel-only destination, full-content import, applicant-field review,
future-interest routes, phase-source field, and careful receipt confirmation
are owner-selected. Detailed field rules, exact status mapping, privacy, and
migration completeness remain dependencies for later validation.
