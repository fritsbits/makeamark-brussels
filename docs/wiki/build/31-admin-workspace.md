---
title: Filament admin workspace
tags: [admin, implementation, review, matchmaking]
sources:
  - "../../raw/2026-10-18-admin-demo-data-request.md"
  - "../../raw/2026-10-18-admin-preview-login-report.md"
  - "../../raw/2026-10-18-admin-build-instruction.md"
  - "../../raw/2026-10-18-admin-review-request.md"
  - "../../raw/2026-10-18-admin-workflow-clarification.md"
  - "30-admin-discovery.md"
  - "../../superpowers/plans/2026-10-18-admin.md"
  - "../../../composer.lock"
  - "../../../app/Providers/Filament/AdminPanelProvider.php"
  - "../../../app/Services/Matchmaking.php"
phase: Build
updated: 2026-10-18
status: implemented and verified in running product; real account emails and Airtable import pending
---

# Filament admin workspace

## Direction and scope

The owner instructed the agent to build the admin, delegated one shared skill
vocabulary and confirmed the handoff to Gwen is a manual message after briefing
refinement. This supersedes the discovery document's separate design-approval
pause. The implementation uses Filament defaults. The navigation and routine
workflow choices below are the agent's implementation calls under that instruction.
[Build instruction](../../raw/2026-10-18-admin-build-instruction.md);
[plan](../../superpowers/plans/2026-10-18-admin.md)

