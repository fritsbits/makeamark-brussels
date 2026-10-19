---
title: Discovery synthesis
tags: [synthesis, discovery]
sources:
  - "../../raw/2026-10-18-astro-site.md"
  - "../../raw/2026-10-18-notion-make-a-mark-2027.md"
  - "../../raw/2026-10-18-airtable-forms.md"
  - "../../raw/2026-10-18-airtable-data-model.md"
  - "../../raw/2026-10-18-bruzz-make-a-mark-2023.md"
  - "../../raw/2026-10-18-additional-mam-materials.md"
  - "../../raw/2026-10-18-previous-website-copy.md"
  - "https://app.notion.com/p/334d3ecc475c81719bdcc15d82bd5b23"
phase: Discovery
updated: 2026-10-18
---

# Discovery synthesis

## What is known

The owner later supplied a five-page PDF of previous website content. It refers to the 2025 edition, with separate maker and organisation invitations, useful creative work, peer collaboration, practical commitments, and conversational FAQs. The export date does not establish the website publication date. Its older details and mixed application-open/closed wording are historical evidence, not current operations. See the [preserved source](../../raw/2026-10-18-previous-website-copy.md) and [messaging analysis](../strategy/12-previous-website-messaging.md). This is additional copy evidence, not direct current audience research.

The existing site presents Make a Mark Brussels as a make-a-thon where
creatives volunteer design work for Brussels social-profits. Its one homepage
includes edition information, maker guidance, 55 organisation names, four FAQs,
and organiser contact links. These are observations of the existing site's
copy, not independent verification of its claims. See the [source
capture](../../raw/2026-10-18-astro-site.md).

The owner requested a Laravel conversion using the supplied [setup
playbook](https://app.notion.com/p/334d3ecc475c81719bdcc15d82bd5b23).
The [migration record](20-laravel-foundation.md) documents the technical scope
and checks. Interpretation: preserving the one-pager is the current task;
new product scope remains outside this conversion.

The 2027 project page describes Make a Mark Brussels as an annual one-day
make-a-thon where volunteer makers help Brussels social organisations with
communication, design, and digital challenges, with usable results for
participating organisations. It identifies the upcoming event as the ninth
edition, scheduled for 27 March 2027, with an umbrella focus on Brussels youth
and organisations serving people approximately 12–25. These are statements in
the project source, not independently verified facts. See the [source
capture](../../raw/2026-10-18-notion-make-a-mark-2027.md).

The project scope spans participant and organisation selection, intake and
team formation, event logistics, funding, communications, and follow-up. This
partly clarifies the product lifecycle and owner's goals. The project page
calls for young people's involvement in preparation and feedback; the meeting
summary discusses possible roles but does not settle the level or format.
Interpretation: the 2027 edition's audience focus is clearer, but there is no
direct research here about young people's needs or journeys.

The owner wants the next website version to support signups for people and
organisations, starting from the existing Airtable model. They identified
three experience phases: signup open, signups closed, and after the event;
signup management in a website admin is a later goal. The supplied screenshots
show a detailed maker application, a detailed organisation application, and a
short organisation contact form inviting people to hear when applications
reopen. They document visible questions and content, but not the Airtable
schema, submission behavior, or consent rules. See the [form capture](../../raw/2026-10-18-airtable-forms.md).

The read-only Airtable inspection found five tables: Organisations, Makers,
Teams, Screenshots, and Editions. Editions is a first-class table with visible
records for 2023 (Done), 2024 (Done), and 2025 (In progress), and a linked
Organisations field. Organisation records also link to an Edition and a Team.
Teams link an organisation and makers, include a maker count and lookup fields,
and carry a Year. Maker fields include application and selection information;
saved views include accepted with team, rejected, unassigned, gallery, and
future-interest paths. This indicates that edition context organizes
applications, selection, and matching; that is an interpretation of the
observed structure. See the [Airtable data model capture](../../raw/2026-10-18-airtable-data-model.md).

The inspected Editions table shows no 2027 record, although the separate 2027
project brief describes the next edition. The Airtable views and fields do not
show how forms write records, how the active edition is chosen, or the rules
for consent and retention. Those remain open. The table capture intentionally
omits personal record values.

The owner later confirmed that the new version will retire Airtable: all of its
content should be imported into the Laravel site, which will hold new signup
data. Working interpretation is to migrate records, field values, and
relationships across all five tables. Attachment completeness, exact
historical values, and whether saved-view behaviors should be recreated need a
complete export and migration inventory. See the [owner's migration direction](../../raw/2026-10-18-laravel-data-migration-direction.md).

The 2023 BRUZZ report provides historical examples of maker motivations and
organisation expectations: makers mention applying skills to socially useful
work, meeting peers, and feeling part of a community; organisation
representatives describe lacking time or resources for design and marketing.
The report also describes one participant finding the first event overwhelming
and returning in later editions. These are hypotheses to validate, not
representative user research. See the [BRUZZ source capture](../../raw/2026-10-18-bruzz-make-a-mark-2023.md).

Historical Brussels materials describe touchpoints after application: selected
maker onboarding, project and file preparation, a planning meeting with the
organisation and maker team, event-day check-ins, a showcase, and post-event
thanks and file handoff. Two 2023 organisation testimonials mention excitement
about selection and early ideas, appreciation of concrete creative outputs,
and hope that the work would support their social mission. The 2020 packet and
2023 testimonials are local historical artifacts, not confirmation of the
current process. The [international Field Guide](../../raw/2026-10-18-additional-mam-materials.md)
also describes optional RSVP, rejection, survey, and follow-up practices; these
are guidance from the wider movement, not Brussels policy.

Interpretation: signup copy should distinguish an application to this edition
from a lighter request to hear about a future edition. Current phase-specific
content, the meaning of "after the event," and how an application is followed
up still need confirmation. The older organisation application screenshot
mentions March 15, 2025, while the current project brief identifies March 27,
2027; old date-specific copy should therefore be treated as historical.

The owner confirmed 27 March 2027 as the next edition date, resolving the
embedded meeting summary's conflicting “Saturday 17 March” entry with an
uncertain year. The venues, detailed budget, theme, team responsibilities,
and event format remain provisional or unresolved in the meeting notes.

## What remains unknown

- Wider product purpose and lifecycle beyond the existing page.
- Audience research, needs, and current journeys beyond the project's stated
  focus on youth-serving organisations and ages 12–25. The BRUZZ report gives
  useful hypotheses about makers and organisations, and local testimonials
  illustrate two organisational experiences, but direct research is still
  absent.
- Completeness of the Airtable export and migration (including attachments,
  historical values, and relationships); Laravel validation, consent and
  retention requirements; how the active edition phase is selected; and how
  forms change between open, closed, and post-event phases.
- Measurable product and organizational success criteria; the project page
  describes goals and workstreams but no outcome measures.
- Confirmed venues, final theme, detailed budget/funding, team roles, and event
  format; see [Discovery concerns](01-concerns.md).
- Relevant alternatives, integrations, and ecosystem context.

These gaps remain in [Discovery concerns](01-concerns.md) and carry into
Strategy. The Discovery gate is met because no concern remains Open; partial
evidence and unresolved details remain visible for the next phase.
