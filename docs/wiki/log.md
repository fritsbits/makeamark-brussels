# Cascade change log

- 2026-10-21: Applied the owner's selections for the closed period. The
  preparing label reads "Applications open soon"; audience pages add an
  Instagram follow pill and a clearer notice; stale submissions say
  applications aren't open yet. Tests now pin edition status instead of
  reading the live file. 116 tests pass; local desktop and phone checks pass.
  Added the opening-day checklist under B-040.
  [Implementation](build/50-applications-closed.md).

- 2026-10-21: Closed 2027 applications in production until an opening date is
  set. The edition status changed from `open` to `preparing`; the server now
  rejects submissions and pages show the preparing label. Copy, follow link,
  stale-form message and opening checklist follow in a second deploy.
  [Decision](../raw/2026-10-21-applications-closed-decision.md).

- 2026-10-20: Deployed Fathom to Forge from main commit `2a64388`. The
  production homepage returns the Fathom script with site ID `UDIIHURW`, and
  `/privacy` serves the analytics disclosure. The Fathom dashboard was not
  checked. GitHub Actions failed before tests because its PHP 8.3 setup is
  incompatible with the lockfile's PHP 8.4.1 dependency requirement. B-039
  remains partly resolved for the controller's balancing assessment and DPA.
  [Observation](../raw/2026-10-20-fathom-production-deployment.md);
  [implementation](build/49-fathom-analytics.md).

- 2026-10-20: Installed the owner-provided Fathom script on production public
  pages only and updated the privacy notice with its purpose and stated data
  processing. Recorded the remaining lawful-basis and DPA confirmation under
  B-039. Running production and dashboard verification remain outstanding.
  [Request](../raw/2026-10-20-fathom-analytics-request.md);
  [implementation](build/49-fathom-analytics.md).

- 2026-10-20: Fixed the homepage wordmark jumping at its scroll trigger. The
  single 24px boundary now uses hysteresis, entering compact above 64px and
  returning to large at or below 8px. The production build, 115 PHP tests
  (887 assertions), and running-page checks at desktop and phone widths pass.
  B-038 is closed. [Request](../raw/2026-10-20-homepage-wordmark-jump-request.md);
  [implementation and verification](build/48-homepage-wordmark-scroll-stability.md).

