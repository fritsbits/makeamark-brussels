---
title: Gwen's 2027 form requests
tags: [applications, forms, privacy, plan]
sources:
  - "../../raw/2026-11-05-gwen-form-requests.md"
  - "50-applications-closed.md"
  - "46-production-rollout.md"
  - "21-application-privacy.md"
  - "22-legitimate-interest-assessment.md"
  - "../strategy/10-tone-of-voice.md"
  - "../../raw/2026-10-18-previous-website-copy.md"
  - "../../raw/2026-10-18-airtable-forms.md"
phase: Build
updated: 2026-11-05
status: batch A implemented and verified locally; batches B and C planned
---

# Gwen's 2027 form requests

Gwen Dubois sent twelve requests for the 2027 application forms on 20 October 2026.
The owner decided on each one on 5 November 2026: ten to build, two skipped.
[Emails and decisions](../../raw/2026-11-05-gwen-form-requests.md)

Applications are closed with status `preparing`, so all changes can land before opening day.
[Applications closed](50-applications-closed.md)

## Decisions

| ID | Request | Decision | Concern |
| --- | --- | --- | --- |
| G-01 | Application deadline on the site | Do now | B-041 |
| G-02 | "Makers" with a capital M | Do now | B-041 |
| G-03 | Backoffice question | Do now: reply | B-041 |
| G-05 | EN/FR/NL hint on the organisation form | Do now | B-041 |
| G-06 | Separate organisation-language field | Do now | B-042 |
| G-12 | Required Maker mobile number | Do now | B-043 |
| G-11 | Shared themes on the organisation form | Do now | B-044 |
| G-09 | Printable team briefing | Do now | B-045 |
| G-08 | Organisation copy of answers | Do now, option B | B-046 |
| G-10 | Volunteer application form | Do, own form | B-047 |
| G-04 | Organisation logo upload | Skip | B-048 |
| G-07 | Organisation keywords | Skip | B-048 |

## Findings behind the plan

These were checked against the code at `ebfcf2c`.

- Editions have no deadline or opening date field; only `dates`, `startDate`, `endDate` and `status` (`resources/content/editions/2027.json`).
- The captured old-site copy shows no deadline, only "end of January" and "mid-February" milestones.
  Interpretation: Gwen may remember another year's site.
  [Previous copy](../../raw/2026-10-18-previous-website-copy.md)
- About 23 rendered public strings and 11 admin strings use lowercase "maker" or "makers".
  The tone-of-voice page proposed lowercase as an unconfirmed default; Gwen's rule replaces it.
  [Tone of voice](../strategy/10-tone-of-voice.md)
- The organisation form asks only "Which languages can your contact person work in?" (`resources/views/applications/organisation.blade.php:77-86`).
- The Maker form already has ten fixed theme checkboxes (`resources/views/applications/maker.blade.php:83-91`).
  The organisation form has no theme field.
  Interpretation: Gwen's "free field" note most likely means organisations, so Maker interests can be matched against them.
  The owner settled it: organisations pick from the same ten themes.
- The Maker form has no phone field; the organisation form requires one.
  The old Airtable Maker form had none either.
  [Airtable forms](../../raw/2026-10-18-airtable-forms.md)
- Production mail is disabled, so receipts and admin password resets do not arrive.
  [Production rollout](46-production-rollout.md)
- The privacy notice says Resend does not receive application answers and that only the internal team reviews applications.
  [Application privacy](21-application-privacy.md)
- Admins can review, rate, brief and match in `/admin`.
  They cannot edit site copy, dates or edition status; those need a commit and deploy.

## Plan

### Wave 1: copy, fields and the reply

1. **G-02.** Capitalise "Maker" and "Makers" in all rendered copy, mail and admin labels, including Filament `$modelLabel`.
   Leave routes, slugs, CSS classes and code identifiers unchanged.
   Update the asserting tests and add a test that fails on a lowercase "maker" word in rendered public pages.
   Update the tone-of-voice row.
