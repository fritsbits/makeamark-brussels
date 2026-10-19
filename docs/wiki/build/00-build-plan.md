---
title: Build plan
tags: [plan, build, website]
sources:
  - "../../raw/2026-10-19-worktree-merge-tests.json"
  - "../../raw/2026-10-19-launch-review-final-feedback.json"
  - "../../raw/2026-10-19-launch-final-fixes-verification.json"
  - "../../raw/2026-10-19-launch-visual-review-selection.md"
  - "../../raw/2026-10-19-launch-selected-fixes-verification.json"
  - "../../raw/2026-10-19-solumob-video-sources.md"
  - "../../raw/2026-10-19-launch-audit-request.md"
  - "../../raw/2026-10-19-launch-audit-evidence.json"
  - "../../raw/2026-10-19-maker-hero-lead-date-swap-request.md"
  - "../../raw/2026-10-19-homepage-detail-feedback.md"
  - "../../raw/2026-10-19-homepage-carousel-additional-photo-batch.md"
  - "../../raw/2026-10-19-date-lockup-d06-d-selection.md"
  - "../../raw/2026-10-19-date-lockup-d06-refinements-request.md"
  - "../../raw/2026-10-19-date-lockup-variations-request.md"
  - "../../raw/2026-10-19-homepage-audience-sentence-request.md"
  - "../../raw/2026-10-19-maker-hero-title-alignment-request.md"
  - "../../raw/2026-10-19-maker-hero-arrangement-request.md"
  - "../../raw/2026-10-19-maker-hero-proportions-request.md"
  - "../../raw/2026-10-19-maker-hero-title-shortening-request.md"
  - "../../raw/2026-10-19-maker-hero-title-selection-2.md"
  - "../../raw/2026-10-19-maker-hero-padding-request.md"
  - "../../raw/2026-10-19-maker-hero-equal-padding.md"
  - "../../raw/2026-10-19-application-payoff-request.md"
  - "../../raw/2026-10-19-copywriting-benefits-selection.md"
  - "../../raw/2026-10-19-organisation-invitation-direction.md"
  - "../../raw/2026-10-19-homepage-wordmark-scroll-request.md"
  - "../../raw/2026-10-19-homepage-wordmark-spacing-request.md"
  - "../../raw/2026-10-19-audience-arrangement-selection.md"
  - "../../raw/2026-10-18-homepage-carousel-sizing-feedback.md"
  - "../../raw/2026-10-18-homepage-carousel-request.md"
  - "../../raw/2026-10-18-button-rollover-selection.md"
  - "../../raw/2026-10-18-public-typography-and-organisation-parity-request.md"
  - "../../raw/2026-10-18-makers-typeset-selection.md"
  - "../../raw/2026-10-18-makers-typeset-request.md"
  - "../../raw/2026-10-18-button-rollover-request.md"
  - "../../raw/2026-10-18-makers-hardening-request.md"
  - "../../raw/2026-10-18-homepage-boldness-selection.md"
  - "../../raw/2026-10-18-homepage-boldness-request.md"
  - "../../raw/2026-10-18-makers-polish-selection.md"
  - "../../raw/2026-10-18-makers-polish-request.md"
  - "../../raw/2026-10-18-homepage-polish-selection.md"
  - "../../raw/2026-10-18-inline-application-request.md"
  - "../../raw/2026-10-18-admin-demo-data-request.md"
  - "../../raw/2026-10-18-admin-build-instruction.md"
  - "../../raw/2026-10-18-admin-workflow-clarification.md"
  - "../../raw/2026-10-18-admin-review-request.md"
  - "../../raw/2026-10-18-all-photo-treatment-selection.md"
  - "../../ux/_strategy.md"
  - "../../ux/_scope.md"
  - "../../ux/_structure.md"
  - "../../ux/_skeleton.md"
  - "../../raw/2026-10-18-notion-make-a-mark-2027.md"
  - "../../raw/2026-10-18-airtable-forms.md"
  - "../../raw/2026-10-18-application-privacy-requirements.md"
  - "../../raw/2026-10-18-application-provider-handling.md"
  - "../../raw/2026-10-18-application-privacy-decision-instruction.md"
  - "../../raw/2026-10-18-application-operations-update.md"
  - "../../raw/2026-10-18-application-privacy-and-operations-decisions.md"
  - "../../superpowers/plans/2026-10-18-edition-site.md"
  - "../../raw/2026-10-18-risoprint-tool-request.md"
  - "../../raw/2026-10-18-site-risoprint-selection.md"
  - "../../raw/2026-10-18-homepage-photo-reveal-selection.md"
  - "../../raw/2026-10-18-image-mapping-request.md"
  - "../../raw/2026-10-18-homepage-top-photo-selection.md"
  - "../../raw/2026-10-18-homepage-carousel-photo-batch.md"
  - "../../raw/2026-10-19-carousel-same-aspect-ratio.md"
  - "../../raw/2026-10-19-form-typography-request.md"
  - "../../raw/2026-10-19-form-typography-feedback.md"
  - "../../raw/2026-10-19-maker-form-copy-request.md"
  - "../../raw/2026-10-19-organisation-page-parity-selection.md"
