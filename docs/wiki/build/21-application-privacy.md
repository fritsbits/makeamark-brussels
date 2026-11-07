---
title: Application privacy and retention
tags: [privacy, applications, build]
sources:
  - "../../raw/2026-10-18-application-privacy-and-operations-decisions.md"
  - "../../raw/2026-10-18-application-operations-update.md"
  - "../../raw/2026-10-18-application-provider-handling.md"
  - "../../raw/2026-10-18-application-privacy-decision-instruction.md"
  - "../../raw/2026-10-18-application-privacy-requirements.md"
  - "../../raw/2026-10-18-airtable-forms.md"
  - "../../raw/2026-11-05-production-mail-setup.md"
  - "../../raw/2026-11-05-volunteer-decisions.md"
phase: Build
updated: 2026-11-07
status: lawful-basis and retention decisions made; account setup verification remains
---

# Application privacy and retention

## Decisions

- **Lawful basis:** Article 6(1)(f) GDPR, legitimate interests, for receiving
  and reviewing applications, selecting participants, organising the edition,
  and keeping a limited record so returning applicants can refer to prior
  answers when applying for a later edition. The written assessment is in
  [22-legitimate-interest-assessment.md](22-legitimate-interest-assessment.md).
- **Retention:** review applicant records once a year. Keep an application for
  up to three years after the applicant's latest application, then delete or
  irreversibly anonymise it unless a new application or separately documented
  legal need justifies a new period. An applicant can request deletion sooner.
  No automated deletion is active. Future account functionality is not covered
  by this decision; assess its purpose and notice before building it.
- **Reviewers:** only the internal Make a Mark team listed on the site. Service
  providers may process data to host the app and deliver email, but do not
  review or select applicants.
- **No consent checkbox:** consent is not the basis for assessing an
  application. Applicants may object to processing that relies on legitimate
  interests.

## Provider findings

The owner's screenshots confirm the shared DigitalOcean droplet is in
Amsterdam 3 (AMS3) and managed with Laravel Forge. Automated backups are
offered in the dashboard, but the screenshot does not show them enabled.
Resend will handle only the short receipt email (name, email, audience, year),
not the application answers; its published terms place email data in the US
and provide contractual transfer safeguards. Cloudflare and one.com support
domain/email. The public notice names these providers without enumerating
subprocessors or services not used by the signup flow. [Owner's screenshots;
provider source review]

Before receipts go live, configure Resend and a verified sender. Confirm the
destination mailbox when setting up `hello@`. Future admin alerts and account
features are outside this notice's current scope.

## Collection notice copy

Impact Studio BV (BE 0652.723.886), Kasteeldreef 47, 1083 Ganshoren, is
responsible for personal information submitted through the Make a Mark
Brussels Maker, volunteer and organisation applications. Privacy contact: Frederik
Vincx at hi@frederikvincx.com.

We use contact information, application answers, and basic technical request
logs to assess applications, select participants, assign volunteer roles, secure the application site,
organise the 2027 edition, and keep prior application answers available for a
returning applicant for up to three years after their latest application. If
you apply again, the team will ask you to review and update the information.
We will not automatically submit an old application or make an automated
selection decision. We do not use application details for marketing.

Our basis is our legitimate interest in running the event and handling
applications effectively across editions. We limit access to the internal
Make a Mark review team and review records yearly. You may object to this
processing or ask us to access, correct, restrict, or delete your information
by contacting hi@frederikvincx.com. We will explain if a legal exception means
we cannot fulfil a request. You may also complain to the [Belgian Data
Protection Authority](https://www.autoriteprotectiondonnees.be/citoyen/agir/introduire-une-plainte).

Our app is hosted on the existing DigitalOcean server in Amsterdam and managed
with Laravel Forge. Resend sends confirmation emails from its Ireland region
and receives only the recipient address, applicant name, audience type, and
edition year, not application answers. Resend may also process email data in
the United States under contractual transfer safeguards. One.com registers our
domain and Cloudflare runs its DNS. Cloudflare forwards mail sent to
`hello@letsmakeamark.brussels` to our team mailbox at Google (Gmail), so both
handle those messages. We keep application content out of email
notifications.

The public notice at `/privacy` carries this provider wording since 2026-11-05.
One.com is the registrar per the `.brussels` RDAP record; the Resend region and Gmail destination come from the [mail setup](../../raw/2026-11-05-production-mail-setup.md).

Providing required application information is necessary for the team to review
your application. If you leave required answers blank, the team cannot assess
it. The dietary-requirements question is not collected. Please do not include
health, identity-document, or other sensitive information in free-text answers.

Makers and volunteers give a mobile number, used only to reach them during
selection and on the day itself. A selected volunteer receives a separate
volunteer agreement on how their photos, film or posts are used and credited.
A volunteer role ends when the next edition opens; volunteering again means a
new application.

The public notice at `/privacy` names volunteers since 2026-11-07
([scoping](52-volunteer-form-scoping.md#privacy-notice-changes)).

This notice does not cover future user accounts or self-service reuse tools;
those features will receive a separate purpose and privacy review before they
are introduced.

## Remaining operational checks

- Resend and the verified `hello@letsmakeamark.brussels` sender are live since 2026-11-05.
  The receipt test carried only name, audience and year; open and click tracking are off.
  [Mail setup](46-production-rollout.md#outgoing-mail)
- The owner chose `makeamarkbxl@gmail.com` as the `hello@` destination; Email Routing waits on its verification.
  The public notice now names Google (Gmail) for mail sent to `hello@`.
- At the annual retention review, delete or anonymise applications reaching
  the three-year limit. This is a manual process; no automatic purge is active.
  Include volunteer applications (`/admin/volunteers`); their roles end when the
  next edition opens, but the records follow the same three-year rule.

This is a product privacy notice and internal processing decision based on the
owner's instructions and official provider documentation reviewed on
2026-10-18. It should be reviewed again if the services, retention, or purpose
changes. [Provider source review; GDPR]
