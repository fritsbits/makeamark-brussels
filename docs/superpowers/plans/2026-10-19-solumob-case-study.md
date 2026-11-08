# Solumob case-study implementation plan

**Goal:** Style the approved shared case page and implement recommended links
L01–L03, keeping publication pending Solumob review.

**Spec:** `docs/wiki/build/47-solumob-case-study.md`; owner approval in
`docs/raw/2026-10-19-solumob-build-approval.md`.

**Architecture:** One file-based case, a shared visibility policy and an
explicit unpublished configuration flag. The canonical route, selected media,
inbound links and sitemap use that policy; local/testing previews are noindex.
Retain the legacy preview URL as a redirect. Use the existing Laravel, Flux,
Alpine, Tailwind, cream/red palette and named patterns in app.css.

**Review focus:** unpublished production isolation; consistent link targets;
legible original deliverables on phones; making-of distinguished from campaign
film; canonical/social metadata independent of the 2027 edition.

- [x] Add failing feature tests for canonical draft, redirects, recommended
  links, publication isolation, selected media and conditional sitemap.
- [x] Extract native presentation images into a private content directory,
  preserving source PDF/hash and extraction provenance. Inspect selected work.
- [x] Implement shared publication policy, canonical controller/routes,
  metadata props, featured/audience invitation component and named page CSS.
  Preserve supplied draft copy and approved structure. Use original output
  images, clearly labelled documentary stills, click-to-load making-of video,
  credits and both onward actions. Never invent a campaign film or quote.
- [x] Run PHP suite/build and one batched browser pass over case/home/audiences
  at desktop/phone/narrow widths, keyboard, reduced motion and no JavaScript.
- [x] Record selected P01/L01–L03, unselected L04, original assets and checks
  in Cascade and the Notion sitemap. Complete an independent final review.

No deployment or messages to Solumob are authorised by this approval.