phase: Build
updated: 2026-10-19
status: implementation underway; 2027 signup open, privacy notice implemented, provider configuration remains
---

# Build plan

## Solumob case-study evidence

The owner requested detailed research from the historical Solumob vlog,
supplied transcript and application. The [evidence dossier](44-solumob-video-analysis.md)
records visible work, presented results, elapsed timecodes and preserved
stills. It separates the filmed 16 March 2019 edition from the repeat
application for 2020. This is content research; no case-study route is built.
B-036 carries the remaining final-asset, credits, usage and outcome questions.

## Livegang-audit

The owner's [final visual-review selection](../../raw/2026-10-19-launch-review-final-feedback.json)
chooses five changes, L-02 through L-06, which are implemented and verified on
the running site. L-01's original text color is restored as requested. The
[receipt](43-launch-audit.md#goedgekeurde-wijzigingen) records field boundaries, menu
focus, readable dates, the new sharing image and required youth-work
confirmation. Build and all 115 PHP tests pass after the stale copy and markup
assertions were aligned with the approved changes. L-07 is resolved in the
[integration record](45-worktree-integration.md). B-035 is partly resolved;
production release checks and unselected L-01 and L-08 through L-10 remain.

The mobile menu fills the viewport in Marker Red, with cream display links and
the close control and wordmark held above the overlay. Build and running-page
checks passed at 390×844 and 320×568. [Implementation record](41-mobile-menu.md).

The homepage hero no longer shows the edition date. Its introduction and the
right-column copy under “The 2027 edition” use the Makers lead type treatment,
with colors set for contrast against each section. Running desktop and mobile
verification is complete. [Receipt](37-homepage-lead-treatment.md).

The homepage no longer shows the hero photo archive caption or carousel
counter. The edition section reuses the Makers date component, the evidence
links are unbordered, and the About team list has no surrounding dividers.
[Owner feedback and verification](37-homepage-lead-treatment.md#homepage-detail-refinements).

The audience form typography now uses the shared 17px reading size for labels,
answers, guidance, privacy copy, sending status and validation errors. Secondary
guidance uses Ink Brown (`#6B5645`) and the 300 reading weight; validation stays
Ink. Running-page review is complete for makers and organisations at desktop
and mobile widths.
[Decision and verification](35-form-typography-normalization.md).

The maker form copy and hierarchy refinements have been applied to organisations
as selected: both hero/date arrangements and application introductions use the
Makers treatments; the organisation payment FAQ and required-fields footer note
are removed. Organisation copy and required markers remain specific to that
form. [Maker decision](36-maker-form-copy.md); [organisation parity](38-organisation-page-parity.md).

The owner selected D06-D after comparing weekday-first date directions and
subtle stamp refinements. The makers page implements the 24px Saturday in
cream text, a cream divider, and a 52px day numeral beside month/year. Desktop,
phone and narrow running-page checks passed; B-025 is closed.
[Selection and verification](34-date-lockup-variations.md#d06-d-selected-and-implemented)

The makers hero now places the D06-D date at the top of its right column,
aligned with the headline's first row in height and top position. The Paper
Cream lead and application action form a group below it, aligned with the
headline bottom at wide desktop widths. The hero stacks into one column at
860px and below. Build and running checks at seven widths passed with no
overflow or JavaScript errors; desktop and phone captures were reviewed. This
supersedes the earlier lead/title top alignment.
[Request](../../raw/2026-10-19-maker-hero-lead-date-swap-request.md);
[implementation and verification](28-maker-hero-arrangement.md#current-lead-and-date-order)

The follow-up spacing pass adds a cream-rule edge to the nav, a 20–36px gap
before the audience hero, and more top/bottom padding inside the red panel.
[Spacing record](28-maker-hero-arrangement.md#vertical-spacing-refinement)

The owner initially selected an editorial “27 / Saturday / March 2027” date
stamp under the title, without icon or location, and removal of the separate
Edition link. A later refinement widened the title track, shortened the title
to “Use your creative skills to make a difference,” and moved the date into
the right column. The latest refinement places it above the lead and action. The linked wordmark remains the
route home. The date is derived from edition `startDate`.
[Implementation](28-maker-hero-arrangement.md)

The owner selected “Design and creativity are for everyone” for the homepage,
and the recommended maker and organisation audience titles. These H1 changes
are applied; running-product verification remains open under B-021.
[Copy decision and receipt](28-copywriting-benefits.md)

The owner requested a more delightful application payoff and removal of the
blue section border. Both audience receipts now use the site's print palette,
clear review/delivery guidance and a reduced-motion-aware stamped check.
Production build, 39 application tests, real desktop/mobile submissions,
keyboard, reduced-motion and no-JavaScript checks pass; B-019 is closed.
[Application payoff](20-application-intake.md#application-payoff)

The owner-selected audience arrangement A is implemented: shared heading,
description and action rows, descriptions capped at 34ch, and stacking at
860px. Production build and six-width running checks pass. B-018 is closed.
Organisation-copy suggestions now follow the owner's direction: invite
creative help for a deferred project, with makers producing the work. Wording
now opens with direct audience labels and continues into the invitation as one
sentence, with tighter spacing between the label and copy.
[Copy direction](../../raw/2026-10-19-organisation-invitation-direction.md);
[owner request and implementation](18-audience-arrangement.md)

The owner-requested homepage carousel now has eleven distinct photos in a
consistent 4:3 frame: the existing 2026 group photo first, followed by seven
supplied landscape photos in event-day order. The portrait archive photo is
excluded because it would require a severe crop. Each image grows to the
container width, following the owner's sizing correction. Photographer credits
and publication permissions remain open.
[Implementation and verification](17-homepage-photo-carousel.md)

The past-client roster now uses the live site's red-band layout after the
homepage photo evidence and before About. Klimaatzaak was added on the owner's
instruction; the displayed list total is 56. [Receipt](32-homepage-client-roster.md)

The About heading now spans the full homepage grid above the organiser list
and logo, preventing an orphaned “Mark.” [Receipt](33-homepage-about-heading.md)

The homepage navigation wordmark uses primary Ink and opens at 55px on wide
screens before shrinking to 22px when scrolling begins. Narrow screens start
smaller to fit beside the menu control; the effect respects reduced motion.
[Decision and verification](39-homepage-wordmark.md).

The homepage wordmark now has generous vertical breathing room while expanded,
with a shorter sticky row after scrolling. Hero top spacing is slightly reduced
to balance the enlarged navigation. [Spacing receipt](40-homepage-wordmark-spacing.md).

## Purpose

Implement the approved phase-aware Make a Mark Brussels website using the
existing Laravel, Blade, Livewire, Flux Pro, Tailwind, and Vite stack. The
English-first edition hub serves makers and social organisations as equal
applicant groups, with a separate information journey for each. [UX Strategy;
Structure; Skeleton]

## Button rollover review

The owner reported an unreadable edition-action rollover and requested options.
The shared pill contrast defect is fixed. The owner then selected R02's paired
ink/label sweep, now implemented and browser-verified across the homepage and
both audience pages. Existing filled CTA styling is preserved; B-017 is closed.
[Selection and receipt](16-button-rollover-review.md#selected-implementation-receipt)

The owner subsequently removed the homepage edition action, “Explore ways to
take part”. The edition status label remains for non-open states.
[Removal](16-button-rollover-review.md#edition-action-removal)

## Selected homepage boldness

The owner selected all four homepage suggestions, H01–H04, and asked to keep
the hero photographs. The group photo now grows on scroll, the poster opening
retains both photos at every width, both audience invitations are larger and
quiet entrance/link motion respects reduced-motion preferences. All changes
are implemented and verified across five widths, keyboard/touch, no-JavaScript
and reduced-motion checks. [Selection and receipt](15-homepage-boldness-review.md#selected-implementation-receipt)

## Shipped surface

The owner selected the distilled version, with the homepage process section
omitted. Canonical home and audience routes now use that version. The previous
homepage is preserved at `/tools/messaging/original`; the local comparison
displays it beside the canonical homepage at matching desktop/mobile widths.
Tools are noindex and outside the public sitemap.
[Selection and verification](13-messaging-review.md).

All public photos now share the hero's neutral monochrome styling, rounded
corners and shadow, and the group's expanding original-color reveal. No
public homepage photo selects Riso. The previous saved treatment remains in the local tool.
[Current photo record](11-site-photo-treatments.md#current-monochrome-treatment).

- The home page introduces the edition and both applicant journeys, combines
  edition/youth details, and retains a Brussels photo carousel, evidence links and
  organisers. Process and cost answers live on audience pages.
  [Selected homepage](13-messaging-review.md); [Skeleton]
- `/makers` and `/organisations` explain audience fit, commitments, review,
  and the event process, with their application forms directly below. Legacy
  `/apply` links redirect to the audience page's form.
  [Inline application decision](20-application-intake.md#forms-on-the-information-pages)
- Edition content remains file-based. Application submissions are stored in
  Laravel and gated by Edition status. The owner set the 2027 status to
  `open`; maker and organisation signup is active. [Owner instruction;
  Application intake]
- The maker dietary-requirements field was removed. The footer credits Impact
  Studio. Application receipts are prepared through Resend, pending the API
  key and a verified sender. Application records receive an annual review and
  are deleted or anonymised no later than three years after the applicant's
  latest application. No automatic purge runs. Future account features need a
  separate purpose review. [Application privacy and retention]
- GitHub Actions runs Laravel feature tests and the Vite production build on
  pushes and pull requests. [Implementation plan]
- Earlier implementation: the owner-selected saved risoprint treatment was applied to the homepage
  group photo only, before the shared monochrome/reveal selection superseded it. Other photos could opt in through content keys; treated
  responsive assets are generated during the build. The owner selected an
  expanding reveal of the original and requested a larger frame. The agent
  applied the 600px preset and associated motion defaults. Running desktop,
  mobile, reduced-motion and no-JavaScript checks pass for the photo update.
  [Photo treatments](11-site-photo-treatments.md);
  [reveal selection](../../raw/2026-10-18-homepage-photo-reveal-selection.md)

## Verification

The [homepage polish review](14-homepage-polish-review.md) presents seven
independent current/proposed choices in Lavish. The owner selected P01–P06
and removal of the duplicate hero links; these are implemented and verified
across five widths. Build, 70 PHP tests and desktop/mobile screenshot checks
pass. P07 remains unselected.

All-photo styling and rollover pass the production build, three PHP photo
tests, five JavaScript tests and running desktop/mobile/reduced-motion/
no-JavaScript checks. All 12 photos use the same component. Desktop and
mobile screenshots were inspected. [Verification](11-site-photo-treatments.md#current-monochrome-treatment).

The [global design review](12-global-design-review.md) records an initial
homepage refinement: temporary international photos, monochrome hero collage,
tonal red process panels and darker-cream audience panels. B-006 tracks final
direction; B-007 tracks replacement with sourced Brussels photos. Browser
checks and the production build pass; two pre-existing PHP assertions remain
out of sync with the live site.

The owner supplied ten photographs and selected two mapped images for the hero.
The mingling-at-gathering and planning-table photos replace the temporary
global images; alt text, archive caption and responsive variants are updated.
Photographer credits and publication permission remain unverified, so B-007
is partly resolved. The catalogue and remaining slot choice are recorded in
[image-map.md](../image-map.md) and [image-map-new-site.md](../image-map-new-site.md).

The homepage carousel keeps the 2026 group photo first and adds the eight
remaining batch photos in event-day order. Individual alt text and responsive
variants are included. [Carousel record](17-homepage-photo-carousel.md).

The owner also requested a small risoprint photo configurator. It is available
as local creative tooling at `/tools/riso`, outside the public sitemap and
application journeys. Its implementation boundary, evidence and separate
browser/engine checks are recorded in the [tool record](10-risoprint-tool.md).

The latest `npm run build` passes. The build reports existing font URL warnings
for self-hosted fonts under `public/fonts`; the output keeps those references
for runtime loading, and the running page renders its fonts. Playwright checks
pass for the larger homepage photo, expanding reveal, keyboard/touch operation,
reduced motion and the static no-JavaScript print. [Photo verification](11-site-photo-treatments.md#homepage-reveal-verification)

The latest PHP suite passes all 21 tests. The previous closed-edition
expectations now reflect the selected open-edition behavior. Photo coverage
matches the three retained homepage images. Build and running desktop/mobile
checks pass. [Selection verification](13-messaging-review.md#verification).

## Completion gate

The owner extended the makers typography to all public pages and the complete
makers visual/form refinements to organisations. Shared roles, audience
patterns and form recovery now serve both tracks. All 111 PHP tests and running
typography/form checks pass. B-016 is closed, recording the underlying
split-heading correction and final verification.
[Extension and receipt](26-public-typography-and-organisation-parity.md)

The owner selected T01 and T03–T06 from the makers typography overview, with
makers-only scope and arrow + Edition. All five are implemented and verified
in the running product, including genuine Light 300, text scaling and font
fallback. T02 was not selected; B-015 is closed.
[Typography receipt](25-makers-typography-review.md#selected-implementation-receipt)

The owner-requested makers hardening now uses proper Flux controls, safe draft
recovery and database-backed replay protection. The local migration is applied;
deployment needs the migration before accepting hardened maker submissions.
[Implementation and verification](24-makers-hardening.md). B-014 is closed.

The owner selected seven makers-page polish, boldness and colour suggestions
in Lavish: M01 and M03–M08. All seven are implemented and verified on the
running page. M02 and organisation styling remain unchanged; B-013 is closed.
[Makers review](23-makers-polish-review.md)

The owner has instructed the Filament admin build and delegated one shared
skill vocabulary. The [admin workspace](31-admin-workspace.md) implements
organisation review/briefings, maker selection, team matching and super-admin
account management. The original applications remain preserved. Four real
accounts (Frederik as super admin, Piet, Gwen and Bernard) await their email
addresses; a local preview login is available.
[Build instruction](../../raw/2026-10-18-admin-build-instruction.md)

The owner requested test data. Local 2027 admin examples now include six
organisations, eighteen makers, twelve reviews and two teams with both covered
and missing skills. The guarded seed command preserves existing data and
demo edits; this does not replace the pending Airtable import.
[Demo data](31-admin-workspace.md#local-test-data)

The owner subsequently confirmed separate organisation reviews for consensus,
Frederik's organisation contact and online briefing refinement, and Gwen's
individual maker selection and matchmaking. Planning now focuses on the
refined-briefing/maker-needs handoff; responsibilities do not revoke the four
users' requested matchmaking access. Organisation contact can remain in
Frederik's mailbox. [Workflow](30-admin-discovery.md#confirmed-handoffs)

This phase is not complete while application requiredness, mail configuration,
real admin-account provisioning, later phase mapping, and import completeness remain
open. Signup is active by owner instruction. The owner's screenshots confirm
the existing Amsterdam 3 server and Forge setup. Configure Resend and confirm
the destination mailbox before enabling production email.
[Scope; Build concerns; Application intake; Application privacy and retention]
