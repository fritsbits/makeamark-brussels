---
title: Volunteer application form scoping
tags: [applications, forms, volunteers, privacy, plan]
sources:
  - "../../raw/2026-11-05-gwen-form-requests.md"
  - "51-gwen-form-requests.md"
  - "21-application-privacy.md"
  - "22-legitimate-interest-assessment.md"
  - "01-build-concerns.md"
  - "../strategy/01-strategy-concerns.md"
  - "../../raw/2026-10-18-notion-make-a-mark-2027.md"
  - "44-solumob-video-analysis.md"
  - "../image-map-new-site.md"
phase: Build
updated: 2026-11-05
status: proposal for owner decision; nothing built
---

# Volunteer application form scoping

Proposal for request G-10 (B-047).
The owner decided on 5 November 2026 that volunteers apply through the site with their own form, as a third audience next to Makers and organisations.
[Decision](../../raw/2026-11-05-gwen-form-requests.md)
Gwen asked for four roles: photographer, videographer, social media manager, and writer of case studies (during the event, plus updating case studies during the year).
[Gwen's email](../../raw/2026-11-05-gwen-form-requests.md)
Code references were checked at commit `957b615`.
No application code changes with this page.

Decisions needed from the owner are V-01 to V-10 in [section 6](#6-open-decisions).
Recommendations are marked as such; they are interpretations, not facts.

## 1. Audience and roles

**Who a volunteer is.**
A volunteer supports the edition through one named role.
They do not work on an organisation's challenge.
Interpretation: this follows from the four roles Gwen listed, which all serve the whole event or its communication, not one organisation.

**How a volunteer differs from a Maker.**
Both give their time without pay, so "volunteer" alone does not separate them.
The brief calls the Makers "volunteer makers", and the Maker form says Makers "contribute their time voluntarily".
[Notion brief](../../raw/2026-10-18-notion-make-a-mark-2027.md); `resources/views/applications/context.blade.php:3`, `:5`
The difference is the work.

| | Maker | Volunteer |
| --- | --- | --- |
| Work | Solves one organisation's communication, design or digital challenge in a team | Covers the event or its record: photo, video, social media, case studies |
| Selection | Reviewed, then placed in a team with skills and theme matching | Reviewed, then given a role |
| Commitment | Full event day and a planning meeting (`resources/views/applications/maker.blade.php:85-87`) | The day, or the part of it the role needs; case-study writers may continue during the year |
| Admin record | Application plus team membership | Application plus assigned roles, no team |

**The four roles.**
Photographer, videographer, social media manager, case-study writer.
The Notion brief shows why the roles matter: a photographer is already being confirmed, the day's presentations are to be recorded for case-study material, social media is preferably given to young people or students, and 3 to 4 case studies with impact are proposed for the website.
[Notion brief](../../raw/2026-10-18-notion-make-a-mark-2027.md)
Gwen put "photographer" and "videographer" in parentheses; what the parentheses mean is not stated ([V-02](#v-02-launch-roles)).

**Can one person be both?**
Interpretation: yes, but as two applications, because the data model is one application per audience.
`applications.audience` is one string per row (`database/migrations/2026_10_18_000000_create_applications_table.php:13`), and replay protection keys one submission per audience (`app/Support/ApplicationForm.php:79-90`).
A Maker holds the full day in a team, so a Maker cannot also cover the day as photographer.
A Maker could still write case studies during the year.
See [V-05](#v-05-one-person-two-audiences).

## 2. Form fields

The form follows the Maker form's structure and tone, and keeps only what selection and planning need (B-001 asks for fields not needed to be removed).
[Build concerns B-001](01-build-concerns.md); [assessment](22-legitimate-interest-assessment.md#preliminary-checks)
Reviewers choose by shown work, so there is no motivation essay and no "where did you hear about us" field.
The Maker form asks both (`resources/views/applications/maker.blade.php:47-48`); dropping them here is a minimisation choice.

### About you

| Field | Purpose | Required | Reason |
| --- | --- | --- | --- |
| `name` | Identify and address the applicant | Required | Same as the Maker form |
| `email` | Receipt, selection, role briefing | Required | The only reliable channel |
| `phone` | Reach the volunteer during selection and on the day itself | Required | Same purpose and hint as the Maker mobile number (G-12, [assessment](22-legitimate-interest-assessment.md#maker-mobile-number)); all four roles include the day. Confirm in [V-09](#v-09-mobile-number) |
| `roles[]` | Decide which role to assign | Required, at least one | The form's reason to exist. Checkboxes from a four-item catalogue |

### About your work

| Field | Purpose | Required | Reason |
| --- | --- | --- | --- |
| `portfolio` (URL) | Judge quality without an interview | Required | Photo, video and writing can only be judged from examples. For social media, a link to accounts managed. The Maker form requires the same (`ApplicationSubmissionController.php:53`) |
| `role_notes` | Anything role-specific: equipment, tools, past events | Optional | Gives photographers and videographers a place to say what they bring without a field per role. One free-text field limits the risk of sensitive details; the hint says not to include any |
| `languages[]` (English, Dutch, French) | Match writers and social media managers to the trilingual site and channels | Required only when "writer" or "social media manager" is chosen | The site is planned in three languages ([Notion brief](../../raw/2026-10-18-notion-make-a-mark-2027.md)). Photographers do not need it. Server rule `required_if`, with Alpine show and hide; the server rule is the guarantee |

Levels 1 to 5, as on the Maker form (`maker.blade.php:52-65`), are not needed here.
A checkbox set is enough to place a writer.

### Availability

| Field | Purpose | Required | Reason |
| --- | --- | --- | --- |
| `date_confirmed` | Confirm the event date is free | Required | Same statement as the Maker form (`maker.blade.php:85`), read from the edition |
| `day_parts[]` (daytime, evening) | Plan photo and video coverage across the day | Required, at least one | The day has a daytime venue and an evening; the notes consider one photographer for the day and another for the evening ([Notion brief](../../raw/2026-10-18-notion-make-a-mark-2027.md)) |
| `year_round` (checkbox) | Know who can update case studies after the event | Optional, shown only when "writer" is chosen | Gwen's role includes updating case studies during the year. Optional so the pool is larger; see [V-03](#v-03-case-study-writer-role-shape) |

### Credit and age

| Field | Purpose | Required | Reason |
| --- | --- | --- | --- |
| `credit_as` | Credit line if the volunteer's work is published | Optional | Existing credits use a name or Instagram handle (`resources/content/stories.json`). Defaults to the applicant's name. Not a rights grant; see section 4 |
| `adult_confirmed` (checkbox) | Know the volunteer can sign an agreement and work unsupervised | Required | See [V-08](#v-08-minimum-age) |

### Left out on purpose

- **Themes.**
  Volunteers are not matched to organisations, so a theme list has no matching purpose.
  Gwen's themes bullet sits under the volunteer list in her email (`docs/raw/2026-11-05-gwen-form-requests.md` lines 31 to 36), but the owner settled it for organisations.
  [Follow-up answers](../../raw/2026-11-05-gwen-form-requests.md)
  Add themes only if volunteers join teams ([V-04](#v-04-teams)).
- Social media links, other languages, referral, planning-meeting availability and full-commitment statement: no selection or planning use found.

### Status flow

Reuse `ApplicationStatus` (`app/Support/ApplicationStatus.php:7-19`) unchanged.
Interpretation: "selected" means organisers accepted the volunteer; "confirmed" means the volunteer agreed to the role terms in section 4.

## 3. Admin

Add a `VolunteerResource` next to `MakerResource` and `OrganisationResource`, with `$audience = 'volunteers'` (the pattern in `app/Filament/Resources/MakerResource.php:11-21`).

- **List.** Name, requested roles, assigned roles, day parts, year-round, status, edition. Filters: edition, role, status, year-round, assigned or not.
- **Edit.** Status, assigned roles (multi-select from the catalogue, may differ from the roles requested), internal notes, and the submitted answers read-only, as for Makers (`app/Filament/Support/ApplicationResource.php:99-143`).
- **Storage.** Two JSON columns, `roles` (requested, backfilled from `answers.roles`) and `assigned_roles`, following the `themes` pattern of G-11 and the `main_skills` pattern (`app/Models/Application.php:15`, `:23-32`). They are not mapped onto `Skills`: that catalogue describes craft skills (`app/Support/Skills.php:11-30`), not event roles.
- **No ratings.** Reviews are restricted to organisations (`app/Models/ApplicationReview.php:18`). One organiser can accept a volunteer; a rating scale adds nothing.
- **No email from the admin.** Status changes stay internal (`ApplicationResource.php:62`), and production mail is disabled ([rollout](46-production-rollout.md)). Selection messages go out manually, as for Makers and organisations.
- **Optional hint.** An "Also applied as Maker" line on the volunteer edit page, matched on email, so organisers see double roles ([V-05](#v-05-one-person-two-audiences)).

**Do volunteers join teams? Recommendation: no.**
A team is one organisation plus its Makers (`app/Models/Team.php:25`), and matching accepts only selected or confirmed Makers (`app/Services/Matchmaking.php:49`, `:56`, `:80-85`).
Photographers and writers serve every team, so a team slot would misdescribe them.
Skill coverage and theme overlap in the Team editor (`TeamResource.php:95-125`) have no meaning for volunteer roles.
[V-04](#v-04-teams)

**Access.**
Volunteers get no admin access.
The notice says only the internal team listed on the site reviews applications, and that nothing is shared with other organisations for their own use.
[Privacy notice, controller section](../../../resources/views/privacy.blade.php); [21](21-application-privacy.md#decisions)
A case-study writer who read organisation applications would be a new reviewer group.
Organisers hand writers only what the organisation has approved.

## 4. Privacy and retention

### New purpose

Assess volunteer applications, assign roles, and coordinate volunteers for the edition, including case-study updates during the year for writers.
Basis stays Article 6(1)(f), as for the other audiences.
Interpretation: the existing assessment covers "application review, participant selection, event organisation" for Makers and organisations, so a new section is needed for volunteers, in the style of the mobile-number section.
[Assessment](22-legitimate-interest-assessment.md)

Add a "Volunteer applications" section to the assessment with these points:

- **Necessity.** Each field above has a stated purpose; the omitted fields are listed.
- **Balancing.** Same internal-only access, manual review, no automated selection, no marketing, same right to object.
- **Reassess if** volunteers get system access, or if their data feeds anything beyond role assignment.

### Privacy notice changes

All in `resources/views/privacy.blade.php` unless noted; bump the notice date.

1. Controller section (`:13`): "Maker and organisation applications" becomes "Maker, volunteer and organisation applications".
2. What we collect (`:32`): "Maker or organisation application" gains volunteers. The mobile-number sentence names volunteers, with the same limited purpose.
3. What we collect: portfolio links, and the optional credit line.
4. Retention: one sentence on year-round roles (below).
5. New sentence: a credit is public only when the volunteer has agreed to it.
6. Receipt email (`resources/views/emails/application-received.blade.php:3`): see section 5, it would otherwise say "organisation".
7. Update the copy in `21-application-privacy.md` and the assessment.

### Year-round role against the 3-year rule

The rule keeps an application up to three years after the applicant's latest application, then deletes or anonymises it.
[21](21-application-privacy.md#decisions)
A 2027 volunteer applies around the event, 27 March 2027 ([Notion brief](../../raw/2026-10-18-notion-make-a-mark-2027.md)).
Updating case studies through 2027 and into early 2028 sits inside three years, so the rule is not broken by one edition year.
The problem is a writer who keeps working for years without reapplying: their "latest application" would age past three years while they are still active.
Interpretation: this is the same situation as a returning Maker, and the notice already says returning applicants are asked to review and update their information.
[21](21-application-privacy.md#collection-notice-copy)

Recommendation ([V-06](#v-06-year-round-retention)):

- Keep three years unchanged.
- A year-round role ends when the next edition's applications open.
- To continue, the volunteer reapplies or confirms, which creates a new application and restarts the clock.
- Add a volunteer line to the annual manual retention review, since no automatic purge exists.

### Image rights and credits for photo and video (B-007)

B-007 is open: photographer credits and publication permission for the thirteen supplied photos are unverified.
[B-007](01-build-concerns.md); [slot map](../image-map-new-site.md)
Volunteer photographers and videographers would add new material under the same open question, so settle the terms before the day rather than after.

Recommendation ([V-07](#v-07-image-rights-and-credit)): a one-page volunteer agreement, sent on selection, not collected through the application form.
It would cover, as interpretation and not legal advice:

- **Copyright and licence.** The volunteer keeps copyright and licenses Make a Mark Brussels and Impact Studio to use the work on the website, social channels, press and grant applications. The Notion brief names press and grant uses.
- **Credit.** The format the volunteer asked for in `credit_as`, defaulting to their name.
- **Who decides** what is published: the organisers.
- **People in the images.** Attendees include young people around 12 to 25 ([Notion brief](../../raw/2026-10-18-notion-make-a-mark-2027.md)). Consent from people shown, and from parents for minors, is a separate on-site process. Not determined here.

Recording the day's presentations is already an action point.
[Notion brief](../../raw/2026-10-18-notion-make-a-mark-2027.md)
A recording shows organisation representatives, so the agreement should say recordings are used only for case studies unless the people shown agree otherwise.

### What a case-study writer may publish (B-036)

B-036 is partly resolved.
The Solumob dossier verifies visible activities and the distinction between the 2019 event and the 2020 repeat, but final assets, brief, credits, later use and measurable outcomes remain unverified.
B-036 says to keep logo changes, hours and event counts qualified, and to establish subsequent use and a concrete outcome before publishing an impact claim.
[B-036](01-build-concerns.md); [Solumob analysis](44-solumob-video-analysis.md)

Rules for the writer, recommended ([V-10](#v-10-case-study-publication)):

- The writer drafts; the organisation approves its case study in writing; the owner publishes.
  The notes prefer calling organisations for their own case-study content.
  [Notion brief](../../raw/2026-10-18-notion-make-a-mark-2027.md)
- Claims come only from what the organisation or the team supplied. No impact claim without a concrete outcome, as B-036 requires.
- Case-study pages do not exist yet; the structure plan lists them as a later page type.
  [UX structure](../../ux/_structure.md)
  Publishing is therefore a content commit by the owner, not a writer action.
- Organisation names, logos and quotes need that organisation's approval.

## 5. Code approach

### Where the two audiences are hard-coded today

Silent defaults are the main risk: many places use `=== 'makers' ? A : B`, so a third audience would quietly get the organisation branch.
Two `match` expressions have no default, so a third audience would throw an `UnhandledMatchError` (a 500) instead.

**Routes**

| Where | What |
| --- | --- |
| `routes/web.php:34`, `:35`, `:36` | `whereIn('audience', ['makers', 'organisations'])` on apply, submit and show |
| `routes/web.php:17-18` | The same list on the local-only messaging preview route |
| `routes/web.php:48` | Sitemap lists `/makers` and `/organisations` by hand |
| `resources/content/site.json:13-22` | Header navigation; the footer loops the same array (`resources/views/sections/footer.blade.php:10-12`) |

**Controllers**

| Where | What |
| --- | --- |
| `app/Http/Controllers/ApplicationPageController.php:11` | `in_array` list |
| `app/Http/Controllers/AudiencePageController.php:15` | `in_array` list |
| `AudiencePageController.php:22-24` | Printable answers only for organisations |
| `AudiencePageController.php:35-38` | Title and description ternaries; volunteers would get the organisation text |
| `app/Http/Controllers/ApplicationSubmissionController.php:22` | `in_array` list |
| `ApplicationSubmissionController.php:42-85` | Whole rule set chosen by `=== 'makers'` ternary |
| `ApplicationSubmissionController.php:89-91` | Organisation-only validation messages |
| `ApplicationSubmissionController.php:103` | Contact name from `name` or `contact_name` by ternary |
| `app/Http/Controllers/ApplicationPrintController.php:15`, `app/Providers/Filament/AdminPanelProvider.php:54` | Print route is organisation-only; stays so |

**`App\Support\ApplicationForm`**

| Where | What |
| --- | --- |
| `app/Support/ApplicationForm.php:14-27` | `match ($audience)` for field limits, **no default** |
| `ApplicationForm.php:34-36` | Checkbox list by ternary; volunteers would get the organisation checkboxes |
| `ApplicationForm.php:42` | List fields and limits by ternary |
| `ApplicationForm.php:85` | Replay scope ternary, kept so existing Maker keys stay valid |
| `ApplicationForm.php:100-106` | `secretKey` `match`, **no default** |
| `ApplicationForm.php:111` | Signature prefix ternary, kept for Maker forms already open in browsers |
| `app/Support/MakerApplicationForm.php:13-28` | Wrapper hard-coding `'makers'`. No caller was found in `app`, `tests`, `resources` or `routes`; interpretation: dead code to delete in the refactor |

**Views and mail**

| Where | What |
| --- | --- |
| `resources/views/audience.blade.php:3` | Page class `makers-page` or `organisations-page` |
| `audience.blade.php:5-27`, `:39-46`, `:55` | Hero, lead, commitment and form heading by ternary |
| `resources/views/applications/intake.blade.php:9`, `:12`, `:15`, `:49`, `:56` | Receipt copy and the form include by ternary (`:49` includes `applications.maker` or `applications.organisation`) |
| `resources/views/applications/context.blade.php:3`, `:5`, `:7` | Context copy; the language hint is organisation-only |
| `resources/views/home.blade.php:32-43` | Two `audience-panel` articles in an `audience-pair` grid |
| `resources/css/app.css:536`, `:643`; `resources/css/makers.css:8-37`, `:96-102`; `resources/css/public-typography.css:52-55` | Two-column pair and per-audience hero selectors |
| `resources/views/privacy.blade.php:13`, `:32` | Names Maker and organisation applications |
| `resources/views/emails/application-received.blade.php:3` | `'Maker' : 'organisation'`; a volunteer receipt would say "organisation" |

**Admin and matching**

| Where | What |
| --- | --- |
| `app/Filament/Support/ApplicationResource.php:30`, `:35` | `static::$audience` already drives the query; the base is mostly generic |
| `ApplicationResource.php:58`, `:69-82`, `:153-190` | `$organisation` boolean switches sections, columns, filters and labels |
| `ApplicationResource.php:112`, `:127-128` | Answer labels by organisation or Maker |
| `app/Filament/Support/EditApplication.php:20-22` | Saved fields by ternary; volunteers would get Maker fields |
| `app/Filament/Resources/MakerResource.php:11-21`, `OrganisationResource.php:11-21` | Audience, slug, label, sort order |
| `app/Models/Application.php:28` | Skill defaults for `makers`; volunteers fall to the empty default, which is correct |
| `app/Models/ApplicationReview.php:18` | Reviews only for organisations; correct for volunteers |
| `app/Services/Matchmaking.php:49`, `:56`, `:80-85` | Eligibility by audience string; unaffected if volunteers have no teams |
| `app/Filament/Resources/TeamResource.php:54`, `:86`, `:111`, `:116`, `:123` | Team editor queries; unaffected if volunteers have no teams |
| Tests | 40 mentions of `audience` across 10 files in `tests/Feature` |

### Audience registry

Add `App\Support\Audiences`, a catalogue like `Skills` and `Themes` (`app/Support/Skills.php`, `app/Support/Themes.php`), returning one definition per audience.
This follows the owner's instruction to build volunteers on an audience registry so the two-way code is refactored once.
[51](51-gwen-form-requests.md)

A definition holds:

- slug, plural and singular labels, admin model label and sort order
- field limits, checkbox fields, list fields (replaces the `match` and ternaries in `ApplicationForm`)
- validation rules and messages (replaces the controller ternary)
- session secret key, with `maker_submission_secret` and `organisation_submission_secret` kept as explicit legacy values
- replay-scope and signature prefix, with the Maker values kept as the legacy case so open forms and existing keys stay valid
- form view name, receipt copy, page title and description, mail noun
- contact-name field, whether the audience has teams, whether it has reviews
- a page-class slug for CSS

Unknown audience slugs fail loudly through the registry's `find` method, replacing the two default-less `match` expressions and every `in_array` list.
Routes use one constraint built from the registry.
Hero and commitment copy stays in Blade, in per-audience partials chosen by slug, so editorial copy is not pushed into PHP.
The admin `$organisation` booleans can read definition flags, and each resource stays a thin subclass.

### Size and conflict risk

Estimates, not measurements.

- **New:** registry (about 150 lines), volunteer form view (about 90), volunteer role catalogue (about 25), `VolunteerResource` with two pages (about 80), one migration for `roles` and `assigned_roles`, hero and commitment partial, a feature test file.
- **Edited:** about 20 files from the tables above, mostly replacing branches with registry lookups, plus about 10 test files that name an audience.
- **Net:** roughly 600 to 900 changed lines, most of them moved rather than new.

Conflict risk is high right now.
Batches A and B are not on `main`: `main` is at `ebfcf2c`, while this branch is 59 files ahead, and batch A and batch B live on separate branches and worktrees.
`git worktree list` shows `fritsbits/review-gwen-website-requests` and `fritsbits/building-part-batch-mark-brussels` besides this branch.
The refactor touches exactly the files those batches changed: `ApplicationSubmissionController`, `ApplicationForm`, `ApplicationResource`, `audience.blade.php`, `TeamResource`, the privacy notice and the tests.
Recommendation: branch batch C from the merged result of A and B, never from `main` as it is today.
Two defences after that: do the refactor as a no-behaviour-change step proven by the existing suite, and keep legacy token and secret values byte-identical.

## 6. Open decisions

### V-01 Public page and name

Where do volunteers land?

- **A. Own page `/volunteers`**, with information and the form, like the other two (recommended). Matches the owner's "third audience". Needs a sitemap entry, a header link and a homepage panel.
- **B. A section on `/makers`.** Smaller, but it blurs the Maker and volunteer difference in section 1.
- **C. Own page named "Crew".** Avoids "volunteer" overlapping with Makers, but departs from the owner's and Gwen's wording.

### V-02 Launch roles

- **A. All four roles now** (recommended). One catalogue, so the extra roles cost little; photographer and videographer are the roles most worth opening early.
- **B. Social media manager and writer only**, because a photographer is already being confirmed.
- **C. All four, with photo and video marked "by invitation".** Adds a state the form and admin do not otherwise need.

Gwen's parentheses around photographer and videographer are unexplained; ask her before building.

### V-03 Case-study writer role shape

- **A. One role, year-round part required.** Matches Gwen's wording literally, but shrinks the pool.
- **B. One role, year-round part an optional checkbox** (recommended). Organisers can assign a day-only writer and find a year-round editor later.
- **C. Two roles:** event writer and case-study editor. Cleanest for retention, one more role to explain.

### V-04 Teams

- **A. No teams; assigned roles only** (recommended). See section 3.
- **B. Volunteers join teams** as non-Maker members. Needs changes in `Matchmaking` and the Team editor, and a reason for a photographer to belong to one organisation.
- **C. A separate coverage view** (who covers which part of the day) without teams. Useful later, not needed at launch.

### V-05 One person, two audiences

- **A. Allow both, two applications, with an "Also applied as Maker" hint** (recommended).
- **B. One application per email per edition.** Forces a choice, needs a cross-audience uniqueness rule.
- **C. Fold volunteering into the Maker form as a role.** Reverses the owner's own-form decision.

### V-06 Year-round retention

- **A. Keep three years; a year-round role ends when the next edition opens; continuing means reapplying or confirming** (recommended).
- **B. Keep three years and let year-round volunteers continue without reapplying.** Simplest, but breaks "three years after latest application".
- **C. Shorter retention for volunteers who are not selected.** Better minimisation, but a second rule in a manual annual review.

### V-07 Image rights and credit

- **A. One-page volunteer agreement sent on selection, not in the form** (recommended). It carries the licence, credit and use terms.
- **B. A required checkbox in the form.** Quick, but a checkbox before selection is a weak basis for a licence and the notice says no consent checkbox is used.
- **C. No formal terms; credit by default.** Leaves B-007 open for new material.

Whoever writes the agreement should check it with a lawyer; this page does not verify Belgian law.

### V-08 Minimum age

Notes prefer young people or students for social media.
[Notion brief](../../raw/2026-10-18-notion-make-a-mark-2027.md)

- **A. Required "I am 18 or older" checkbox** (recommended). A yes or no answer, minimal data.
- **B. No age gate**, as on the Maker form. Raises the question of minors and the agreement.
- **C. Ask for an age band.** More data than the purpose needs.

### V-09 Mobile number

- **A. Required, same purpose as the Maker field** (recommended). All four roles include the day.
- **B. Optional.** Volunteers cannot be reached if email fails on the day, the reason the owner required it for Makers.

### V-10 Case-study publication

- **A. Writer drafts, the organisation approves in writing, the owner publishes; no admin access for the writer** (recommended). Keeps B-036 claims qualified and the reviewer group unchanged.
- **B. Writer gets limited admin access** to organisation briefs. Faster research, but a new reviewer group the notice does not describe.
- **C. Writer publishes through a content commit.** Case-study pages do not exist yet, so this also needs a page type.

## 7. Proposed build order

Batch C is one thread and one branch from the merged A and B result, collapsed to one commit.
[Work batches](51-gwen-form-requests.md#work-batches)

1. **Gate:** save the volunteer sitemap entry in Notion and get wireframe approval before any custom styling, as `AGENTS.md` requires for new pages. Update `docs/ux/_structure.md`.
2. **Merge A and B** to the base branch.
3. **Registry refactor with no behaviour change.** Add `Audiences`, move Maker and organisation onto it, delete `MakerApplicationForm`, keep legacy token values. The existing suite must pass unchanged apart from audience wiring.
4. **Volunteer audience.** Role catalogue, migration, rules and form view, receipt copy, mail, route, sitemap.
5. **Admin.** `VolunteerResource`, filters, assigned roles, optional "also a Maker" hint.
6. **Public page.** `/volunteers`, header and footer link, homepage panel. The homepage scribble currently points at two audience blocks (`docs/wiki/build/42-homepage-apply-now-scribble-options.md`), so recheck it with three.
7. **Privacy.** Notice, assessment section, `21` copy, retention review line.
8. **Verify** in the running product: PHP suite, build, 1440px and 390px for the new page, form states and admin screens.
9. **Wiki:** this page's status, B-047, `index.md`, `log.md`.

The volunteer agreement (V-07) and the on-site consent process for people in images are not code; they must exist before the event.

## Could not determine

- What Gwen's parentheses around photographer and videographer mean.
- Whether Gwen meant themes for the volunteer form; the owner answered for organisations.
- Whether volunteers under 18 are allowed, and any insurance cover for volunteers.
- Whether a photographer is already arranged, so that role may not need an open call. The notes say "Confirm Nena as photographer", with Dominique also considered for the evening; they do not record a confirmation.
- Whether and when batches A and B merge to `main`.
- Belgian image-right and copyright rules for the agreement; not researched here.
