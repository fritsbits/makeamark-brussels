---
title: Admin discovery and screenshot review
tags: [admin, discovery, review, matchmaking]
sources:
  - "../../raw/2026-10-18-admin-build-instruction.md"
  - "../../raw/2026-10-18-admin-workflow-clarification.md"
  - "../../raw/2026-10-18-admin-review-request.md"
  - "../../raw/2026-10-18-airtable-data-model.md"
  - "../../ux/_scope.md"
  - "20-application-intake.md"
  - "21-application-privacy.md"
  - "../../../AGENTS.md"
phase: Build
updated: 2026-10-18
status: discovery informed implementation; shared skills and manual handoff selected; see admin workspace record
---

# Admin discovery and screenshot review

## Confirmed direction

The owner requests Filament and individual organising-team accounts for
Frederik, Piet, Gwen and Bernard. Frederik is super admin; all four need to
do matchmaking. Detailed permissions were unknown during discovery. These are internal admin
accounts, distinct from future applicant self-service accounts.
[Owner request](../../raw/2026-10-18-admin-review-request.md)

Earlier scope deferred administration. The initial request opened discovery;
the owner subsequently delegated skill labels and instructed the agent to build.
The [implementation record](31-admin-workspace.md) now documents operational
defaults, account permissions and verification. Filament is the explicit admin
choice; public-site Flux conventions remain in the
[project instructions](../../../AGENTS.md).
[Intake](20-application-intake.md#current-implementation-limits)

The owner will message Gwen outside the admin when all briefings are ready.
No additional readiness or messaging workflow is needed. The implementation
uses a shared nineteen-option skill catalogue and a refined briefing alongside
the original application. [Build instruction](../../raw/2026-10-18-admin-build-instruction.md)

## Screenshot inventory

All eleven images have been inspected. A-01–A-09 resolve to the original
attachments in the [first manifest](../../raw/2026-10-18-admin-review-request.md#supplied-originals);
A-10/A-11 resolve to the [follow-up manifest](../../raw/2026-10-18-admin-workflow-clarification.md#supplied-originals).
Historical labels do not establish current business rules.

| Evidence | Visible content | Unresolved |
| --- | --- | --- |
| A-01 · organisation grid | Blank/Applied/Contacted/Meer info nodig/Confirmed status groups, editions, participation checks, named reviewer ratings, comment, average and skills needs. | Complete statuses/transitions, rating scale/formula and missing-rating behavior. |
| A-02 · maker grid | Application date, contacts/work links, main/backup/other skills, portfolio thumbnails, language levels; Accepted with team, Rejected and Teamlozen views; eleven hidden fields. | Hidden fields, view filters, selection actions and language-level source. |
| A-03 · editions | Name, status, notes and linked organisations; historic In progress and Done labels. | Application/team relationships and mapping to current public phases (B-003). |
| A-04 · email automation | Marked off; entering Accepted with team triggers an acceptance/availability email using name/email/team fields. | Whether it was used, actual communication process and current requirements. |
| A-05 · organisation review | Search/list grouped by average; mission/challenge, links/contact, status, selection feedback, Gwen/Piet/Frederik ratings and shared rating comment. | Criteria, rating visibility/edit rights, review sequence and feedback versus internal notes. |
| A-06 · selected organisation | Same review interface with Picked status, ratings and comments. | Picked versus Confirmed, decision authority and side effects of changes. |
| A-07 · team matching | Team name/status, organisation, maker count, skills needed, notes, linked makers and Add record control. | Candidate filtering, assignment rules, capacity, moves/removals and simultaneous edits. |
| A-08 · matching lower section | Aggregated main/backup skills and team email addresses. | How gaps are assessed, language/availability constraints and use of email list. |
| A-09 · maker detail | Work/contact, skills, languages, planning slot, preferences/themes, referral source, motivation and historical food/diet field. | Offscreen selection controls and which fields matter now. |
| A-10 · organisation skills configuration | Skills needs is a multi-select with fifteen visible options. | Whether the historic vocabulary should be retained or aligned with maker skills. |
| A-11 · maker skills configuration | Main skills is a multi-select with nineteen visible options; the maker view is filtered by Status/Team and grouped by Team. | Backup-skill options, exact filters and current field purposes. Historical group sizes do not establish team capacity limits. |

The dietary question has since been removed: do not restore it from A-09.
[Current intake](20-application-intake.md). The earlier
[data-model capture](../../raw/2026-10-18-airtable-data-model.md) adds historic
table/relationship context but is not a complete export.

## Interpretation to validate

### Confirmed handoffs

The owner clarified responsibilities. These do not establish exclusive access
restrictions: all four organisers still need accounts and matchmaking access.
[Clarification](../../raw/2026-10-18-admin-workflow-clarification.md);
[account requirement](../../raw/2026-10-18-admin-review-request.md)

| Work | Responsible people | Result or handoff |
| --- | --- | --- |
| Organisation review | Organisers review separately. | Individual assessments help find consensus; the exact consensus rule is unknown. |
| Organisation contact | Frederik, through his own mailbox, which he considers acceptable. | Contact and arrangement of an online briefing meeting. |
| Briefing refinement | Frederik meets with the organisation online. | Refine the makers' briefing and decide which types of maker are needed. |
| Maker selection | Gwen handles this individually. | Selected makers; criteria and timing relative to briefing refinement are unknown. |
| Team matchmaking | Gwen uses the refined briefing and maker needs. | Find suitable makers for each team. Others' access does not imply shared day-to-day ownership. |

Interpretation: the important admin handoff is the refined briefing and maker
needs becoming available to Gwen. Proposal: preserve the original application
alongside the refined brief, distinguishing submitted answers from later
clarifications. This is not an agreed field model. Do not infer blind reviews,
automatic rating thresholds or that Gwen needs organisation-style ratings for
maker selection.

The described organisation-contact process can continue without an integrated
mailbox. Contact notes, meeting dates and any handoff-ready indicator remain
scope questions. Maker acceptance/rejection communication is unknown (B-010).

### Skills vocabulary in A-10 and A-11

A-10 shows Animation, Art direction, Brand identity, Brand strategy, Campaign
strategy, Content development / copywriting, Digital strategy, Graphic design,
Illustrations, Photography, Print production, Social media design, UX design,
Video production and Web design.

A-11 shows Animation, Art director, Brand identity, Brand strategy, Campaign
strategy, Content development/copywriting, Digital strategy, Graphic design,
Illustrations, Photography, PR, Print production, Service design, Social media
design, UX design, Video production, Web design, Back end web development and
Front end web development.

Observation: the historical vocabularies differ. The owner has since delegated
a unified catalogue: Art director maps to Art direction, copywriting variants
share one label, and PR, Service design and development skills are available
to both audiences. [Mapping](31-admin-workspace.md#skills-and-briefing-handoff).
A-11 also exposes a historical birth-date column;
that does not establish a current collection requirement. Field purpose stays
in B-001 and migration mapping in B-004.

### Task areas still to validate

The core tasks appear to be reviewing organisations, reviewing makers and
assembling teams, supported by editions, portfolios and communications.
This is an interpretation of A-01–A-09, not an agreed sitemap. Screenshots
do not establish automatic ranking/matching or a need to copy every view.

## Review workflow

This is the agent's proposed working method. Work top-down, recording owner
decisions here and unresolved items in the [register](01-build-concerns.md).

| Step | Work | Output / exit condition | Status |
| --- | --- | --- | --- |
| 1. Inventory evidence | Identify each screenshot's task, fields/actions and limits; request only missing views. | Evidence IDs and explicit gaps. | First pass complete above. |
| 2. Walk through decisions | Clarify organisation consensus, Gwen's maker selection and briefing-to-matching handoff. | Record actors and handoffs without inventing automated selection. | Responsibilities and manual message confirmed. |
| 3. Agree scope/rules | Separate essential tasks from later conveniences; define operational defaults. | Task list, permissions and acceptance examples. | Owner instructed build; defaults and tests recorded in the implementation plan/workspace. |
| 4. Audit fields/import | Map task needs to current application answers and historical data; obtain options/formulas/exports as needed. | Field purpose/source/edit owner/requiredness, current versus historical use, migration coverage. | B-001/B-004; screenshots cannot close these. |
| 5. Navigation/default interface | Use the tasks to choose navigation and save the sitemap in Notion. | Content-first Filament defaults under the owner's direct build instruction. | Sitemap saved as private Notion draft; no extra design approval round. |
| 6. Plan/build/verify | Check compatibility, implement and verify in the running product. | Working access, review and matching flows. | See implementation record for current verification and remaining real-account provisioning. |

For each requirement, record: evidence/owner decision, task/actor, information
needed, action/result, essential/later priority, open concern and an observable
acceptance example. A screenshot column alone does not make a requirement.

## Discovery questions retained for refinement

The owner instructed implementation using one shared skills list and a manual
message to Gwen when the briefings are ready. The admin therefore uses a
free-text refined briefing and shared skill selectors. The earlier screenshot
requests below are optional refinement material, not blockers to the build.

**Targeted screenshots:** sanitised or representative records are sufficient.

1. Open organisation and maker status dropdowns so all options are visible.
2. Show the rest of a maker record with selection feedback, notes/ratings (if
   used), status and assignment controls. Organisation skills options are now
   supplied in A-10; languages and eligibility controls remain useful if below
   the supplied review screenshots.
3. Open the Add record maker picker from a team, showing candidate filters
   and how assignments are moved/removed. Include Teamlozen's filters if used
   to find available makers.
4. If maker messages were sent, provide actual acceptance/rejection/waitlist/
   confirmation templates and how they were sent. Organisation contact through
   Frederik's mailbox is confirmed as acceptable; it does not require an email
   integration. A-04 being off does not establish an automation requirement.

**Rules to capture during the walkthrough:**

- Review criteria/scale, averages with missing reviews, visibility of others'
  ratings, individual/shared notes, and maker versus organisation differences.
- Status meanings and transition authority, reopening decisions, and whether
  selection feedback is internal or sent to applicants.
- Team size, one/multiple assignments, skills/languages/availability/preferences,
  draft/final matches and simultaneous changes by organisers.
- All four can match. Decide rights for editing applications/others' reviews,
  selection, sending messages, export and deletion. Account/access management
  and system settings are proposed super-admin responsibilities, not yet
  owner-approved details.

**Later, before implementation/import:** account emails (no passwords in
chat); full table exports with field options, formulas, view filters and
attachment/link coverage through a suitable private transfer route (B-004);
the destination Notion page when the agreed sitemap is ready. None of these
blocks the initial walkthrough.

## Baseline at discovery and boundaries

- Laravel is the ongoing data system; Airtable is an import source.
  [Scope](../../ux/_scope.md#confirmed-scope-decisions)
- Current submissions use a single applications table with audience, edition
  year, contacts and JSON answers. Default users have no admin role controls;
  Filament and review/team models are not present at this review. This is an
  implementation observation, not the target schema.
  [Model](../../../app/Models/Application.php);
  [migration](../../../database/migrations/2026_10_18_000000_create_applications_table.php);
  [User](../../../app/Models/User.php); [Composer](../../../composer.json);
  [routes](../../../routes/web.php)
- Internal notification/link alerts await an authenticated admin destination;
  these differ from selection emails in A-04. [Intake](20-application-intake.md)
- Existing annual review, three-year retention and internal-team access
  decisions apply. Organiser login differs from future applicant accounts.
  [Privacy](21-application-privacy.md#decisions)

B-008 tracks workflow/rules, B-009 permissions, B-010 selection communications.
B-001 remains the field audit, B-003 public phase mapping and B-004 migration
coverage. Current implementation and verification are in the
[admin workspace record](31-admin-workspace.md).
