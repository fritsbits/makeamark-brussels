---
title: Application legitimate-interests assessment
tags: [privacy, lawful-basis, applications, build]
sources:
  - "../../raw/2026-10-18-application-privacy-and-operations-decisions.md"
  - "../../raw/2026-10-18-application-privacy-decision-instruction.md"
  - "../../raw/2026-10-18-application-operations-update.md"
  - "../../raw/2026-10-18-airtable-forms.md"
  - "../../raw/2026-10-18-legitimate-interest-and-retention-guidance.md"
  - "../../raw/2026-11-05-gwen-form-requests.md"
  - "../../raw/2026-11-05-volunteer-decisions.md"
  - "../../raw/2026-11-07-volunteer-wireframe-approval.md"
phase: Build
updated: 2026-11-07
status: legitimate interests selected for current application lifecycle; annual review required
---

# Application legitimate-interests assessment

## Purpose and selected basis

Impact Studio BV needs to review applications, select participants, and
organise the Make a Mark Brussels 2027 event. Applicants provide information
specifically to apply. The owner says only the internal team listed on the
website reviews the information and it is not shared with other organisations.
[Owner decisions]

Decision: use GDPR Article 6(1)(f), legitimate interests, for application
review, participant selection, event organisation, and keeping prior answers
available to a returning applicant for up to three years after their latest
application. The owner delegated this decision and the retention period. [Owner
decision instruction; operations update]

## Preliminary checks

- **Legitimate interest:** selecting participants and organising the edition
  are present, specific purposes directly related to an application. Retaining
  prior answers briefly for returning applicants supports the recurring
  edition process and the owner's future-account direction.
- **Necessity:** contact information and answers about work/skills, mission,
  challenge, languages, and availability support review and coordination. The
  owner removed the dietary-requirements field. The reviewer-requiredness
  audit remains open, so necessity of every remaining field is not established.
  [Airtable forms capture; Owner decisions]
- **Balancing:** people can reasonably expect that their application is
  reviewed by the event organisers. Review is internal and manual. The
  retention is limited to three years after the most recent application, with
  an annual review and deletion/anonymisation at expiry. Access is restricted
  to the internal review team. There is no marketing use or automated
  selection. Applicants are informed of and can exercise the right to object.
  Free-text fields could still invite unnecessary sensitive details; tell
  applicants not to include them. These safeguards support the balance.

## Maker mobile number

Added 2026-11-05 on Gwen's request (G-12). The Maker form requires a mobile
number. The stated purpose is reaching a Maker during selection and on the
day itself, if email does not get through. The notice and the form hint state
this purpose; the number is not used for marketing or shared outside the
internal team. [Gwen's requests](../../raw/2026-11-05-gwen-form-requests.md);
[plan](51-gwen-form-requests.md)

- **Necessity:** interpretation: a phone call is the fallback on the event day
  when an email goes unread. Making it required, rather than optional, is the
  owner's decision. The earlier Airtable Maker form had no phone field, so
  there is no prior practice to compare with.
- **Balancing:** the number joins data already collected, with the same
  internal access, retention and right to object. The hint tells applicants
  the limited purpose before they enter it. Reassess if the number is used for
  anything beyond selection and the day itself.

## Volunteer applications

Added 2026-11-07 with the volunteer form (G-10, B-047).
Purpose: assess volunteer applications, assign the photographer, videographer and social media roles, and coordinate volunteers for the edition.
The basis stays Article 6(1)(f), as for Makers and organisations.
[Scoping](52-volunteer-form-scoping.md#4-privacy-and-retention); [owner decisions](../../raw/2026-11-05-volunteer-decisions.md)

- **Necessity:** each field has a stated purpose in the [scoping](52-volunteer-form-scoping.md#2-form-fields).
  Name, email and mobile number reach the volunteer; roles, examples of work and day parts decide the assignment; languages are asked only of social media managers.
  The optional notes field carries equipment and experience, and its hint asks applicants to leave out sensitive details.
  Left out on purpose: motivation, referral, themes, age, teams, and the credit line, which the owner dropped from the form on 2026-11-07 ([approval](../../raw/2026-11-07-volunteer-wireframe-approval.md)).
- **Balancing:** the same internal-only access, manual review, no automated selection, no marketing and the same right to object as the other audiences.
  Volunteers get no admin access.
  Use and credit of the photos, film and posts is agreed separately in the volunteer agreement on selection (V-07), not through the form.
- **Retention:** the three-year rule applies unchanged; a volunteer role ends when the next edition opens, and volunteering again means a new application (V-06).
- **Reassess if** volunteers get system access, the data feeds anything beyond role assignment, or a year-round role returns.

## Gaps before approval

This is the controller's operational determination based on the current
purpose and form. Reassess if a form field, reviewer group, retention period,
provider, or purpose changes. Self-service accounts and applicant-managed
reuse are not covered and need a separate purpose/compatibility review and
notice before implementation. Confirm production host access controls and
rights-request handling as account setup is completed.

The notice names the interests pursued and explains the right to object. No
consent checkbox is needed for this purpose. Future account functionality
requires another review. [GDPR Article 13; EDPB Guidelines 1/2024]