2. **G-01.** Add `applicationDeadline` (ISO date) to the edition JSON.
   Show it in the homepage edition band, the audience hero, the preparing notice and the form context.
   Derive the hand-typed date strings in Maker copy from edition data where possible.
   Closing stays manual through the status, as decided under P7.
   Blocked on the deadline date from Gwen.
3. **G-05.** Add one hint line to the organisation form introduction: answers may be in English, French or Dutch.
4. **G-06.** Add a required `organisation_languages[]` question (English, Dutch, French) above the contact-person question.
   Store it in the answers JSON; no migration.
   Label it clearly in the admin, show it in the Team editor and use it to pre-fill the Maker language filter.
5. **G-12.** Add a required `type=tel` Maker mobile number with the hint "Only for reaching you during selection and on the day itself, if email doesn't get through."
   Record the purpose in the legitimate-interest assessment and check the privacy notice wording.
6. **G-03.** Reply to Gwen: the backoffice covers applications and teams; site copy and dates go through the owner.
   Ask her to log in once to confirm her password, ask for the deadline and opening dates, and ask what she meant by themes.
   A draft in Dutch is in the review artifact.

### Wave 2: matchable organisations

7. **G-11.** Create an `App\Support\Themes` catalogue following `Skills`, used by both forms and the controller.
   Add the same checkboxes to the organisation form, a `themes` column with a backfill from `answers.themes`, admin filters and theme overlap in the Team editor.
   Both forms use the same ten themes (owner, 2026-11-05).
8. **G-09.** Add a "Print briefing" action per team and per organisation.
   It opens a print page with the refined brief, skills needed, themes and languages.
   It omits contact name, email and phone, so it stays within the current notice.
9. **G-08.** On the organisation confirmation page, show the submitted answers with a print control.
   No email copy, so the Resend statement in the notice stays true.

### Volunteers

10. **G-10.** The owner decided on 2026-11-05 that volunteers apply through the site with their own form; S-001 now includes them.
    Build it as a third audience on an audience registry, so the two-way audience code is refactored once.
    Fields, roles and availability still need scoping.
    A year-round case-study role also needs a retention check, and photo and video roles touch B-007 and B-036.

## Work batches

The work is split into three batches, each reviewed by the owner in one pass.
Each build batch is one thread, one branch and one curated commit.

| Batch | Requests | Concerns | Waits on |
| --- | --- | --- | --- |
| A. Copy and fields | G-02, G-01, G-05, G-06, G-12 | B-041, B-042, B-043 | Nothing |
| B. Matching and print | G-11, G-09, G-08 | B-044, B-045, B-046 | Batch A merged |
| C. Volunteers | G-10 | B-047 | Scoping as a Wayfinder map |

G-03 is the reply to Gwen, drafted in Gmail on 2026-11-05.

### Batch A: copy and fields

Plan steps 1 to 5.

- **Makers capitalisation (G-02).**
  Capitalise "Maker" and "Makers" in rendered public copy, content JSON, mail, the privacy page and Filament labels, including `$modelLabel`.
  Leave routes, slugs, CSS classes and identifiers unchanged.
  Add a regression test that fails on a lowercase "maker" word in rendered public pages.
  Update the tests that assert copy and the tone-of-voice row.
- **Application deadline (G-01).**
  Add an optional `applicationDeadline` (ISO date) to the edition JSON and render it only when set, so Gwen's date becomes a one-line content change.
  Show it in the homepage edition band, the audience hero, the preparing notice and the form context.
  Closing stays manual through the status.
- **Language hint (G-05).**
  One line in the organisation form introduction: answers may be in English, French or Dutch.
- **Organisation language (G-06).**
  A required `organisation_languages[]` question above the contact-person question, stored in the answers JSON.
  Clear admin label, shown in the Team editor, used to pre-fill the Maker language filter.
