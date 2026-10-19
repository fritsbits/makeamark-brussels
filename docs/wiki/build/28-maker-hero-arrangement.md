---
title: Maker hero arrangement
tags: [design, layout, makers, review]
sources:
  - "../../raw/2026-10-19-maker-hero-lead-date-swap-request.md"
  - "../../raw/2026-10-19-date-lockup-title-row-request.md"
  - "../../raw/2026-10-19-date-lockup-title-row-request.png"
  - "../../raw/2026-10-19-date-lockup-title-row-annotation.png"
  - "../../raw/2026-10-19-date-lockup-d06-d-selection.md"
  - "../../raw/2026-10-19-maker-hero-arrangement-request.md"
  - "../../raw/2026-10-19-maker-hero-arrangement-selection.md"
  - "../../raw/2026-10-19-maker-hero-arrangement-implementation-request.md"
  - "../../raw/2026-10-19-maker-hero-proportions-request.md"
  - "../../raw/2026-10-19-maker-hero-title-shortening-request.md"
  - "../../raw/2026-10-19-maker-hero-title-selection-2.md"
  - "../../raw/2026-10-19-maker-hero-padding-request.md"
  - "../../raw/2026-10-19-maker-hero-equal-padding.md"
  - "../../raw/2026-10-19-maker-hero-current.png"
  - "../../../resources/views/sections/nav.blade.php"
  - "../../../resources/views/audience.blade.php"
  - "../../../app/Http/Controllers/AudiencePageController.php"
  - "../../../resources/content/editions/2027.json"
  - "../../../resources/css/makers.css"
  - "../../../DESIGN.md"
  - "../../../.lavish/maker-hero-arrangement.html"
phase: Build
updated: 2026-10-19
status: implemented and browser-verified
---

# Maker hero arrangement

## Current lead and date order

The owner requested swapping the lead and date and improving their alignment.
[Request and screenshot](../../raw/2026-10-19-maker-hero-lead-date-swap-request.md)
The right column now starts with the D06-D date, aligned with the headline's
top. The lead and application action form a group below it, with a 24px gap.
A 36px gap separates the date and group; automatic remaining space aligns the
group's bottom with the headline at wide desktop widths. Intermediate columns
can grow taller than the headline as copy wraps. At 860px and below the hero
uses one column, in title, date, lead and action order. The mobile rule now
overrides the makers desktop columns, removing the empty right track.
[Template](../../../resources/views/audience.blade.php);
[styles](../../../resources/css/makers.css)