- 2026-10-19: Applied the owner's final Lavish selection, superseding the earlier
  six-item selection: L-02 through L-06 remain implemented; L-01's original
  Paper Cream text is restored. The visual receipt now shows this distinction.
  Build and twelve running-route/viewport checks pass; original contrast
  observations remain. Backend validation is unchanged from the 111/115 test
  run. The user ended the review session; it has not been reopened.
  [Final feedback](../raw/2026-10-19-launch-review-final-feedback.json);
  [final verification](../raw/2026-10-19-launch-final-fixes-verification.json);
  [receipt](build/43-launch-audit.md#goedgekeurde-wijzigingen).

- 2026-10-19: Reworked the launch report into a visual review. The owner
  selected L-01 through L-06; all six are implemented and running-page verified.
  Youth eligibility uses a new required JSON key with distinct legacy admin
  labels. Twelve route/viewport combinations, keyboard menu checks, targeted
  Axe checks and the exact approved sharing asset pass. Build passes;
  111/115 PHP tests pass, retaining the same four pre-existing copy failures.
  No real emails were sent. B-035 is partly resolved.
  [Selection](../raw/2026-10-19-launch-visual-review-selection.md);
  [receipt](build/43-launch-audit.md#goedgekeurde-wijzigingen).

- 2026-10-19: Completed the owner's launch-readiness audit with Impeccable.
  Reviewed four public routes at five widths, accessibility, keyboard/mobile
  navigation, no-JavaScript fallbacks, invalid form submissions, build, the PHP
  suite, content and security/operations. Recorded six P1 and four P2 findings,
  six release checks and a 14/20 score; B-035 remains open. Build passes and
  107/111 PHP tests pass, with four stale copy expectations. Product code was
  not changed and no real mail was sent. [Audit](build/43-launch-audit.md);
  [evidence](../raw/2026-10-19-launch-audit-evidence.json).

- 2026-10-19: Reordered the homepage photo carousel at the owner's request:
  bright workshop audience second, meal preparation fourth, and the dark-room
  presentation audience last. The remaining photos keep their relative order.
  [Request](../raw/2026-10-19-homepage-carousel-order-request.md);
  [carousel record](build/17-homepage-photo-carousel.md).

- 2026-10-19: Replaced the homepage's open-state application label and arrows
  with the owner's “Apply now!” vector scribble. The owner selected Ink
  lettering with a Marker Red oval and arrow; closed and preparing phases keep
  their dynamic status label. [B-034 implementation](build/42-homepage-apply-now-scribble-options.md);
  [owner selection](../raw/2026-10-19-homepage-apply-now-scribble-selection.md);
  [request](../raw/2026-10-19-homepage-apply-now-scribble-request.md).

- 2026-10-19: Added generous vertical room around the expanded homepage
  wordmark, kept the compact sticky row on scroll, and reduced hero top spacing
  slightly to balance the title position. [Owner request](../raw/2026-10-19-homepage-wordmark-spacing-request.md);
  [decision](build/40-homepage-wordmark-spacing.md).

- 2026-10-19: Replaced the mobile navigation dropdown with a viewport-filling
  Marker Red menu and large cream display links. The wordmark and close control
  stay above the menu; background scrolling is locked while it is open.
  [B-033 receipt](build/41-mobile-menu.md);
  [owner direction](../raw/2026-10-19-mobile-menu-request.md).

- 2026-10-19: Linked the homepage’s dynamic applications status to the two
  audience routes and added a doubled, uneven marker underline with paired
  arrows inspired by the owner's social artwork. The link keeps an accessible
  name and keyboard focus treatment. [B-032 receipt](build/40-homepage-application-cue.md);
  [owner request](../raw/2026-10-19-homepage-applications-open-cue-request.md);
  [social artwork](../../.bloom/attachments/z9mWGC/Pasted%202026-10-19%20at%2010.53.35.png).

- 2026-10-19: Changed the shared navigation wordmark to primary Ink and added
  the homepage large-to-compact scroll transition, with reduced-motion support
  and a smaller narrow-screen start. Running-page verification is recorded in
  [B-031](build/39-homepage-wordmark.md). [Owner request](../raw/2026-10-19-homepage-wordmark-scroll-request.md).

- 2026-10-19: Applied all selected Makers parity refinements to the
  organisations page: removed the Edition back link and payment FAQ, matched
  the D06-D date/hero order, promoted the hero and application introductions to
  leads, and removed the form footer note while retaining field markers.
  Desktop/mobile running review confirmed the expected type sizes, no overflow,
  and no page errors. [B-030 receipt](build/38-organisation-page-parity.md).
  [Owner selection](../raw/2026-10-19-organisation-page-parity-selection.md).

- 2026-10-19: Refreshed the Impeccable design sidecar from the current CSS
  palette and the makers-page implementation. Updated color metadata and ramps,
  current photography depth, and reusable makers hero/form patterns. Removed a
  duplicate YAML color key in DESIGN.md; Impeccable doctor reports no drift.
  [Owner request](../raw/2026-10-19-impeccable-document-refresh.md);
  [tokens](../../resources/css/tokens.css);
  [makers implementation](../../resources/css/makers.css);
  [sidecar](../../.impeccable/design.json).

- 2026-10-19: Removed the homepage hero date and applied the Makers lead
  treatment to the hero introduction and right-side “The 2027 edition” copy.
  The lead matches the Makers size, weight and line height at 1440px and 390px;
  colors preserve contrast on the cream and red surfaces. No horizontal
  overflow; desktop capture reviewed. [Owner feedback](../raw/2026-10-19-homepage-lead-feedback.md);
  [implementation and verification](build/37-homepage-lead-treatment.md).

- 2026-10-19: Removed the homepage archive caption and carousel counter,
  extracted the Makers date lockup into a shared Blade component used by the
  edition section, and removed dividers from the evidence links and About team
  list. Desktop/mobile running verification is recorded in the
  [homepage receipt](build/37-homepage-lead-treatment.md#homepage-detail-refinements).
  [Owner feedback](../raw/2026-10-19-homepage-detail-feedback.md).

- 2026-10-19: Matched the makers date lockup's desktop height to the first
  headline row and aligned both at the top. Responsive desktop type keeps the
  full lockup inside the row; mobile retains its selected sizing. Production
  asset build passed and the refreshed desktop page was visually reviewed.
  [Owner request and screenshots](../raw/2026-10-19-date-lockup-title-row-request.md);
  [implementation](build/28-maker-hero-arrangement.md#date-and-first-title-row-alignment).

- 2026-10-19: Changed the makers date lockup to cream text with a cream divider,
  using the shared `--cream` and `--cream-rule` tokens. The production asset
  build passed and the refreshed makers page was visually reviewed. [Owner request](../raw/2026-10-19-date-lockup-color-request.md);
  [implementation](build/34-date-lockup-variations.md#d06-d-selected-and-implemented).

- 2026-10-19: Gave the homepage About title the full grid row above the
  organiser list and logo so its final word no longer sits alone on a line.
  [Owner request](../raw/2026-10-19-homepage-about-heading-width.md);
  [implementation and review](build/33-homepage-about-heading.md).

- 2026-10-19: Applied the live site's red-band layout to the past-client list
  after homepage photo evidence and added Klimaatzaak at the owner's
  instruction. The list now contains 56 names. [Owner instruction](../../raw/2026-10-19-add-klimaatzaak.md);
  [implementation receipt](build/32-homepage-client-roster.md).

- 2026-10-19: Processed the owner's Lavish copy choices: “Design and creativity
  are for everyone” for the homepage, plus the recommended maker and
  organisation titles. Updated the homepage headline and both audience H1s;
  supporting copy remains unchanged. B-021 is Partly pending running-product
  verification. [Decision](build/28-copywriting-benefits.md);
  [selection capture](../raw/2026-10-19-copywriting-benefits-selection.md).

- 2026-10-19: Analysed public titles against the confirmed audience strategy,
  owner feedback on organisation agency, historical maker/organisation
  motivations, and the current audience copy. Proposed clearer homepage, maker,
  and organisation title/lead pairs without changing site copy. Added B-021 for
  selection and running-product verification. [Analysis](build/28-copywriting-benefits.md);
  [Lavish review](../../.lavish/copywriting-benefits.html).

- 2026-10-19: Changed the homepage audience cards to sentence-style
  invitations beginning “Makers,” and “Social organisations,”. Tightened the
  heading-to-copy gap to 8px while preserving 32px before the action. Updated
  B-018 and the audience arrangement record. [Owner request](../raw/2026-10-19-homepage-audience-sentence-request.md);
  [implementation record](build/18-audience-arrangement.md).

- 2026-10-19: Standardised the homepage carousel to a 4:3 display frame after
  the owner's aspect-ratio instruction. Kept the 4:3 group photo first and
  retained seven landscape archive photos with a consistent center crop. Left
  out the portrait serving-food photo because the crop would be severe. Updated
  the carousel data, photo styling, mapping and concern register.
  [Decision](../raw/2026-10-19-carousel-same-aspect-ratio.md);
  [carousel record](build/17-homepage-photo-carousel.md).

- 2026-10-19: Refined both application payoff states with a red receipt,
  audience-specific thanks, a brief stamped-check entrance, truthful email
  status, review guidance and a return to the edition. Removed the native
  blue outline from the fragment-focused section while retaining control
  focus rings. Build, 39 application tests and real desktop/mobile,
  reduced-motion and no-JavaScript checks pass; captures inspected and
  temporary test records removed. Closed B-019; mail setup remains B-002.
  [Request](../raw/2026-10-19-application-payoff-request.md);
  [implementation and verification](build/20-application-intake.md#application-payoff).

- 2026-10-19: Captured the owner's organisation-invitation clarification.
  The copy should invite creative help for a deferred communication or design
  project, with makers producing the work. New wording remains unselected;
  current homepage copy and B-018's verified layout stay.
  [Source](../raw/2026-10-19-organisation-invitation-direction.md);
  [copy review](build/18-audience-arrangement.md#copy-review).

- 2026-10-19: Recorded the owner's selection of audience arrangement A from
  Lavish, with shared heading, description and action rows, a 34ch paragraph
  measure and stacking at 860px. Build and six-width running checks pass;
  desktop/mobile screenshots were inspected. Closed B-018.
  The owner also requested shorter-copy suggestions; those remain review
  options and the homepage retains its wording. [Decision](build/18-audience-arrangement.md)

Append one dated, concise entry whenever the synthesis changes.

- 2026-10-19: Closed B-023 after the owner selected D-B, an editorial “27 /
  Saturday / March 2027” stamp under the title with no icon or location, and
  removal of the duplicate Edition link. The linked wordmark remains the route
  home; implementation was not requested. [Selection](../raw/2026-10-19-maker-hero-arrangement-selection.md);
  [analysis](build/28-maker-hero-arrangement.md).

- 2026-10-19: Implemented the selected D-B makers hero. The date stamp is
  derived from the edition's ISO start date; the duplicate Edition link and
  repeated Brussels label are removed for makers. Desktop title/lead top edges
  align; mobile stacks title, date, lead and action without overflow. The
  organizations hero retains its existing return link and date row. Production
  CSS built successfully. [Request](../raw/2026-10-19-maker-hero-arrangement-implementation-request.md);
  [implementation and verification](build/28-maker-hero-arrangement.md).

- 2026-10-19: Made the makers-page hero introduction a larger Paper Cream lead
  using the shared `--fs-lead` type token. Copy and layout remain unchanged.
  Fixed equal-priority typography cascade and rebuilt production CSS. Browser
  checks confirm 24px desktop, 20px mobile and no mobile overflow. Closed
  B-020. [Request](../raw/2026-10-19-maker-hero-lead-request.md);
  [implementation record](build/27-maker-hero-lead.md).

- 2026-10-19: Aligned the makers-page title with the lead at the top of the
  hero after the owner noted their vertical offset. Desktop bounding-box tops
  now match; mobile remains naturally stacked with no overflow. [Request](../raw/2026-10-19-maker-hero-title-alignment-request.md);
  [implementation record](build/27-maker-hero-lead.md).

- 2026-10-19: Expanded the homepage carousel to nine distinct photos. Kept
  the 2026 group photo first, then ordered the remaining eight archive images
  from participants/workshop activity through presentations and shared food.
  Updated slide labels, caption, responsive variants, image mapping and B-007.
  [Request](../raw/2026-10-18-homepage-carousel-photo-batch.md);
  [carousel record](build/17-homepage-photo-carousel.md).

- 2026-10-18: Replaced the top hero image with the owner's selected mingling
  photo, leaving the planning-table image in the lower position. Updated alt
  text, image-map use/assignment and B-007. [Selection](../raw/2026-10-18-homepage-top-photo-selection.md).

- 2026-10-18: Applied the owner's carousel sizing correction: one complete
  photograph fills the container after growth, with natural proportions and
  no neighboring slide preview or reveal zoom. Added width and cropping
  regression checks. [Feedback and receipt](build/17-homepage-photo-carousel.md).

- 2026-10-18: Replaced the two temporary homepage hero photos with the owner's
  mapped selections: the bright workshop audience and planning-table team.
  Updated alt text and source caption, generated 480/960 WebP variants, and
  recorded the selection in the image map and B-007. Photographer credit and
  publication permission still need confirmation.

- 2026-10-18: Converted the homepage's “A feel for the day” photo into a
  four-slide carousel using duplicate placeholders as requested. Native swipe,
  Flux arrows and keyboard navigation retain photo growth and colour reveal.
  Build, 111 PHP tests, five-width carousel/motion checks and no-JavaScript
  checks pass. Sourced replacement photography remains B-007.
  [Receipt](build/17-homepage-photo-carousel.md).

- 2026-10-18: Phase 1 — copied and catalogued ten owner-supplied photographs
  in [image-map.md](image-map.md), including dimensions, orientation, visual
  descriptions, quality and current use. [Request](../raw/2026-10-18-image-mapping-request.md).
- 2026-10-18: Phase 2 — mapped three provisional homepage matches in
  [image-map-new-site.md](image-map-new-site.md). Event/location, photographer
  credits and publication rights need confirmation. Updated B-007 and the
  index; public photography is unchanged.

- 2026-10-18: Changed form-group divider rules and text-control borders to
  a dedicated, slightly darker cream token. Updated the makers
  design receipt; no form behavior changed.
  [Review](build/23-makers-polish-review.md#selected-implementation-receipt).

- 2026-10-18: Matched unchecked maker theme-choice tile borders to the darker
  cream used by the text fields and form dividers. Red selected states remain.
  [Review](build/23-makers-polish-review.md#selected-implementation-receipt).

- 2026-10-18: Refined required-field asterisks into smaller red inline markers.
  They remain hidden from assistive technology because the native required
  state and field validation provide the accessible requirement.
  [Review](build/23-makers-polish-review.md#selected-implementation-receipt).

- 2026-10-18: Aligned the audience hero's headline and intro with the lower
  section's centered content columns while keeping the red panel wide. The
  stacked layout follows the same content edge on small screens.
  [Review](build/23-makers-polish-review.md#selected-implementation-receipt).

- 2026-10-18: Applied the shared darker cream rule token to fine borders and
  dividers across the site, including homepage evidence links and organiser
  rows. Red accents remain on headings and actions.
  [Homepage review](build/14-homepage-polish-review.md).

- 2026-10-18: Implemented owner-selected R02 across navigation pills: a 260ms
  synchronized ink/label sweep with shared arrows, visible keyboard focus and
  instant reduced-motion states. Preserved the other session's filled audience
  CTAs and the removed edition action. Build, 111 PHP tests, five JS tests and
  three-width running checks pass; both layers meet contrast and align within
  1px. No-JavaScript and reversal checks pass; independent review is clean.
  Resolved the duplicate concern ID by assigning the button review B-017 and
  closing it; makers-hardening B-014 is unchanged.
  [Selection](../raw/2026-10-18-button-rollover-selection.md);
  [receipt](build/16-button-rollover-review.md#selected-implementation-receipt).

- 2026-10-18: Extended the approved makers typography to public pages and the
  makers visual/Flux/form-hardening treatment to organisations. Fixed split
  application headings at their source: restrictive measure plus inherited
  emergency wrapping. Added glyph-range regression checks and title-column
  sizing for the longer organisation hero. All 111 PHP tests, six-width type
  checks, both form browser checks and changed-path Pint pass; repository-wide
  Pint retains two unrelated existing failures. Closed B-016.
  [Request](../raw/2026-10-18-public-typography-and-organisation-parity-request.md);
  [implementation and checks](build/26-public-typography-and-organisation-parity.md).

- 2026-10-18: Removed “Explore ways to take part” from the homepage edition
  block at the owner's request. Retained the conditional non-open-edition
  status. Adapted the browser-only contrast fixture so the removed action is
  not required for shared-pill surface checks. Rollover choice remains pending.
  [Instruction](../raw/2026-10-18-edition-action-removal.md);
  [review](build/16-button-rollover-review.md#edition-action-removal).

- 2026-10-18: Implemented owner-selected T01 and T03–T06, makers-only, with
  arrow + Edition. Reading copy uses real Light 300; form roles, date metadata
  and heading balance follow the selected overview. T02 was unselected.
  Build, 89 PHP tests, four-width typography checks, 200% text scaling, font
  fallback and existing form regression checks pass. Closed B-015.
  [Selection](../raw/2026-10-18-makers-typeset-selection.md);
  [receipt](build/25-makers-typography-review.md#selected-implementation-receipt).

- 2026-10-18: Prepared six makers typography suggestions in Lavish, following
  the owner's screenshot and request for lighter body copy and a quieter back
  action. Verified current text is true 400; prepared a matching real 300 face
  for review. Desktop/mobile comparisons and structured style/scope selection
  pass. Recommended T01–T05; opened B-015. Product is unchanged.
  [Request](../raw/2026-10-18-makers-typeset-request.md);
  [review](build/25-makers-typography-review.md).

- 2026-10-18: Reproduced the reported unreadable edition-button rollover at
  1.00:1 contrast. Fixed the shared pill's anchor/child colour ownership;
  hover and keyboard-focus checks now measure 4.78:1 on both surfaces. Build
  and 89 PHP tests pass. Prepared three interactive Lavish motion choices,
  with red/cream demos and reduced-motion handling. Opened B-017; no new
  animation is selected.
  [Request](../raw/2026-10-18-button-rollover-request.md);
  [fix and review](build/16-button-rollover-review.md).

- 2026-10-18: Triaged the homepage overview's stop-hook findings. Enlarged
  small mockup labels, verified actual contrast/font/padding at three widths,
  and added narrow artifact-specific detector exceptions for false positives
  and the established body font. Refreshed the design sidecar without changing
  the public homepage. Final targeted detector run is clean.
  [Audit receipt](build/15-homepage-boldness-review.md#follow-up-hook-audit).

- 2026-10-18: Hardened the maker page and migrated its controls to Flux. Fixed
  narrow selects and the decorative `.block`/Tailwind utility collision. Added
  safe recovery for malformed/expired/rate-limited attempts, session-bound form
  nonces with unique database replay protection, and audience-scoped receipts.
  Applied the local migration. All 89 PHP tests, five JavaScript tests, build,
  Pint and running browser checks pass. Security review's theme-cardinality
  finding is fixed. Closed B-014; mail operations remain separate.
  [Request](../raw/2026-10-18-makers-hardening-request.md);
  [implementation](build/24-makers-hardening.md).

- 2026-10-18: Implemented all owner-selected homepage suggestions, H01–H04,
  keeping both hero photos visible on phones too. Added reversible group-photo
  scroll growth with a 1200px source variant, poster headline, larger audience
  invitations and reduced-motion-aware entrances/arrows. Build, 89 PHP tests,
  five JS tests and five-width browser checks pass. Keyboard/touch colour
  reveal, runtime reduced motion and no-JavaScript defaults pass. Independent
  review found no material issues. Existing Pint failures in two unrelated files
  remain. Updated B-006; B-007 photo replacement stays separate.
  [Selection](../raw/2026-10-18-homepage-boldness-selection.md);
  [receipt](build/15-homepage-boldness-review.md#selected-implementation-receipt).

- 2026-10-18: Prepared four homepage boldness and motion suggestions in Lavish,
  including a working group-photo scroll-growth demo, current-site captures,
  composition studies and independent selection controls. H01/H02 are recommended
  first. Selection is pending; the public homepage is unchanged. Updated B-006.
  [Request](../raw/2026-10-18-homepage-boldness-request.md);
  [review](build/15-homepage-boldness-review.md).

- 2026-10-18: Implemented the seven selected makers-page refinements, M01 and
  M03–M08. M02 and organisation styling remain unchanged. Added scoped red hero,
  compact pay answer, sticky introduction, grouped fields, clear control and
  choice states, send panel, sending/reset state and linked/focused errors.
  All 70 PHP tests, build, Pint and four-width browser checks pass; independent
  review found no material issues. Closed B-013.
  [Selection](../raw/2026-10-18-makers-polish-selection.md);
  [receipt](build/23-makers-polish-review.md#selected-implementation-receipt).

- 2026-10-18: Prepared eight makers-page polish, boldness and colour suggestions
  in Lavish, using the existing Brussels design system. Current/proposed renders,
  a combined preview, desktop/mobile checks and structured selection controls
  are ready. Recommended M01/M03/M04/M05/M06/M08; owner selection is pending.
  Opened B-013. The running page is unchanged.
  [Request](../raw/2026-10-18-makers-polish-request.md);
  [review](build/23-makers-polish-review.md).

- 2026-10-18: Owner selected homepage polish P01–P06 and removal of duplicate
  hero audience links through Lavish. Implemented tighter spacing, evidence
  resource links, organiser rows, 48px menu/close state, grouped footer with
  44px links, and accurate edition-link wording. P07 remains unchanged.
  Build, 70 PHP tests and running checks at five widths pass; desktop/mobile
  screenshots and scoped code review completed.
  [Selection](../raw/2026-10-18-homepage-polish-selection.md);
  [receipt](build/14-homepage-polish-review.md).

- 2026-10-18: Embedded maker and organisation forms directly on their information
  pages following the owner's request. Hero actions and old application links
  jump to the form; validation and receipts return there. Both-audience feature
  coverage, desktop/mobile browser checks, five JavaScript tests and production
  build pass. Closed B-012; field audit and operations concerns stay open.
  [Request](../raw/2026-10-18-inline-application-request.md);
  [decision and verification](build/20-application-intake.md#forms-on-the-information-pages).

- 2026-10-18: Reviewed the running distilled homepage for polish and created
  a Lavish decision page with seven independent current/proposed choices,
  source snapshots and structured selection controls. Recommended P01, P02,
  P04 and P06; owner selection is pending. Existing desktop/mobile browser
  checks and decision-page interaction checks pass. Public implementation
  is unchanged. [Review](build/14-homepage-polish-review.md).

- 2026-10-18: Seeded persistent local admin examples at the owner's request:
  six organisations, eighteen makers, twelve reviews and two starter teams
  for 2027. Examples include unreviewed applications, available selected makers,
  a draft team missing copywriting and a confirmed team with covered needs.
  Added a local-only repeatable seed command that preserves existing records,
  demo edits and login credentials. Real accounts/import concerns remain open.
  All 68 tests (392 assertions), Pint and the focused safety review pass.
  [Request](../raw/2026-10-18-admin-demo-data-request.md);
  [data and test journeys](build/31-admin-workspace.md#local-test-data).

- 2026-10-18: Fixed the preview credential handoff after the owner could not
  sign in. The valid password contained a backslash escaped in the JSON file;
  the preview command now also writes a private `0600` text file with literal,
  copyable credentials. Original account/password retained. Browser login using
  the text file succeeds; 65 tests (365 assertions), Pint and the focused file
  security review pass. [Report](../raw/2026-10-18-admin-preview-login-report.md);
  [setup](build/31-admin-workspace.md#account-setup).

- 2026-10-18: Built and verified the requested Filament admin: organisation
  reviews/briefings, maker selection, unified skills, edition-safe team matching
  and super-admin account management. Saved the sitemap as a private Notion
  draft. The owner retains mailbox contact and the manual message to Gwen.
  Added secure interactive account setup and a private local preview login;
  four real account emails remain pending. Closed B-008; B-009 remains Partly
  for provisioning and B-010 for any later maker-email scope. All 64 PHP tests
  (358 assertions), production build, Pint and Composer audit pass. Authenticated
  desktop/mobile checks and an independent security review pass; temporary
  browser data was removed. [Instruction](../raw/2026-10-18-admin-build-instruction.md);
  [implementation and verification](build/31-admin-workspace.md).

- 2026-10-18: The owner selected the second, distilled version and removed the
  homepage process section. Promoted home and audience pages to canonical
  routes, updated content/navigation, and preserved the original homepage and
  data under the local comparison. Closed B-011. All 21 PHP tests, production
  build and running desktop/mobile checks pass. Homepage main text is now
  165 words against the original 489, excluding navigation/footer.
  [Owner instruction](../raw/2026-10-18-messaging-selection.md);
  [implementation](build/13-messaging-review.md).

- 2026-10-18: Recorded separate organisation reviews for consensus,
  Frederik's own-mailbox contact and online briefing refinement, and Gwen's
  individual maker selection/matchmaking. Reviewed two further skills-option
  screenshots; vocabulary mapping remains in the field/import audit.
  B-008 retains the remaining rules; B-010 is now Partly because organisation
  contact can remain external. Four-account matchmaking access remains required.
  [Source](../raw/2026-10-18-admin-workflow-clarification.md);
  [workflow](build/30-admin-discovery.md#confirmed-handoffs).

- 2026-10-18: The owner authorised all messaging recommendations in a duplicate
  homepage with side-by-side comparison. Implemented local-only homepage and
  audience previews, shared-width desktop/mobile comparison, consolidated
  edition details and reduced supporting content. The original public pages
  and JSON are unchanged. Four preview tests and running browser checks pass;
  build passes. Full PHP suite remains at two known baseline failures, 19/21
  passing. B-011 is partly resolved pending comparison and public adoption.
  [Implementation](build/13-messaging-review.md).

- 2026-10-18: Reviewed homepage and audience messaging at the owner's request,
  without implementation. Created a Lavish overview with four proposed cuts,
  suggested copy, current screenshots and numbered evidence snapshots. Flagged
  the open-status/preparation-copy contradiction. Added B-011 for owner review.
  Existing running desktop/mobile browser checks pass.
  [Review](build/13-messaging-review.md).

- 2026-10-18: Opened admin discovery at the owner's request; recorded Filament,
  four individual organising-team accounts and Frederik as super admin.
  Inspected nine Airtable screenshots and added an evidence inventory, review
  workflow and targeted content requests. Added B-008–B-010 for workflow,
  permissions and selection communications; B-001/B-004 retain field/import
  coverage. No admin code or accounts created.
  [Source](../raw/2026-10-18-admin-review-request.md);
  [workflow](build/30-admin-discovery.md).

- 2026-10-18: Replaced the public group photo's Riso base with the hero's
  shared monochrome treatment, keeping the original-color expanding reveal,
  mobile/keyboard activation and reduced-motion fallback. Three PHP photo
  tests, five JavaScript tests, production build and four browser modes pass.
  Reconciled the actual hero alignment, tonal surfaces, edition grid and photo
  behavior in DESIGN.md; refreshed Impeccable metadata after a healthy doctor
  check. [Selection](../raw/2026-10-18-monochrome-group-photo-selection.md);
  [implementation](build/11-site-photo-treatments.md#current-monochrome-treatment).

- 2026-10-18: Used the owner's requested international photos as temporary
  locally served hero images with source attribution and replaceable content
  records. Added monochrome hero photography, lighter-red process-number
  panels and darker-cream audience panels. Updated DESIGN.md and the
  [review](build/12-global-design-review.md); B-006 is Partly and B-007 tracks
  sourcing Brussels images. Desktop/mobile checks and build pass; the PHP
  suite exposes two existing status/preparation assertions unrelated to this
  visual change.

- 2026-10-18: Reviewed the live global Make a Mark homepage and stylesheet,
  captured measured colors and photo treatments, and incorporated owner
  feedback on tonal reds, tonal creams and monochrome images. Added the
  [design review](build/12-global-design-review.md) and B-006. Recommendations
  remain unimplemented; production styling and photo selections are unchanged.

- 2026-10-18: Added a preliminary legitimate-interests assessment and captured
  official GDPR and EDPB guidance. The basis remains a working proposal until
  Impact Studio confirms the purpose, necessity, balance, and practical access
  controls. [Assessment](build/22-legitimate-interest-assessment.md); [source](../raw/2026-10-18-legitimate-interest-and-retention-guidance.md).

- 2026-10-18: Built grouped maker and organisation application forms with
  validation and Laravel persistence. Public submission remains gated because
  the current 2027 edition is `preparing` and reviewer field/data-handling
  decisions remain open. [Application intake](build/20-application-intake.md).
- 2026-10-18: The owner opened the 2027 edition. Recorded that maker and
  organisation applications now accept submissions, and researched the
  missing collection-notice details against GDPR Article 13 and Belgian DPA
  guidance. Controller identity, legal basis, recipients, retention, required
  fields, and internal handling remain to be confirmed. [Application intake](build/20-application-intake.md); [privacy source capture](../raw/2026-10-18-application-privacy-requirements.md).
- 2026-10-18: Captured Impact Studio BV as proposed controller, internal-only
  review, Resend receipt email direction, removal of maker dietary data, and
  the owner's plan to provide Notion admin references later. Added Resend
  receipt mail and the Impact Studio footer credit. Prepared a notice draft;
  lawful-basis assessment, provider locations, and deployment mail
  configuration remain open. [Owner decisions](../raw/2026-10-18-application-privacy-and-operations-decisions.md); [privacy and retention](build/21-application-privacy.md).

- 2026-10-18: The owner clarified that application data should not be
  automatically deleted because it may support future accounts and editions.
  Removed the 90-day purge schedule. Recorded DigitalOcean Amsterdam hosting
  via Forge, planned one.com/Cloudflare sender setup, Resend receipts, and
  alert-only Gmail messages deferred until admin exists. Updated the privacy
  draft: retention, reuse rules, and provider verification remain to be
  decided. The likely basis for current review is recorded as a candidate only;
  the owner asked for a plain-language explanation before selecting one.
  [Owner update](../raw/2026-10-18-application-operations-update.md);
  [privacy draft](build/21-application-privacy.md).

- 2026-10-18: At the owner's request, selected legitimate interests as the
  basis for reviewing applications, running the edition, and supporting a
  returning applicant. Chose annual review and deletion/anonymisation no later
  than three years after the latest application. Reviewed current official
  provider terms, recorded the confirmed AMS3/Forge setup and remaining mail
  configuration, and implemented a
  privacy notice linked on application pages and in the footer. Gmail alerts
  remain inactive until a Google Workspace account and admin destination are
  available. [Notice](build/21-application-privacy.md); [assessment](build/22-legitimate-interest-assessment.md); [provider review](../raw/2026-10-18-application-provider-handling.md).


- 2026-10-18 — Set up the Cascade scaffold; product evidence has not yet been added.
- 2026-10-18: Captured the existing Astro one-pager and ported it to Laravel
  with Livewire, Flux Pro, Tailwind, and bundled Alpine. Preserved its design,
  content, and behavior; verified the running site at desktop/mobile sizes,
  the PHP suite, build, migrations, formatting, and dependency audits.
  [Migration record](discovery/20-laravel-foundation.md). D-001 and D-003 are
  partly resolved; Discovery remains active.
- 2026-10-18: Captured the Make a Mark 2027 Notion brief and meeting summary.
  Updated Discovery synthesis and concerns; D-001 and D-003 remain partly
  resolved, D-002 remains open, and D-004 records the date/venue discrepancy.
  [Source capture](../raw/2026-10-18-notion-make-a-mark-2027.md).
- 2026-10-18: The owner confirmed 27 March 2027 as the next edition date,
  resolving the conflicting meeting-note entry. Updated the source capture,
  synthesis, and D-004; venue confirmation remains open.
- 2026-10-18: Captured the owner-provided maker and organisation signup
  screenshots, after-event contact form, three requested website phases, and
  the BRUZZ 2023 report. Updated Discovery synthesis and concerns; D-002 is
  partly resolved with historical reporting but direct research remains needed,
  and D-005 tracks the uninspected Airtable model and phase behavior.
- 2026-10-18: Captured additional Brussels 2019/2020 materials, the distinct
  international Make-a-Thon Field Guide, and two Brussels organisation
  testimonials from 2023. Expanded Discovery synthesis with historical
  onboarding, event, and post-event touchpoints; D-002 remains partly resolved
  because these sources are not representative current user research.
- 2026-10-18: Inspected the Airtable base read-only. Captured its five tables,
  relationship fields, views, and visible edition records; Editions is a
  first-class entity linked with organisations, while teams join makers to
  organisations for a year. Updated Discovery synthesis and moved D-005 to
  Partly; form behavior, consent, retention, and phase rules remain unknown.
  [Airtable data model capture](../raw/2026-10-18-airtable-data-model.md).
- 2026-10-18: Reconciled Discovery's plan with its register: zero Open concerns,
  five Partly concerns carried into Strategy. Opened the Strategy phase with a
  plan and five owner decisions to resolve. The first UX plane is in a Lavish
  review draft and remains unvalidated.
- 2026-10-18: Added [tone of voice draft v0.1](strategy/10-tone-of-voice.md) to the Strategy scaffold, using all six product captures, migrated site copy, and the [retrieved Notion playbook/template](../raw/2026-10-18-tone-of-voice-playbook.md). Added a Lavish review for TOV-001 to TOV-005; all five choices remain Open and Strategy remains unvalidated. Linked the canonical draft from the README and agent voice reference.
- 2026-10-18: Processed five owner selections from the Strategy Lavish review: makers-only person signup; organisations and makers co-primary; edition-cycle guide; review-ready applications as candidate North Star; show results and invite future interest after the event. Recorded [the Strategy plane](../ux/_strategy.md), closed S-001 to S-005 and D-001, and carried historical-research, target, venue, Airtable behavior, and tone-of-voice questions. Opened the Scope plane with a review draft based on the captured forms and model.
- 2026-10-18: Applied all five [round 1 voice choices](../raw/2026-10-18-tone-of-voice-feedback-round-1.md) in [guide v0.2](strategy/10-tone-of-voice.md). Closed TOV-001 to TOV-005 with resolutions and opened TOV-006 to TOV-011 for [six A/B copy comparisons](strategy/11-voice-copy-review.md), following the owner's request to refine through actual wording. Reconciled the guide with the separately validated Strategy without changing it.
- 2026-10-18: Processed Scope feedback selecting an audit of application fields and separate maker/organisation future-interest forms. The owner clarified that Airtable will not be used in v1: all content is to be imported into Laravel, which becomes the ongoing data system. Updated [the Scope draft](../ux/_scope.md), its Lavish review, and Discovery synthesis; phase control, submission/privacy details, and migration inventory remain open.
- 2026-10-18: Recorded the remaining Scope selections: use the migrated Edition status as the public phase source, and confirm application receipt with a response timeline only when the team can meet it. The captured statuses (“In progress” and “Done”) do not yet distinguish all three public phases, so mapping, privacy details, and import completeness remain explicit dependencies. The owner ended the Scope review.
- 2026-10-18: Opened the Structure plane with a provisional sitemap based on the current one-page site and owner-selected Scope. The Lavish review asks whether phases share a stable edition page, whether audience-specific information pages precede forms, and whether past editions need a public archive.
- 2026-10-18: Processed Structure choices: one stable phase-aware edition hub; separate maker and organisation information pages leading to dedicated forms; only the current edition in v1. Case studies will each have a future page when content exists. Recorded the selected sitemap in [Structure](../ux/_structure.md) and moved to Skeleton.
- 2026-10-18: Opened Skeleton with a full-page current-edition wireframe, phase-state variants, and a parallel audience-page pattern. Lavish review asks about first-screen hierarchy, audience-page information order, and one-page versus multi-step application forms.
- 2026-10-18: Processed Skeleton decisions: edition purpose leads with equal maker/organisation actions; audience pages order who it's for, offer, commitments, process, then application; forms use one page with grouped sections and final review. Saved the selected layout in [Skeleton](../ux/_skeleton.md). Stopped at Skeleton; Surface remains for a later explicit request.
- 2026-10-18: Ingested the owner-supplied [previous website PDF](../raw/2026-10-18-previous-website-copy.md), preserving its unchanged original and all five extracted pages, with a separate partial transcription of the image-only page 2. Added [messaging analysis](strategy/12-previous-website-messaging.md) and linked it from Discovery, the voice guide, copy review, plan, and concerns. Historical 2025 details remain separate from current commitments; no pending copy choice was selected.
- 2026-10-18: Added [PRODUCT.md](../../PRODUCT.md) as the Impeccable product record, synthesised from Strategy, Scope, Discovery, and the voice guide. The owner confirmed the record, set WCAG 2.2 AA as the accessibility target, and made the existing one-pager identity binding for all future pages. No visual system (DESIGN.md) is recorded yet.
- 2026-10-18: Added [DESIGN.md](../../DESIGN.md) and `.impeccable/design.json`, recording the migrated one-pager's visual system as the binding identity for all pages. The tokens come from `resources/css/tokens.css` and `app.css`. The owner chose "The Brussels Broadsheet" as the North Star, Marker Red and Paper Cream as colour names, and "ticket-stub confident" for components. The contrast check shows Headline Red (#D14B4C) at 3.45:1 on cream, so it is restricted to large text.
- 2026-10-18: Implemented the approved edition hub and maker/organisation journeys with separate application information routes. The 2027 edition remains in `preparing` while applicant fields, data handling, and phase mapping are unresolved. Added route and page tests plus a GitHub Actions workflow for Laravel tests and the Vite build. [Build plan](build/00-build-plan.md); [Build concerns](build/01-build-concerns.md).
- 2026-10-18: Built the requested [risoprint photo tool](build/10-risoprint-tool.md)
  at local-only `/tools/riso`, with the supplied photo, two ink plates, stable
  grain, comparison, uploads, PNG export and reusable JSON treatments. Verified
  the running tool at desktop/mobile sizes, four engine checks, 14 PHP tests
  and the Vite build. Closed B-005 for the tool; public photography and sitemap
  are unchanged.
- 2026-10-18: Applied the owner's saved risoprint treatment to the homepage
  group photo only. Added a reusable photo module, content opt-ins and a
  build step using the shared effect engine. Original images and other photo
  selections are preserved. Verified responsive image loading on desktop,
  mobile and with JavaScript disabled. [Selected photo treatments](build/11-site-photo-treatments.md).
- 2026-10-18: The owner selected the expanding original-photo reveal and
  requested a larger homepage frame. Recorded the agent-applied preset
  defaults separately from that choice: 600px maximum width, 700ms duration,
  40px feather, 1.025 zoom and straightening from 3 degrees. The production
  build and running desktop, mobile, reduced-motion and no-JavaScript photo
  checks pass. Fixed existing photo-strip label overflow exposed by reduced
  motion. The PHP suite reports 15 of 17 tests passing; two earlier
  application-state expectations conflict with the already-open edition.
  [Selection](../raw/2026-10-18-homepage-photo-reveal-selection.md);
  [photo treatment record](build/11-site-photo-treatments.md).

- 2026-10-18: Applied the hero's monochrome styling, rounded corners and shadow,
  and the group photo's expanding original-color rollover to all 12 photos.
  Extracted one reusable reveal component and removed the group's cream
  border. Three PHP photo tests, five JavaScript tests, production build and
  desktop/mobile/reduced-motion/no-JavaScript browser checks pass. Inspected
  desktop and mobile screenshots; no browser errors or horizontal overflow.
  [Owner selection](../raw/2026-10-18-all-photo-treatment-selection.md);
  [implementation and verification](build/11-site-photo-treatments.md#current-monochrome-treatment).

- 2026-10-19: Refined the makers hero after the owner asked for better column
  proportions, a right-column date, and a shorter title with the same meaning.
  The new title is “Use your creative skills on work that matters.” The date
  now sits with the application action at the bottom of the right column. The
  running desktop page was reviewed and production CSS builds.
  [Requests](../raw/2026-10-19-maker-hero-proportions-request.md);
  [title request](../raw/2026-10-19-maker-hero-title-shortening-request.md);
  [implementation](build/28-maker-hero-arrangement.md).

- 2026-10-19: Replaced the shortened maker title with the owner's latest
  wording, “Use your creative skills to make a difference.” The edition date
  remains beside the application action in the right column.
  [Selection](../raw/2026-10-19-maker-hero-title-selection-2.md);
  [implementation](build/28-maker-hero-arrangement.md).

- 2026-10-19: Added vertical breathing room to the audience hero panels, a
+  subtle cream-rule border under the shared nav, and a fluid gap before the
+  red panel. Desktop and phone padding values are recorded in the design system
+  and hero receipt. [Request](../raw/2026-10-19-maker-hero-padding-request.md);
+  [implementation](build/28-maker-hero-arrangement.md#vertical-spacing-refinement).

- 2026-10-19: Verified the selected homepage title and both audience title/lead
  pairs in the running Laravel app. B-021 is closed. The organisation lead is
  recorded as an implementation choice under the owner's direction.
  [Decision and verification](build/28-copywriting-benefits.md#audience-lead-copy-update);
  [concern resolution](build/01-build-concerns.md).

- 2026-10-19: Corrected the hero's unequal vertical padding. The red panel now
  uses equal 80px top/bottom padding on desktop and equal 56px padding on phones.
  [Owner correction](../raw/2026-10-19-maker-hero-equal-padding.md);
  [implementation](build/28-maker-hero-arrangement.md#vertical-spacing-refinement).

- 2026-10-19: Revised both audience-page leads to complement their titles.
  The maker lead now explains the collaboration and challenge; the organisation
  lead names the challenge and bounded outcome. Running-product verification is
  pending. [Owner request](../raw/2026-10-19-audience-lead-copy-request.md);
  [decision and implementation](build/28-copywriting-benefits.md#audience-lead-copy-update).
- 2026-10-19: Warmed shared near-black typography and added a dark-brown ink
  for selected roles. The audience commitment lead now uses the maker hero's
  larger, light sans treatment. Desktop/mobile browser checks and the production
  CSS build pass; B-024 is closed. [Request](../raw/2026-10-19-warm-ink-type-request.md);
  [implementation and verification](build/34-warm-ink-and-commitment-lead.md).
+  uses equal 80px top/bottom padding on desktop and equal 56px padding on phones.
+  [Owner correction](../raw/2026-10-19-maker-hero-equal-padding.md);
+  [implementation](build/28-maker-hero-arrangement.md#vertical-spacing-refinement).
- 2026-10-19: Warmed the shared near-black with a cream undertone and added a
  dark-brown ink for selected typography. The audience commitment lead now
  shares the maker hero lead's larger, light sans treatment. Running-product
  verification remains pending. [Request](../raw/2026-10-19-warm-ink-type-request.md);
  [decision](build/34-warm-ink-and-commitment-lead.md).

- 2026-10-19: Created eight weekday-first makers date lockup proposals, with the supplied screenshot and interactive desktop/phone hero previews. Artifact overflow, text contrast and choice submission checks passed. Owner selection is pending under B-024. [Request](../raw/2026-10-19-date-lockup-variations-request.md); [review](build/34-date-lockup-variations.md).

- 2026-10-19: Owner selected D06 as the makers date direction and requested subtle variations. The active board now compares the original with five refinements; the first round is archived. Desktop/phone overflow, text contrast and choice-submission checks pass. B-024 remains open for the final refinement choice. [Follow-up](../raw/2026-10-19-date-lockup-d06-refinements-request.md); [review](build/34-date-lockup-variations.md#d06-selected-subtle-refinements).

- 2026-10-19: Triaged the date review board’s follow-up font and padding flags. Preserved the established Plus Jakarta Sans and the visually reviewed editorial separators through artifact-scoped exceptions. No product or specimen styling changed; existing design-sidecar drift remains outside scope. [Triage record](build/34-date-lockup-variations.md#review-checks-and-next-step).

- 2026-10-19: Implemented owner-selected D06-D on the makers page: 24px weekday above a fine rule, then a 52px day numeral beside month/year. Production build and running-page checks passed at 1440, 390 and 320px; desktop/phone captures reviewed. Date concern B-025 is closed. Its earlier provisional B-024 references in this log refer to the date review; B-024 remains assigned to the concurrent warm-ink work. [Selection](../raw/2026-10-19-date-lockup-d06-d-selection.md); [implementation and verification](build/34-date-lockup-variations.md#d06-d-selected-and-implemented).

- 2026-10-19: Swapped the makers hero date and lead. The date aligns with the
  headline top; the lead and application action form a group below, with bottom
  alignment at wide desktop widths. Corrected the mobile column override at
  860px. Build and seven-width running checks passed; desktop and phone captures
  were reviewed. B-023 and B-020 remain closed.
  [Request](../raw/2026-10-19-maker-hero-lead-date-swap-request.md);
  [implementation and verification](build/28-maker-hero-arrangement.md#current-lead-and-date-order).
- 2026-10-19: Normalized the audience forms to the shared 17px reading size.
  Secondary guidance, privacy and sending copy use the lighter Ink Brown;
  validation remains in Ink. The production build and desktop/mobile
  makers/organisations running-page checks pass; makers screenshots were
  reviewed. [Request](../raw/2026-10-19-form-typography-request.md);
  [decision](build/35-form-typography-normalization.md).

- 2026-10-19: Followed the owner's form typography feedback by retaining 17px
  copy sizes, setting secondary text to the reading face's 300 weight, and
  lightening Ink Brown to `#6B5645`. Contrast remains at least 4.85:1 on cream.
  [Feedback](../raw/2026-10-19-form-typography-feedback.md);
  [updated decision](build/35-form-typography-normalization.md).

- 2026-10-19: Removed the makers payment FAQ, moved the pro-bono detail into
  the commitment copy, enlarged the application introduction, shortened the
  privacy and language guidance, and removed the makers form footer note.
  Running-page verification is recorded in [B-027](build/36-maker-form-copy.md).

- 2026-10-19: Shortened the footer city invitation to “Organise in your city.”
  The global-movement destination and link behavior are unchanged; running
  desktop/mobile footer rendering was reviewed. [Owner request](../raw/2026-10-19-footer-city-link-copy.md);
  [review receipt](build/14-homepage-polish-review.md#later-footer-copy-refinement).

- 2026-10-19: Rebalanced the footer lockup and navigation into a compact two-part
  desktop grid, with a stacked mobile layout and consistent responsive inset.
  The full-width red background and square corners remain. Desktop and mobile
  footer captures were reviewed. [Owner request](../raw/2026-10-19-footer-arrangement-request.md);
  [review receipt](build/14-homepage-polish-review.md#footer-lockup-and-link-rhythm).

- 2026-10-19: Added three supplied work photos to the homepage carousel before
  the presentation images. Preserved the originals, generated 440/880/1200px
  WebP variants, added alt text and updated the image catalogue and concern
  register. The production build and five-width running carousel checks pass;
  all 22 responsive/reveal images decode, and desktop/mobile captures were
  reviewed. [Photo batch](../raw/2026-10-19-homepage-carousel-additional-photo-batch.md);
  [carousel record](build/17-homepage-photo-carousel.md).

- 2026-10-19: Analysed the owner-supplied Solumob vlog, transcript and repeat
  application for case-study research. Preserved 23 original-resolution video
  frames, source hashes, selected verbatim application fields and corroborating
  2019 article excerpts. The dossier distinguishes visible work from presented
  results, separates the 2019 event from the 2020 application, and records
  conflicting duration/count claims. B-036 partly resolves video evidence and
  carries final assets, credits, use and outcome questions. No site implementation
  changed. A local automatic transcription adds speech timecodes; recognition
  errors remain explicit and no uncertain speech is treated as a verified quote.
  [Evidence dossier](build/44-solumob-video-analysis.md);
  [source capture](../raw/2026-10-19-solumob-video-sources.md).

- 2026-10-19: Prepared all worktree work for local main integration at the
  owner’s request. Corrected stale approved-copy and homepage-action assertions
  in EditionWebsiteTest and MessagingPreviewTest; all 115 tests and 887
  assertions pass, and the production build passes. L-07 is resolved; B-035
  retains its production checks. Preserved local attachments, runtime settings,
  database and private preview files outside Git.
  [Integration record](build/45-worktree-integration.md);
  [test output](../raw/2026-10-19-worktree-merge-tests.json).
- 2026-10-19: Verified the squash-merged main checkout: 115 PHP tests, 887
  assertions, five JavaScript tests and the production build pass. Chromium
  checks pass four public routes at desktop/mobile widths plus embedded forms,
  mobile navigation and admin login, with no applications submitted. The Herd
  alias now points to the main checkout; historical main documents and local
  worktree files are preserved before archive is requested.
  [Merged verification](../raw/2026-10-19-main-integration-verification.json);
  [integration record](build/45-worktree-integration.md).

- 2026-10-19: Completed production rollout checks on Forge. Set APP_URL to the
  production hostname and refreshed the config cache. Verified production mode,
  debug off, six migrations, the four active organizer accounts, TLS, and direct
  origin routes. Mail remains on log with no Resend key or verified sender;
  Cloudflare Email Routing awaits a destination mailbox. Forge database backups
  require Business, DigitalOcean backup/restore remains unknown, and the shared
  server has 96% disk use. Cloudflare/public resolvers point to Forge while this
  workstation's resolver still returned stale one.com/Netlify data. No forms
  were submitted and no mail was sent. B-035 remains partly resolved.
  [Rollout record](build/46-production-rollout.md);
  [captured observations](../raw/2026-10-19-production-rollout-observations.md).

- 2026-10-19: Moved the homepage hand-drawn CTA after the hero image at the
  mobile stack breakpoint. The arrow now leads toward the Makers and Social
  organisations blocks; a 390px running-page capture confirms the order, while
  desktop placement stays beneath the intro. Updated the selected CTA record
  and B-034. [Owner request](../raw/2026-10-19-mobile-hero-cta-order-request.md);
  [implementation record](build/42-homepage-apply-now-scribble-options.md).
