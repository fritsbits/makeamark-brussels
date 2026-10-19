---
title: Homepage audience arrangement
tags: [design, layout, homepage, implementation]
sources:
  - "../../raw/2026-10-19-homepage-audience-sentence-request.md"
  - "../../raw/2026-10-19-organisation-invitation-direction.md"
  - "../../raw/2026-10-19-audience-arrangement-selection.md"
  - "../../../.lavish/audience-arrange.html"
  - "../../../resources/views/home.blade.php"
  - "../../../resources/css/app.css"
phase: Build
updated: 2026-10-19
---

# Homepage audience arrangement

The owner selected option A from the [Lavish overview](../../../.lavish/audience-arrange.html#option-a) and later requested sentence-style audience invitations. The headings now address “Makers,” and “Social organisations,” directly, with the existing invitation copy continuing as the sentence. [Arrangement selection](../../raw/2026-10-19-audience-arrangement-selection.md); [copy request](../../raw/2026-10-19-homepage-audience-sentence-request.md)

## Selected arrangement

Both cards share dynamic grid rows for headings, descriptions and actions through CSS subgrid. Headings align at the bottom of their row, descriptions start together, and actions share a baseline. The audience labels now read as the opening address to each invitation. The heading-to-copy gap is 8px; the copy-to-action gap remains 32px. Descriptions have a common maximum width of 34ch. Equal line counts are not required. [Selected A](../../../.lavish/audience-arrange.html#option-a)

The selected option stacks the cards at 860px and below, replacing the previous 640px breakpoint. Stacked cards return to their natural content height. The scope is the homepage audience pair; links, colours and fonts retain the existing treatment. [Styles](../../../resources/css/app.css); [markup](../../../resources/views/home.blade.php#L28)

## Copy review

The first copy review offered a compact pair of 10 words each and a together
pair of 11 words each, compared with 12 words each in the current descriptions.
Neither pair was selected. [Owner request](../../raw/2026-10-19-audience-arrangement-selection.md);
[review](../../../.lavish/audience-arrange.html)

The owner then clarified the organisation invitation: the organisation brings
a communication or design need, and makers produce the work. The invitation
should describe receiving creative help and bringing a long-deferred project
to life. Wording such as "work with makers" or "tackle a challenge with a maker
team" suggests that the organisation does the production too, which the owner
wants to avoid here. [Verbatim direction](../../raw/2026-10-19-organisation-invitation-direction.md)

The owner selected direct audience labels as the sentence openings. The
existing invitation copy remains, so the maker card reads “Makers, give your
creative skills…” and the organisation card reads “Social organisations,
work with a maker team…”. This is a layout and copy treatment, not a new
decision about programme responsibilities or delivery guarantees.
[Homepage markup](../../../resources/views/home.blade.php#L28);
[copy request](../../raw/2026-10-19-homepage-audience-sentence-request.md)

## Verification

The production build passes. Its existing root-font-path warnings remain;
runtime assets all return HTTP 200. Running checks at 1440, 1200, 900, 860,
768 and 390px confirm aligned heading bottoms, description starts and action
tops above 860px, stacked cards at 860px and below, and no horizontal overflow,
JavaScript errors or HTTP errors. The original exact copy and reading weight
300 remain. [Styles](../../../resources/css/app.css);
[markup](../../../resources/views/home.blade.php#L28)

At 1440px both descriptions measure 417.89px wide, corresponding to the 34ch
limit. Heading bottoms are at 705.30px, description tops at 729.30px, and action
tops at 815.67px. Desktop and mobile screenshots were inspected.
[Desktop capture](../../../.lavish/assets/audience-arrange/implemented-desktop.png);
[mobile capture](../../../.lavish/assets/audience-arrange/implemented-mobile.png)

B-018 is closed for the selected layout and its later owner-directed sentence
copy treatment. [Build concerns](01-build-concerns.md)
