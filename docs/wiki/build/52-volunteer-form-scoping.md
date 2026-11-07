---
title: Volunteer application form scoping
tags: [applications, forms, volunteers, privacy, plan]
sources:
  - "../../raw/2026-11-05-gwen-form-requests.md"
  - "../../raw/2026-11-05-volunteer-decisions.md"
  - "51-gwen-form-requests.md"
  - "21-application-privacy.md"
  - "22-legitimate-interest-assessment.md"
  - "01-build-concerns.md"
  - "../strategy/01-strategy-concerns.md"
  - "../../raw/2026-10-18-notion-make-a-mark-2027.md"
  - "44-solumob-video-analysis.md"
  - "../image-map-new-site.md"
  - "../../raw/2026-11-07-volunteer-sitemap-approval.md"
  - "53-volunteer-sitemap.md"
phase: Build
updated: 2026-11-07
status: owner decided V-01 to V-10 on 2026-11-05; sitemap approved 2026-11-07; three roles; nothing built
---

# Volunteer application form scoping

Scoping for request G-10 (B-047), with the owner's decisions recorded.
The owner decided on 5 November 2026 that volunteers apply through the site with their own form, as a third audience next to Makers and organisations.
[Decision](../../raw/2026-11-05-gwen-form-requests.md)
Gwen asked for four roles: photographer, videographer, social media manager, and writer of case studies (during the event, plus updating case studies during the year).
[Gwen's email](../../raw/2026-11-05-gwen-form-requests.md)
The owner dropped the case-study writer role, so the form launches with three roles.
[Owner decisions](../../raw/2026-11-05-volunteer-decisions.md)
Code references were checked at commit `957b615`.
No application code changes with this page.

The owner decided V-01 to V-10 on 5 November 2026; see [section 6](#6-owner-decisions).
The rest of the page is the agreed scope.
Interpretations are marked as such.

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
| Work | Solves one organisation's communication, design or digital challenge in a team | Covers the event or its record: photo, video, social media |
| Selection | Reviewed, then placed in a team with skills and theme matching | Reviewed, then given a role |
| Commitment | Full event day and a planning meeting (`resources/views/applications/maker.blade.php:85-87`) | The day, or the part of it the role needs |
| Admin record | Application plus team membership | Application plus assigned roles, no team |

**The three roles.**
Photographer, videographer, social media manager.
The case-study writer role Gwen also listed is out of scope by the owner's decision (V-03).
The Notion brief shows why the roles matter: a photographer is to be confirmed, the day's presentations are to be recorded, and social media is preferably given to young people or students.
[Notion brief](../../raw/2026-10-18-notion-make-a-mark-2027.md)
Gwen put "photographer" and "videographer" in parentheses; what the parentheses mean is not stated, and the owner is asking Gwen.

**Can one person be both?**
Interpretation: yes, but as two applications, because the data model is one application per audience.
`applications.audience` is one string per row (`database/migrations/2026_10_18_000000_create_applications_table.php:13`), and replay protection keys one submission per audience (`app/Support/ApplicationForm.php:79-90`).
A Maker holds the full day in a team, so a Maker cannot also cover the day as photographer.
The owner decided to allow both, as two applications (V-05).

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
| `phone` | Reach the volunteer during selection and on the day itself | Required | Same purpose and hint as the Maker mobile number (G-12, [assessment](22-legitimate-interest-assessment.md#maker-mobile-number)); all three roles include the day. Required by owner decision V-09 |
| `roles[]` | Decide which role to assign | Required, at least one | The form's reason to exist. Checkboxes from a three-item catalogue |

### About your work

| Field | Purpose | Required | Reason |
| --- | --- | --- | --- |
| `portfolio` (URL) | Judge quality without an interview | Required | Photo, video and writing can only be judged from examples. For social media, a link to accounts managed. The Maker form requires the same (`ApplicationSubmissionController.php:53`) |
| `role_notes` | Anything role-specific: equipment, tools, past events | Optional | Gives photographers and videographers a place to say what they bring without a field per role. One free-text field limits the risk of sensitive details; the hint says not to include any |
| `languages[]` (English, Dutch, French) | Match social media managers to the trilingual channels | Required only when "social media manager" is chosen | The site is planned in three languages ([Notion brief](../../raw/2026-10-18-notion-make-a-mark-2027.md)). Photographers and videographers do not need it. Server rule `required_if`, with Alpine show and hide; the server rule is the guarantee |

Levels 1 to 5, as on the Maker form (`maker.blade.php:52-65`), are not needed here.
A checkbox set is enough to place a social media manager.

### Availability

| Field | Purpose | Required | Reason |
| --- | --- | --- | --- |
| `date_confirmed` | Confirm the event date is free | Required | Same statement as the Maker form (`maker.blade.php:85`), read from the edition |
| `day_parts[]` (daytime, evening) | Plan photo and video coverage across the day | Required, at least one | The day has a daytime venue and an evening; the notes consider one photographer for the day and another for the evening ([Notion brief](../../raw/2026-10-18-notion-make-a-mark-2027.md)) |

### Credit

| Field | Purpose | Required | Reason |
| --- | --- | --- | --- |
| `credit_as` | Credit line if the volunteer's work is published | Optional | Existing credits use a name or Instagram handle (`resources/content/stories.json`). Defaults to the applicant's name. Not a rights grant; see section 4 |

There is no age field or age gate, as on the Maker form (V-08).
Whether minors may volunteer, and how the agreement applies to them, is an open question for Gwen.

### Left out on purpose

- **Themes.**
  Volunteers are not matched to organisations, so a theme list has no matching purpose.
  Gwen's themes bullet sits under the volunteer list in her email (`docs/raw/2026-11-05-gwen-form-requests.md` lines 31 to 36), but the owner settled it for organisations.
  [Follow-up answers](../../raw/2026-11-05-gwen-form-requests.md)
  Volunteers do not join teams (V-04), and the owner decided on 7 November 2026 that volunteers don't pick themes.
  [Approval](../../raw/2026-11-07-volunteer-sitemap-approval.md)
- Social media links, other languages, referral, planning-meeting availability, full-commitment statement, year-round availability and age confirmation: no selection or planning use found.

### Status flow

Reuse `ApplicationStatus` (`app/Support/ApplicationStatus.php:7-19`) unchanged.
Interpretation: "selected" means organisers accepted the volunteer; "confirmed" means the volunteer agreed to the volunteer agreement in section 4.

## 3. Admin

Add a `VolunteerResource` next to `MakerResource` and `OrganisationResource`, with `$audience = 'volunteers'` (the pattern in `app/Filament/Resources/MakerResource.php:11-21`).

- **List.** Name, requested roles, assigned roles, day parts, status, edition. Filters: edition, role, status, assigned or not.
- **Edit.** Status, assigned roles (multi-select from the catalogue, may differ from the roles requested), internal notes, and the submitted answers read-only, as for Makers (`app/Filament/Support/ApplicationResource.php:99-143`).
- **Storage.** Two JSON columns, `roles` (requested, backfilled from `answers.roles`) and `assigned_roles`, following the `themes` pattern of G-11 and the `main_skills` pattern (`app/Models/Application.php:15`, `:23-32`). They are not mapped onto `Skills`: that catalogue describes craft skills (`app/Support/Skills.php:11-30`), not event roles.
- **No ratings.** Reviews are restricted to organisations (`app/Models/ApplicationReview.php:18`). One organiser can accept a volunteer; a rating scale adds nothing.
- **No email from the admin.** Status changes stay internal (`ApplicationResource.php:62`), and production mail is disabled ([rollout](46-production-rollout.md)). Selection messages go out manually, as for Makers and organisations.
- **Also applied as Maker.** A hint on the volunteer edit page, matched on email, so organisers see double roles (V-05).

**Do volunteers join teams? No (V-04).**
A team is one organisation plus its Makers (`app/Models/Team.php:25`), and matching accepts only selected or confirmed Makers (`app/Services/Matchmaking.php:49`, `:56`, `:80-85`).
Photographers, videographers and social media managers serve every team, so a team slot would misdescribe them.
Skill coverage and theme overlap in the Team editor (`TeamResource.php:95-125`) have no meaning for volunteer roles.

**Access.**
Volunteers get no admin access.
The notice says only the internal team listed on the site reviews applications, and that nothing is shared with other organisations for their own use.
[Privacy notice, controller section](../../../resources/views/privacy.blade.php); [21](21-application-privacy.md#decisions)
A volunteer who read organisation applications would be a new reviewer group.

## 4. Privacy and retention

### New purpose

Assess volunteer applications, assign roles, and coordinate volunteers for the edition.
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
4. Retention: no change in rule; one sentence that a volunteer role ends when the next edition opens (below).
5. New sentence: a credit is public only when the volunteer has agreed to it.
6. Receipt email (`resources/views/emails/application-received.blade.php:3`): see section 5, it would otherwise say "organisation".
7. Update the copy in `21-application-privacy.md` and the assessment.

### Retention against the 3-year rule

The rule keeps an application up to three years after the applicant's latest application, then deletes or anonymises it.
[21](21-application-privacy.md#decisions)
With the case-study writer dropped, no year-round element is left.
All three roles are for the day, 27 March 2027 ([Notion brief](../../raw/2026-10-18-notion-make-a-mark-2027.md)).
Interpretation: a volunteer record is therefore like a Maker record, and the existing rule applies unchanged.

Decided (V-06):

- Keep three years unchanged.
- A volunteer role ends when the next edition opens.
- To continue, the volunteer reapplies, which creates a new application and restarts the clock.
  The notice already says returning applicants are asked to review and update their information.
  [21](21-application-privacy.md#collection-notice-copy)
- Add a volunteer line to the annual manual retention review, since no automatic purge exists.

### Image rights and credits for photo and video (B-007)

B-007 is open: photographer credits and publication permission for the thirteen supplied photos are unverified.
[B-007](01-build-concerns.md); [slot map](../image-map-new-site.md)
Volunteer photographers and videographers would add new material under the same open question, so settle the terms before the day rather than after.

Decided (V-07): a one-page volunteer agreement, sent on selection, not collected through the application form.
It would cover, as interpretation and not legal advice:

- **Copyright and licence.** The volunteer keeps copyright and licenses Make a Mark Brussels and Impact Studio to use the work on the website, social channels, press and grant applications. The Notion brief names press and grant uses.
- **Credit.** The format the volunteer asked for in `credit_as`, defaulting to their name.
- **Who decides** what is published: the organisers.
- **People in the images.** Attendees include young people around 12 to 25 ([Notion brief](../../raw/2026-10-18-notion-make-a-mark-2027.md)). Consent from people shown, and from parents for minors, is a separate on-site process. Not determined here.
It is an open question for Gwen whether minors may volunteer and how the agreement applies to them.

Recording the day's presentations is already an action point.
[Notion brief](../../raw/2026-10-18-notion-make-a-mark-2027.md)
A recording shows organisation representatives, so the agreement should say recordings are used only for the purposes agreed with the people shown.

### Case studies (B-036)

The case-study writer role is out of scope, so volunteers publish no case-study text.
B-036 is unaffected and stays partly resolved under its own next step.
[B-036](01-build-concerns.md)

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

## 6. Owner decisions

The owner decided all ten on 5 November 2026.
[Verbatim](../../raw/2026-11-05-volunteer-decisions.md)

| ID | Question | Decision | Effect on this page |
| --- | --- | --- | --- |
| V-01 | Public page and name | A: own page `/volunteers` | Own page with information and form; sitemap entry, header link and homepage panel in the build order |
| V-02 | Launch roles | A: launch with all roles, but see V-03 | Reduced to three roles by V-03 |
| V-03 | Case-study writer role | "Omit this role" | Role dropped. G-10's fourth role is out of scope, by the owner's decision. Three roles: photographer, videographer, social media manager |
| V-04 | Teams | A: no teams, only assigned roles | Section 3 |
| V-05 | One person, two audiences | A: allow both, with an "Also applied as Maker" hint in the admin | Section 1 and 3 |
| V-06 | Retention | A: keep 3 years; a role ends when the next edition opens; continuing means reapplying | No year-round element is left, so `year_round` is dropped and the retention section simplified |
| V-07 | Image rights and credit | A: one-page volunteer agreement sent on selection | Section 4; the agreement is not code |
| V-08 | Minimum age | B: no age gate, as for Makers | `adult_confirmed` dropped. The agreement and minors stay an open question for Gwen |
| V-09 | Mobile number | A: required, same purpose as for Makers | Section 2 |
| V-10 | Case-study publication | Moot, the writer role is dropped. Closed as out of scope | B-036 unaffected |

## 7. Proposed build order

Batch C is one thread and one branch from the merged A and B result, collapsed to one commit.
[Work batches](51-gwen-form-requests.md#work-batches)

1. **Gate:** sitemap approved on 7 November 2026 and saved in `docs/ux/_structure.md` ([53](53-volunteer-sitemap.md)). Next: wireframe approval before any custom styling, as `AGENTS.md` requires for new pages.
2. **Merge A and B** to the base branch.
3. **Registry refactor with no behaviour change.** Add `Audiences`, move Maker and organisation onto it, delete `MakerApplicationForm`, keep legacy token values. The existing suite must pass unchanged apart from audience wiring.
4. **Volunteer audience.** Role catalogue, migration, rules and form view, receipt copy, mail, route, sitemap.
5. **Admin.** `VolunteerResource`, filters, assigned roles, "Also applied as Maker" hint.
6. **Public page.** `/volunteers`, header and footer link, homepage panel. The homepage scribble currently points at two audience blocks (`docs/wiki/build/42-homepage-apply-now-scribble-options.md`), so recheck it with three.
7. **Privacy.** Notice, assessment section, `21` copy, retention review line.
8. **Verify** in the running product: PHP suite, build, 1440px and 390px for the new page, form states and admin screens.
9. **Wiki:** this page's status, B-047, `index.md`, `log.md`.

The volunteer agreement (V-07) and the on-site consent process for people in images are not code; they must exist before the event.

## Open questions for Gwen

The owner left these unanswered; they go to Gwen.
The fourth, whether volunteers pick themes, the owner answered on 7 November 2026: they don't ([approval](../../raw/2026-11-07-volunteer-sitemap-approval.md)).

- What her parentheses around photographer and videographer mean.
- Whether volunteers under 18 are allowed, and whether insurance covers volunteers.
- Whether Nena is confirmed as photographer. The notes say "Confirm Nena as photographer", with Dominique also considered for the evening; they do not record a confirmation.

## Not determined

- When batches A and B merge to `main`.
- Belgian image-right and copyright rules for the agreement; not researched here.
