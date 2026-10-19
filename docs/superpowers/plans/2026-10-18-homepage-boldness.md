# Homepage boldness implementation plan

> **For agentic workers:** Execute inline using superpowers:executing-plans. Track the two tasks below; the owner's final Lavish batch already instructs implementation.

**Goal:** Implement H01–H04 while retaining both atmosphere photographs.

**Architecture:** Scope homepage composition in existing named CSS patterns.
An Alpine component scales an outer group-photo wrapper using viewport
position; existing inner photo-reveal transforms stay independent. Static
HTML and reduced-motion mode show the full photograph.

**Tech stack:** Existing Laravel/Blade, Flux, Tailwind/Vite, bundled Alpine.

**Spec:** `docs/wiki/build/15-homepage-boldness-review.md` and
`docs/raw/2026-10-18-homepage-boldness-selection.md`.

## Constraints

Preserve current copy, palette, fonts, photo provenance, audience equality,
routes, keyboard/touch colour reveal and public section order. Keep both hero
photos visible, including on phones. Add no dependencies. This is the existing
Bloom workspace with the in-progress Laravel migration; do not move or stage
unrelated work.

## Review focus

- Scroll reversal must reverse growth with no document-height change.
- Reduced-motion changes at runtime must restore full size and stop entrances.
- No JavaScript must leave all photographs visible at their final size.
- Phone collage must reserve space for both photos without overflow.
- Keyboard and touch colour reveal must work while the outer photo scales.

## Task 1: Implement the selected composition and motion

Files: `resources/views/home.blade.php`, `resources/css/app.css`,
`resources/js/app.js`, new `resources/js/scroll-photo.js`, new
`public/images/edition-2026-group.1200.webp`, new
`scripts/homepage-motion-check.cjs`.

- [x] Add a read-only browser check proving photo width grows on scroll and
  reverses, then run it against the current homepage and observe failure.
- [x] Stack the evidence heading, full-width photo and links. Generate a 1200px
  WebP from the original 1200px source and add it to the responsive srcset.
- [x] Register Alpine `scrollPhoto`: use a stable outer stage, rAF-coalesced
  viewport measurement, 0.75 desktop / 0.9 phone initial scale, 1 final scale,
  runtime reduced-motion handling and listener cleanup. No pinning.
- [x] Increase the homepage hero headline toward 150px, keep its two-photo
  collage prominent, and give both audience headings more presence. Add equal
  Flux arrows to their existing actions. Use separate outer wrappers for the
  650ms, 80ms-staggered hero entrance; no content is hidden by default.
- [x] Run the browser check, production build, PHP and JS suites, and existing
  photo interaction checks. Capture desktop/phone together once, fix defects
  in one batch and confirm at most once.

## Task 2: Record the decision and verified receipt

Files: `DESIGN.md`, `docs/wiki/build/15-homepage-boldness-review.md`, active
plan/concerns, index and log.

- [x] Record all H01–H04 outcomes with actual verification, source the owner's
  photo constraint, and update B-006 without closing unrelated concerns.
- [x] Request one focused fresh review of the final diff; resolve material
  findings. No automatic commit of the wider untracked migration.

## Execution notes

Owner's final batch authorises all four changes, so no new approval gate is
needed. Inline execution avoids splitting this tightly coupled page among
implementers. Cosmetic changes use browser verification rather than tests
that duplicate CSS. The scroll check exercises real browser behavior.

Completed in the existing isolated Bloom worktree. Browser checks pass at five widths, including touch and runtime reduced motion. Build, 89 PHP tests and five JS tests pass. Independent review found no material issues. Existing Pint findings in bootstrap/providers.php and routes/console.php remain outside this change. No unrelated files were staged or committed.
