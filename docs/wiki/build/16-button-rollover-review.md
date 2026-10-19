---
title: Button rollover readability and motion options
tags: [design, motion, buttons, review, accessibility]
sources:
  - "../../raw/2026-10-18-button-rollover-selection.md"
  - "../../raw/2026-10-18-edition-action-removal.md"
  - "../../raw/2026-10-18-button-rollover-request.md"
  - "../../../resources/css/app.css"
  - "../../../resources/views/components/pill.blade.php"
  - "../../../scripts/button-rollover-check.cjs"
  - "../../../.lavish/button-rollovers.html"
phase: Build
updated: 2026-10-18
status: R02 selected, implemented and browser-verified
---

# Button rollover readability and motion options

The owner reported an unreadable rollover on the edition action and requested
animation options in Lavish. [Request](../../raw/2026-10-18-button-rollover-request.md)

## Confirmed defect and independent fix

The old CSS changed the hovered link's background but applied the new text
colour only to direct child spans. Flux renders this unwrapped label as a
direct text node. It therefore stayed cream-light on a cream-light background:
the reproduced contrast was 1.00:1. [Original CSS](../../../.lavish/assets/button-rollovers/sources/before.css.txt);
[component](../../../resources/views/components/pill.blade.php);
[computed evidence](../../../.lavish/assets/button-rollovers/sources/before-computed.json)

The anchor now owns its hover and focus colour; wrapped content inherits it.
Red/cream-light inverse pairs give 4.78:1 contrast. The same correction covers
outline, red-block and solid pill states. No new rollover animation was chosen.
[Current CSS](../../../resources/css/app.css)

The new browser regression check failed first at 1.00:1 and now passes at
4.78:1 for hover and keyboard focus on red and cream homepage surfaces. The
production build and all 89 PHP tests pass. Build retains existing runtime
font URL warnings. [Browser check](../../../scripts/button-rollover-check.cjs)

## Review options

The [Lavish overview](../../../.lavish/button-rollovers.html) uses the existing
Brussels palette, fonts and pill shape. All three options have working red and
cream demos, mouse/keyboard/tap previews and reduced-motion handling. Its
single-choice controls queue one final selection. [Artifact](../../../.lavish/button-rollovers.html)

| ID | Proposal | Agent recommendation and tradeoff |
| --- | --- | --- |
| R01 | Arrow nudges 3px over 180ms; label, outline and background stay steady. | Recommended. Quiet, clear feedback; navigation pills need a consistent arrow. |
| R02 | Ink sweeps across over 260ms with a perfectly aligned contrasting label in the same clipped layer. | Bolder alternative. Two visual label layers must align; duplicate hidden from assistive technology. |
| R03 | Arrow exits top right while another enters bottom left over 240ms; label stays still. | More playful. More icon activity than R01; choose consistently rather than mixing. |

The table records the original recommendations. The owner subsequently chose
R02. Scope is shared navigation pills; submission/loading feedback stays
separate. B-017 records the resolution. [Selection](../../raw/2026-10-18-button-rollover-selection.md);
[concerns](01-build-concerns.md)

The overview passes 1440, 1024 and 390px browser checks for all six demos,
rollover states, final choice queuing, reduced motion and zero overflow or
browser errors. Desktop and phone samples were inspected in one batch. All
measured text meets its applicable contrast threshold. Narrow review-file
exceptions record the established fonts, 40px-plus display headings, logical
section spacing and intentional review-only text sizes; public rules remain
active. [Detector config](../../../.impeccable/config.json)

## Edition action removal

The owner subsequently requested removal of “Explore ways to take part”.
The homepage edition block now omits that action. The non-open-edition status
label remains conditional on the edition state. The review's screenshot and
demo labels preserve the original reported case.
[Instruction](../../raw/2026-10-18-edition-action-removal.md);
[homepage](../../../resources/views/home.blade.php)

The contrast regression check now mounts a copy of the actual audience-link
markup on the red block in its temporary browser page when no edition action
exists. This keeps both-surface coverage without adding an action to the
public page. [Check](../../../scripts/button-rollover-check.cjs)

## Selected implementation receipt

The owner selected R02 in chat and asked to wait for another session before
continuing. The implementation preserves that session's cream-filled audience
CTAs and the separately requested edition-action removal.
[Selection](../../raw/2026-10-18-button-rollover-selection.md)

The shared Flux link now contains a normal label and a perfectly aligned
duplicate inside an `aria-hidden` overlay. The overlay paints its contrasting
background and label together, clipping from left to right over 260ms with
`cubic-bezier(.16,1,.3,1)`. Base colours stay steady throughout the sweep.
The arrow is shared by the component rather than supplied separately by each
homepage action. No JavaScript is needed.
[Component](../../../resources/views/components/pill.blade.php);
[CSS](../../../resources/css/app.css)

Outline links on cream sweep red with cream-light text. Outline links on red
sweep cream-light with red text. Solid links use the inverse pair. The existing
cream-filled hero CTAs on both audience pages sweep red while their base
appearance remains unchanged. Their existing grid/spacing rules are preserved.
[Audience CTA variables](../../../resources/css/makers.css)

Keyboard focus triggers the same sweep with a 3px visible outline.
Reduced-motion mode makes the change immediate; it does not hide either label.
The duplicate label is absent from the accessible name, leaving one navigation
link. “Explore ways to take part” remains absent from the running homepage.
[Browser verification](../../../scripts/button-rollover-check.cjs)

Verification: the new behavior check failed before implementation because no
sweep layer existed. It now passes at 1440, 860 and 390px on home, makers and
organisation pages. Both text layers meet 4.5:1, the label rectangles align
within 1px, and intermediate/reversed sweeps, stable button widths, keyboard
focus, runtime reduced motion, original destinations, no-JavaScript operation
and zero horizontal overflow pass. Desktop and phone screenshots were inspected
as one batch. Production build, all 111 PHP tests and all five JavaScript tests
pass. A focused independent review found no material issues.
[Verification script](../../../scripts/button-rollover-check.cjs)

Pint still reports existing formatting issues in `bootstrap/providers.php` and
`routes/console.php`, neither modified here. The targeted design scan reports
only existing type/radius advisories outside the changed pill rules. No new
detector exceptions were added for R02. The sidecar now carries the selected
motion and working outline/solid component previews.
[Design metadata](../../../.impeccable/design.json)

The other session also used B-014 for makers hardening. This button concern was
renamed B-017 to retain distinct IDs; the makers B-014 and its resolution stay
unchanged. [Register](01-build-concerns.md)
