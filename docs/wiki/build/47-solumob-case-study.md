---
title: Solumob case study and selected discovery links
tags: [case-study, solumob, wireframe, sitemap, build]
sources:
  - "../../raw/2026-10-19-solumob-lavish-selection.json"
  - "../../raw/2026-10-19-solumob-build-approval.md"
  - "../../raw/2026-10-19-solumob-presentation-assets.json"
  - "../../raw/2026-10-19-solumob-build-verification.json"
  - "../../raw/2026-10-19-solumob-page-request.md"
  - "../../raw/2026-10-19-solumob-publication-instruction.md"
  - "../../raw/2026-10-19-solumob-preview-verification.json"
  - "../../raw/2026-11-08-solumob-link-approval.md"
  - "../../raw/2026-11-08-solumob-link-verification.json"
  - "https://app.notion.com/p/3fed3ecc475c81f4b46bdef7939b038d"
  - "https://app.notion.com/p/3fed3ecc475c81138ac2cdf39db1d527"
  - "../../ux/_structure.md"
  - "44-solumob-video-analysis.md"
phase: Build
updated: 2026-11-08
status: P01 and L01–L03 implemented and verified; publication enabled by owner instruction
---

# Solumob case-study page

The owner requested the supplied case as a page and a Lavish link proposal.
The existing [Structure](../../ux/_structure.md) anticipates individual cases
when material exists. The [Notion draft](https://app.notion.com/p/3fed3ecc475c81f4b46bdef7939b038d)
was the earlier source for the page.
[Request and source observations](../../raw/2026-10-19-solumob-page-request.md).

## Selected implementation

The owner approved P01 and the recommended L01–L03: “I follow your suggestions,
Go for it.” L04 was optional and is unselected.
[Approval capture](../../raw/2026-10-19-solumob-build-approval.md).
The user's final [Lavish selection](../../raw/2026-10-19-solumob-lavish-selection.json)
explicitly confirms those four IDs. The later [publication instruction](../../raw/2026-10-19-solumob-publication-instruction.md)
removes the review gate and authorizes shipping the case study.

`/case-studies/solumob` now renders the styled case locally, using the existing
public shell, cream/red palette, display type and named patterns in app.css.
It includes the supplied copy, an original-output hero composition, native
website before/after images, selected social work, documentary material,
credits and both onward participation links. The former tools URL redirects
to the canonical path. Implementation: `resources/views/case-studies/solumob.blade.php`,
`app/Http/Controllers/CaseStudyPreviewController.php` and
`app/Content/SolumobCaseStudy.php`.

`config/case-studies.php` enables publication. The case, selected media,
homepage/audience links and XML sitemap are available together. The former
tools URL remains a local redirect to the canonical path. Only selected media
aliases resolve to files outside `public/`; no application files or arbitrary
paths are exposed.
[Regression tests](../../../tests/Feature/CaseStudyPreviewTest.php).

The making-of uses a privacy-enhanced YouTube iframe created only after the
visitor activates Play, with a regular YouTube link available without
JavaScript. Proposed quotes and internal email guidance are omitted. Article
metadata has the canonical path and a case-specific image rather than the 2027
edition image.

## Recovered original presentation

The original nine-page `solumob.pdf` was found in Downloads/makeamark and
preserved unchanged under `docs/raw/assets/solumob-2019/presentation.pdf`.
Six native JPEGs were extracted without reconstruction; slide 8 was also
rendered at 1200×675 for the hero teaser/social image. These private resources
replace the deliverable placeholders. The [provenance manifest](../../raw/2026-10-19-solumob-presentation-assets.json)
records source/asset hashes, page and PDF object numbers, dimensions and method.

The presentation's “before” image already shows the baseline and some of the
photography. Its chronology relative to live edits therefore remains uncertain;
the comparison documents the presentation's design direction. The owner
authorized publication of the case as presented. This approval does not resolve
unknown downstream recruitment impact.

## Claim and asset matrix

| Content | Evidence | Visual / remaining work |
| --- | --- | --- |
| One day, four makers, challenge and credits | Draft v3 and its linked original-source assessment | Hero now uses native website and volunteer-design images; factual/media review pending |
| Direct website edits on the day | Frederik's recollection in draft v3 | Slide 7 native images recovered; presentation chronology and live changes need confirmation |
| Communication plan, identity refresh and tagline | Draft v3; presented baseline corroborated by video dossier | Original identity exports pending; do not claim a full rebrand |
| Photography and separate 56-second service film | Draft v3 and video shoot/edit/screening | Documentary frames at 01:38 and 01:32; retrieve original photos and service film, verify rights |
| Facebook cover, recruitment posts, testimonials and Canva templates | Draft v3 identifies slides 3–6 | Native cover and three social designs recovered; rights pending; testimonial designs are not new public quotes |
| Cindy's collaboration and volunteer contribution | Frederik's recollection in draft v3 | Validate with organisation and confirm portrait permissions |
| Later film use and positive feedback | Film use from Frederik; 2020 repeat application reports good feedback | Confirm uptake; recruitment impact remains unknown; no invented quotes or statistics |

Sources: [draft editorial notes and visual selection](https://app.notion.com/p/3fed3ecc475c81f4b46bdef7939b038d)
and [timecoded dossier](44-solumob-video-analysis.md). The stills are uncropped
references to the original presentation and vlog.

## Proposed sitemap and links

**Owner-selected direction:** one shared page at
`/case-studies/solumob`, reached through these independent placements:

- **L01, recommended:** homepage visual feature after “A feel for the day”
  and its evidence links, before the organisation roster. Heading: “One day.
  Work out in the world.” Action: “See what we made for Solumob.” The photos
  convey atmosphere; the case adds concrete work.
- **L02, recommended:** compact link on `/organisations`, between “What you
  commit to” and the application. “What can a day make?” introduces the
  collaboration without changing 2027 youth-work eligibility.
- **L03, recommended:** same placement on `/makers`, introducing the creative
  sprint and people. Both audiences visit the same case.
- **L04, optional:** link the existing Solumob name in the organisation roster,
  whose view already supports organisation URLs.

Keep the current main navigation for the first case. Revisit a Stories index
when several approved cases exist. These are design judgments based on current
`home.blade.php`, `audience.blade.php`, the organisation roster and the
[task sequence](https://app.notion.com/p/3fad3ecc475c80b5a2a0e807b4a53f60).

The [sitemap and wireframe proposal is saved as a private Notion draft](https://app.notion.com/p/3fed3ecc475c810f8f65ce1393b8283d).
**P01 and L01–L03 are approved and implemented.** The homepage feature follows
the existing photo evidence and precedes the organisation roster. Both audience
invitations follow commitments and precede the form. L04 is unselected.
`.lavish/solumob-case-study.html` preserves the visual review and selection
receipt. B-037 is closed; the owner's publication instruction removed the
review gate, but B-036 stays partly open for rights and outcome evidence.

## Verification and publication

The prior implementation check recorded **121 PHP tests / 970 assertions**, a
successful build and browser checks at 1440, 390 and 320px. That verification
predates the publication switch. For this change, the running local route
returns HTTP 200, the requested review text is absent, and the XML sitemap
includes the case URL. The publication flag enables the page, selected media,
homepage/audience links and sitemap together. The automated suite was not run
for this copy and publication update. No external deployment was performed.

The independent security review for the prior implementation found no blocking
issues. Its low-severity finding about Symfony changing draft media's cache
directive to public was reproduced and fixed by setting headers after
BinaryFileResponse construction. Mechanical design scanning found no new
primary issues; pre-existing advisories elsewhere in app.css and the stale
design-system sidecar were left outside that change.

## Merge into the current branch (8 November 2026)

The page was built on 19 October on `fritsbits/setup-cascade-digital-product` and never reached `main`.
The owner asked to link it and accepted the recommended route.
[Go-ahead](../../raw/2026-11-08-solumob-link-approval.md).
Commit `6d53641` was cherry-picked onto `fritsbits/solumob-case-study-koppelen`.
Three adaptations to the newer code:

- The XML sitemap is built from `Audiences::slugs()`, so the case URL is added to that list when published.
- The invitation component renders only on the Makers and organisations pages, so `/volunteers` shows none.
  The owner chose no volunteer invitation for now.
- The case page is added to the public sitemap in [`docs/ux/_structure.md`](../../ux/_structure.md#site-map).
- Copy follows the Maker capitalisation rule (G-01): "Makers" is capitalised in the case page and invitations.

Verified on the current branch: 175 PHP tests pass, the build passes, and a browser check at 1440, 1024, 390 and 320px finds no overflow, console errors or failed local responses.
The homepage feature, both audience invitations and the absence of one on `/volunteers` were checked in the rendered pages.
[Verification record](../../raw/2026-11-08-solumob-link-verification.json).
Production deployment is not done.
