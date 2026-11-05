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
status: planned; not implemented
---

# Gwen's 2027 form requests

Gwen Dubois sent twelve requests for the 2027 application forms on 20 October 2026.
The owner decided on each one on 5 November 2026: nine to build now, two skipped, one to discuss.
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
| G-10 | Volunteer application form | Discuss | B-047 |
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
  Unknown until Gwen confirms.
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
   Confirm with Gwen whether the ten themes still fit the youth-work focus.
8. **G-09.** Add a "Print briefing" action per team and per organisation.
   It opens a print page with the refined brief, skills needed, themes and languages.
   It omits contact name, email and phone, so it stays within the current notice.
9. **G-08.** On the organisation confirmation page, show the submitted answers with a print control.
   No email copy, so the Resend statement in the notice stays true.

### To discuss

10. **G-10.** A volunteer form reopens S-001 ("person signup: Makers only").
    The options are a third audience on an audience registry, a role on the Maker form, or recruiting directly without the site.
    The agent recommended a third audience if the answer is yes; a Maker-form role was advised against.
    A year-round case-study role also needs a retention check, and photo and video roles touch B-007 and B-036.

## Not doing

G-04 logo upload and G-07 keywords were skipped by the owner.
Shared themes (G-11) cover part of the keywords need.

## Verification

Nothing is implemented yet.
Each wave needs the PHP suite, the production build and running-page checks at desktop and phone widths before it is marked done.