This supersedes the previous title/lead top alignment and bottom-right date
placement described below. D06-D typography, the date source, and equal
80px desktop / 56px phone vertical padding remain.
[Date decision](34-date-lockup-variations.md#d06-d-selected-and-implemented);
[styles](../../../resources/css/makers.css)

The production build passed. Running-page checks at 1700, 1440, 1024, 861, 860,
390 and 320px confirmed date-before-lead-before-action order, a shared left
edge in the supporting column, loaded fonts, no horizontal overflow and no
JavaScript errors. Desktop and phone captures were reviewed; the layout
detector returned no findings. These checks close the refinement under B-023.

## Earlier arrangement decisions

The date lockup's visual reading order was later refined to owner-selected
D06-D: Saturday above a fine rule, then the day number beside month/year.
This is implemented and browser-verified; the earlier arrangements below are
the decision history. [Current date decision](34-date-lockup-variations.md#d06-d-selected-and-implemented)

The owner first selected an editorial date lockup under the title with the lead
and application action on the right. A later refinement moved the date to the
right column and shortened the title. The date remains derived from the
edition's `startDate`; the separate Edition link and repeated Brussels label
are removed. [Hero](../../../resources/views/audience.blade.php);
[date source](../../../resources/content/editions/2027.json);
[current capture](../../raw/2026-10-19-maker-hero-current.png)

## Proportions and title refinement

The owner later asked for a better balance between the headline and supporting
column, suggested moving the date right, and then asked to shorten the title
without changing its meaning. [Proportions request](../../raw/2026-10-19-maker-hero-proportions-request.md);
[title request](../../raw/2026-10-19-maker-hero-title-shortening-request.md)
That refinement gave the headline 1.2 parts of the desktop grid and the
supporting column 0.8 parts. Its shorter title is “Use your creative skills to
make a difference.” The right column kept the introduction at the top and
aligned the date with the application action at the bottom. The date value and
format remain unchanged. [Template](../../../resources/views/audience.blade.php);
[styles](../../../resources/css/makers.css)

## Evidence and interpretation

- **Observed:** the header wordmark links to `/`, and the “Edition” link also
  links to `/`. [Navigation](../../../resources/views/sections/nav.blade.php); [hero](../../../resources/views/audience.blade.php)
- **Observed:** the edition data provides both a display date and ISO
  `startDate`, currently 2027-03-27. The lockup formats that ISO date as a large
  day number beside its weekday and month/year. [Edition data](../../../resources/content/editions/2027.json);
  [controller](../../../app/Http/Controllers/AudiencePageController.php);
  [hero](../../../resources/views/audience.blade.php)
- **Interpretation:** removing the extra return link should retain a route to
  the edition page while reducing a duplicated navigation cue. The masthead is
  consistently present in the supplied screenshot.
- **Interpretation:** moving the date beneath the title would group the event
  detail with the subject of the invitation. A calendar icon may help identify
  the line as event metadata, but the date itself is already explicit.
- **Unknown:** whether the intended date format should keep the weekday, and
  whether Brussels needs to repeat for visitors arriving directly on this page.

## Options

The [Lavish board](../../../.lavish/maker-hero-arrangement.html) shows the
existing hero and three concrete date arrangements. It has separate review
questions for the Edition link and date format/placement.

| ID | Arrangement | Benefit | Cost |
| --- | --- | --- | --- |
| D-A | Under-title line: calendar icon + “Saturday 27 March 2027”; omit Brussels and the Edition link. | Directly groups the date with the invitation and keeps the full event date easy to read. | Adds a fourth line to the left-side title block; check it still breathes at tablet widths. |
| D-B | Under-title day stamp: large “27” beside “Saturday · March 2027”; omit Brussels and the Edition link. | Makes the day memorable and gives the title a more editorial lockup. | Promotes a date detail that may compete with the headline; icon is unnecessary. |
| D-C | Keep the date with the right-side lead, add a calendar icon and retain “Brussels”; omit the Edition link. | Preserves the current reading order and keeps location explicit. | Leaves date separate from the title and repeats the city already named by the wordmark. |

**Owner-selected direction:** D-B with the separate Edition link removed. Use
an editorial day stamp under the title: a large “27” beside “Saturday / March
2027”. Omit the calendar icon and Brussels because the wordmark already names
the city. Keep the linked wordmark as the route to the edition homepage.
[Owner request](../../raw/2026-10-19-maker-hero-arrangement-request.md);
[selection](../../raw/2026-10-19-maker-hero-arrangement-selection.md)

## Implementation and verification

The makers page implements the selected lockup from the edition's ISO date,
using the documented display numeral and body type roles. At 1440px the title
and lead share a top coordinate; at 390px the date follows the title and
precedes the lead. Both widths have zero horizontal overflow. The makers hero
has no separate Edition link. The organizations page retains its Edition link
and existing date/location row. [Template](../../../resources/views/audience.blade.php);
[styles](../../../resources/css/makers.css);
[running-product request](../../raw/2026-10-19-maker-hero-arrangement-implementation-request.md)

The proportions and title refinement were reviewed in the running makers page
at desktop: the title uses fewer lines, while the date and application action
share the lower right column. Production CSS builds successfully.

## Date and first title row alignment

The owner asked for the date block to match the first title row's height and
align with it exactly. On desktop, the date lockup now uses the title's
`--fs-hero` size and `.92` line height as its height, placing the weekday and
date row at opposite ends. Its type scales down at narrower desktop widths so
the contents fit that row. The mobile lockup retains its selected D06-D sizes
and natural height. [Request and references](../../raw/2026-10-19-date-lockup-title-row-request.md);
[styles](../../../resources/css/makers.css)

## Vertical spacing refinement

The owner asked for more top and bottom padding inside the red hero, a subtle
bottom rule on the navigation, and space between the navigation and hero.
[Request](../../raw/2026-10-19-maker-hero-padding-request.md) The shared nav now
has a `--cream-rule` bottom border. Audience heroes sit 20–36px below the nav;
the red panel uses equal 80px top/bottom padding at desktop widths and equal
56px padding on phones. [Padding correction](../../raw/2026-10-19-maker-hero-equal-padding.md);
[Styles](../../../resources/css/app.css);
[audience styles](../../../resources/css/makers.css)