The sitemap is saved as a private Notion draft:
[Make a Mark admin sitemap](https://app.notion.com/p/3fdd3ecc475c8122b9c8f4b5a3f638dd).
It is a document, not a new Notion project. No project status or sharing
permissions were changed.

## Admin pages

| Page | Behaviour |
| --- | --- |
| `/admin/organisations` | Search and filter by edition, status, skill, own review and team assignment. Compare average rating, review count and own rating. |
| Organisation detail | Preserved submission, operational selection status, own 1–5 rating/comment, shared assessment overview, refined briefing, skills needed and contact/internal notes. |
| `/admin/makers` | Search and filter by edition, status, main/backup skill and team assignment. Edit selection status, operational skills and internal selection notes. |
| Maker detail | Original motivation, work links, languages, availability and preferences remain visible alongside operational fields. |
| `/admin/teams` | Create/edit edition-specific teams, choose a selected/confirmed organisation and makers, filter candidates by skill/language, see main/backup coverage and unmet skill needs. Deleting a team releases its assignments and preserves applications/reviews. |
| `/admin/users` | Active super admins create/edit/deactivate individual admin accounts and manage super-admin access. Other organisers cannot open this area. |
| Login, profile, password reset | Individual session login, own profile/password changes and standard mail-based password reset. Mail delivery requires working production mail configuration. |

Sources: [application resources](../../../app/Filament/Support/ApplicationResource.php),
[organisation review](../../../app/Filament/Resources/OrganisationResource/Pages/EditOrganisation.php),
[team resource](../../../app/Filament/Resources/TeamResource.php),
[accounts](../../../app/Filament/Resources/UserResource.php),
[panel](../../../app/Providers/Filament/AdminPanelProvider.php).

Operational status labels are Applied, Needs information, Contacted, Selected,
Confirmed, Waitlisted, Rejected and Withdrawn. These are implementation defaults,
not a reconstructed Airtable transition model. Status/rating changes do not
send messages or automatically select an applicant. Missing ratings are excluded
from the average. Gwen's individual maker-selection process has no added
collective rating requirement.
[Statuses](../../../app/Support/ApplicationStatus.php);
[review action](../../../app/Filament/Resources/OrganisationResource/Pages/EditOrganisation.php)

## Skills and briefing handoff

Organisations' skills needed and makers' main/backup skills share one
nineteen-option catalogue. Art director maps to Art direction; copywriting
variants map to Content development / copywriting; historical web-development
labels map to Back end development or Front end development. PR and Service
design remain available to both audiences. Original submitted free text is
preserved, including unmatched wording that needs an organiser's mapping.
[Catalogue](../../../app/Support/Skills.php);
[migration](../../../database/migrations/2026_10_18_010000_create_admin_workspace.php)

Frederik updates the refined briefing and skills needed after organisation
meetings. The team editor displays these for Gwen. There is no readiness gate,
message composer or automatic notification: Frederik sends the agreed message
outside the admin when the briefings are ready. Contact notes can record the
useful outcome without importing his mailbox.
[Owner instruction](../../raw/2026-10-18-admin-build-instruction.md);
[handoffs](30-admin-discovery.md#confirmed-handoffs);
[team form](../../../app/Filament/Resources/TeamResource.php)

## Access and data integrity

All active organisers can review, refine briefings, select makers and match
teams. Each organisation assessment belongs to the signed-in reviewer; a
submitted reviewer ID cannot overwrite another organiser's assessment.
Only active super admins can manage accounts. Account management prevents
self-lockout and removal of the last active super admin. Profile and reset
passwords share the initial-account policy of at least twelve characters with
mixed case, a number and a symbol.
[Policies](../../../app/Policies/ApplicationPolicy.php);
[review ownership](../../../app/Filament/Resources/OrganisationResource/Pages/EditOrganisation.php);
[account service](../../../app/Services/AdminAccounts.php);
[password defaults](../../../app/Providers/AppServiceProvider.php)

Teams require selected/confirmed applicants of the correct audience and
edition. A maker application can belong to only one team; each organisation
application has at most one team. Assignment writes are transactional and
constrained in the database. An application must be removed from its team
before its eligibility/edition changes. Application updates and assignment
updates lock the same application rows. Team edits use a version check so a
stale page cannot overwrite a newer save; repeated saves on the same page
advance the page's version correctly. No arbitrary team-size limit is imposed.
[Matchmaking](../../../app/Services/Matchmaking.php);
[application updates](../../../app/Filament/Support/EditApplication.php);
[team edits](../../../app/Filament/Resources/TeamResource/Pages/EditTeam.php);
[migration](../../../database/migrations/2026_10_18_010000_create_admin_workspace.php)

Submitted answers remain separate from editable operational data. Text renders
escaped; only valid HTTP/HTTPS portfolio/website URLs become links.
[Answer rendering and fields](../../../app/Filament/Support/ApplicationResource.php);
[update allowlist](../../../app/Filament/Support/EditApplication.php)

## Account setup

The requested accounts are Frederik (super admin), Piet, Gwen and Bernard.
Their actual email addresses have been requested; no real addresses or shared
passwords are guessed. Create the first real super admin interactively:

```sh
php artisan admin:create --name=Frederik --super-admin
```

The command prompts for email and a hidden password with confirmation. It
does not accept a password command-line option or print the password. Once
signed in, use Admin accounts to create the other organisers with individual
passwords. Password reset needs configured mail; no invitations were sent.
[Setup command](../../../app/Console/Commands/CreateAdmin.php);
[account UI](../../../app/Filament/Resources/UserResource.php)

For immediate local review, run `php artisan admin:preview`. It creates a
clearly named local preview super admin and writes copyable credentials to
`storage/app/private/admin-preview.txt` with permissions `0600`. The JSON
source is also retained privately. Use the text file when copying the password:
JSON escapes literal backslashes, which caused the owner's first login attempt
to fail. The account/password were unchanged; browser login using the exact text
file values was verified after the fix.
[Login report](../../raw/2026-10-18-admin-preview-login-report.md)

The command
prints only the file location and admin URL, reuses matching credentials on
later runs and refuses account/file conflicts. It cannot run in production.
The local preview login has been created; it is separate from the four real
accounts and sends no email.
[Preview command](../../../app/Console/Commands/PreviewAdmin.php);
[preview tests](../../../tests/Feature/AdminPreviewTest.php)

## Verification and remaining work

### Local test data

At the owner's request, the running local database now contains six fictional
organisation applications, eighteen fictional maker applications, twelve
sample assessments and two starter teams for 2027. All organisations begin
with `Demo ·`; maker/reviewer names identify them as demo records and contacts
use the reserved `admin-demo.example.invalid` domain. The three sample reviewer
identities are inactive non-admins with random passwords. The existing preview
login is unchanged; no mail is sent.
[Request](../../raw/2026-10-18-admin-demo-data-request.md);
[dataset](../../../database/seeders/AdminDemoSeeder.php)

Useful test journeys:

- Review **Demo · Voix du Canal**, which has no ratings or refined briefing yet.
- Open **Demo · Atelier youth campaign**: its draft team needs copywriting.
  **Lina Vermeer (demo)** is a selected, unassigned copywriter to try adding.
- Create a team for **Demo · Passerelle Numérique**, a selected organisation
  with a refined brief and no team. **Samir, Mila and Alex** are available
  service/UX/web examples.
- Compare **Demo · Quartier Vert campaign**, a confirmed team with its required
  skills covered. Filter makers by application/selection/waitlist/rejection/
  withdrawal status and inspect the organisation needing more information.

Re-run `php artisan admin:seed-demo` to add missing examples. Existing records,
passwords and edits to seeded briefs/reviews/teams are preserved. The command
is local-only and the direct seeder also refuses production. These are test
records, not an Airtable import, and do not resolve B-004.
[Command](../../../app/Console/Commands/SeedAdminDemo.php);
[preservation/guard tests](../../../tests/Feature/AdminDemoSeedTest.php)

### Checks

Verification completed on 2026-10-18:

- Full PHP suite after adding demo data: 68 tests, 392 assertions pass. Coverage includes access,
  audience isolation, own-review writes, preserved answers, assignment
  integrity/rollback, repeated saves, candidate filtering, team removal,
  account management, local preview setup and password policy.
- Production Vite build passes. Existing self-hosted font URL warnings remain;
  browser verification loads the running assets.
- Pint, PHP/JavaScript helper syntax checks and Composer dependency audit pass.
- Independent security review found three issues, all fixed and rechecked:
  refreshing the team version after saving, locking applications while changing
  eligibility, and using one strong password policy across create/profile/reset.
- Authenticated desktop/browser checks verify persisted briefing and own review,
  maker selection, adding a team member, two saves without reload, ordinary-admin
  account restrictions, super-admin account pages, profile and reset entry.
  Mobile team editing has zero horizontal overflow. Desktop organisation/team
  and mobile team screenshots were inspected in one final screenshot pass.
- Browser fixtures use unique synthetic contacts, are removed after the run,
  and do not affect the private preview account. No real selection emails or
  invitations were sent.

Reproduce the browser verification with:

```sh
NODE_PATH="$(npm root -g)" node scripts/admin-browser.cjs
```

The helper refuses production fixture setup. Screenshots are written under
`/tmp/makeamark-admin-screenshots/`.
[Browser helper](../../../scripts/admin-browser.cjs);
[fixture isolation](../../../scripts/admin-browser-fixtures.php)

Automated verification sources:
[Panel tests](../../../tests/Feature/AdminPanelTest.php);
[domain tests](../../../tests/Feature/AdminDomainTest.php);
[account tests](../../../tests/Feature/AdminAccountsTest.php);
[preview tests](../../../tests/Feature/AdminPreviewTest.php);
[demo seeding tests](../../../tests/Feature/AdminDemoSeedTest.php).

Remaining operations: provision the four real email-based accounts (B-009),
complete the full Airtable export/import inventory (B-004), and configure mail
before relying on password reset or applicant receipts (B-002). The admin
uses applications already submitted through the Laravel website; historical
Airtable applicants are not reconstructed from screenshots. Maker selection
emails remain manual/unscoped (B-010). The broader Build phase remains open.
[Build concerns](01-build-concerns.md)