- **Maker mobile number (G-12).**
  A required `type=tel` field with the hint "Only for reaching you during selection and on the day itself, if email doesn't get through."
  Record the purpose in the legitimate-interest assessment and check the privacy notice wording.

Commit order inside the branch: capitalisation first, so later copy is written with the capital M.
The curated commit can collapse them.

### Batch B: matching and print

Plan steps 7 to 9.
Start after batch A is merged.

- **Shared themes (G-11).**
  An `App\Support\Themes` catalogue following `Skills`, used by both forms and the controller.
  The organisation question, a `themes` column with a backfill from `answers.themes`, admin filters and theme overlap in the Team editor.
- **Team briefing print (G-09).**
  "Print briefing" per team and per organisation in the admin: refined brief, skills needed, themes, languages.
  No contact name, email or phone.
- **Printable answers (G-08).**
  The organisation confirmation page shows the submitted answers with a print control.
  No email copy.
- One print stylesheet in `@layer components` serves both print views.

### Done criteria for every build batch

- PHP suite and production build pass.
- Running-page check at 1440px and 390px for every changed public page and admin screen, with screenshots in the review hand-off.
- Wiki updated in the same change: this page's status, the concern rows, `index.md` and `log.md`.
- Concerns closed or marked partly with a pointer back here.
- The hand-off lists every request ID with what changed and where to look, so the owner reviews the batch in one pass.

## Not doing

G-04 logo upload and G-07 keywords were skipped by the owner.
Shared themes (G-11) cover part of the keywords need.

### Batch A implementation

Built on 2026-11-05 on branch `fritsbits/review-gwen-website-requests`.
Screenshots were taken with a sample deadline of 31 January 2027 and status `open` to show the forms; neither is committed.

| ID | What changed | Where to look |
| --- | --- | --- |
| G-02 | "Maker" and "Makers" capitalised in public pages, content JSON, mail, privacy page, controller strings, Filament labels (`$modelLabel`) and validation messages. Routes, slugs, classes, identifiers and the preserved original-homepage preview are unchanged. A test fails on any lowercase maker word in the rendered home, Maker, organisation and privacy pages. | `/`, `/makers`, `/organisations`, `/privacy`, `/admin/makers` |
| G-01 | Optional `applicationDeadline` in the edition JSON, rendered as "Apply by 31 January 2027" by `<x-application-deadline>`. It shows in the homepage edition band, the audience hero, the preparing notice and, when open, the form context. It hides when unset or when the status is not `preparing` or `open`. The Maker date confirmation now reads `dates` from the edition. | Homepage edition band, audience heroes, `/organisations#application` |
| G-05 | "You can answer in English, French or Dutch." under the organisation introduction. | `/organisations`, left column of the application |
| G-06 | Required `organisation_languages[]` at the end of "About the organisation", above the contact-person question, with client and server validation. Admin labels distinguish the two language answers. The Team editor lists the organisation languages and pre-fills a now multi-select language filter. | `/organisations#application`; `/admin/teams/{id}/edit` |
| G-12 | Required `type=tel` "Mobile number" with the stated hint. Privacy notice updated to 5 November 2026 and the assessment gained a mobile-number section. | `/makers#application`, `/privacy`, [assessment](22-legitimate-interest-assessment.md#maker-mobile-number) |
| G-03 | Not part of the build; the reply is drafted in Gmail. | |

Decisions made while building:

- The deadline sits in the form context only while the status is `open`, because the preparing notice already shows it on the same page.
- The language filter is multi-select and lists Makers at level 3 or higher in any chosen language.
- Fixed in passing: the organisation hero stayed two columns on phones, squeezing its copy to 196px.
- Left alone: `bootstrap/providers.php` and `routes/console.php` fail the Pint style check; not touched here.

## Verification

Batch A: 131 PHP tests and the production build pass.
Running-page checks at 1440px and 390px passed for the home page, both audience pages in preparing and open states, the privacy notice, the organisation validation states, and the admin organisation, team and Maker screens, with no horizontal overflow.
Batches B and C are not started.
