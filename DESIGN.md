---
name: Make a Mark Brussels
description: Two-colour broadsheet for a one-day Brussels make-a-thon.
colors:
  marker-red: "#C33F40"
  headline-red: "#D14B4C"
  paper-cream: "#F5E2CA"
  paper-cream-light: "#FFF6EA"
  paper-cream-inset: "#EBD5BB"
  paper-cream-rule: "#DFC3A3"
  ink: "#29231D"
  ink-brown: "#6B5645"
  duotone-tint: "rgba(121, 26, 27, 0.3)"
typography:
  homepage-display:
    fontFamily: "Big Shoulders Display, Impact, sans-serif"
    fontSize: "clamp(72px, 10.5vw, 150px)"
    fontWeight: 600
    lineHeight: 0.92
    letterSpacing: "0.01em"
  display:
    fontFamily: "Big Shoulders Display, Impact, sans-serif"
    fontSize: "clamp(56px, 9vw, 118px)"
    fontWeight: 600
    lineHeight: 0.92
    letterSpacing: "0.01em"
  headline:
    fontFamily: "Big Shoulders Display, Impact, sans-serif"
    fontSize: "clamp(40px, 6vw, 78px)"
    fontWeight: 600
    lineHeight: 0.92
    letterSpacing: "0.01em"
  application-heading:
    fontFamily: "Big Shoulders Display, Impact, sans-serif"
    fontSize: "clamp(2rem, 5vw, 4.25rem)"
    fontWeight: 600
    lineHeight: 0.98
  title:
    fontFamily: "Big Shoulders Display, Impact, sans-serif"
    fontSize: "clamp(26px, 3vw, 34px)"
    fontWeight: 600
    lineHeight: 0.92
    letterSpacing: "0.01em"
  numeral:
    fontFamily: "Big Shoulders Display, Impact, sans-serif"
    fontSize: "clamp(48px, 5vw, 64px)"
    fontWeight: 300
    lineHeight: 0.92
  process-numeral:
    fontFamily: "Big Shoulders Display, Impact, sans-serif"
    fontSize: "clamp(72px, 7vw, 104px)"
    fontWeight: 300
    lineHeight: 1
  body:
    fontFamily: "Plus Jakarta Sans, system-ui, sans-serif"
    fontSize: "17px"
    fontWeight: 400
    lineHeight: 1.6
  reading:
    fontFamily: "Plus Jakarta Sans, Plus Jakarta Sans fallback: Arial, system-ui, sans-serif"
    fontSize: "1.0625rem"
    fontWeight: 300
    lineHeight: 1.6
  field-label:
    fontFamily: "Plus Jakarta Sans, system-ui, sans-serif"
    fontSize: "1rem"
    fontWeight: 600
    lineHeight: 1.5
  supporting:
    fontFamily: "Plus Jakarta Sans, system-ui, sans-serif"
    fontSize: ".875rem"
    fontWeight: 400
    lineHeight: 1.6
  label:
    fontFamily: "Plus Jakarta Sans, system-ui, sans-serif"
    fontSize: "12px"
    fontWeight: 600
    letterSpacing: "0.12em"
  small:
    fontFamily: "Plus Jakarta Sans, system-ui, sans-serif"
    fontSize: "13px"
    fontWeight: 400
rounded:
  card: "10px"
  photo: "10px"
  block: "18px"
  pill: "999px"
spacing:
  block-margin: "clamp(12px, 2vw, 24px)"
  gutter: "clamp(20px, 3vw, 40px)"
  section-y: "clamp(56px, 8vw, 96px)"
  container: "1360px"
components:
  pill-outline:
    textColor: "{colors.ink}"
    typography: "{typography.label}"
    rounded: "{rounded.pill}"
    padding: "12px 20px"
  pill-outline-hover:
    backgroundColor: "{colors.marker-red}"
    textColor: "{colors.paper-cream-light}"
  pill-solid:
    backgroundColor: "{colors.marker-red}"
    textColor: "{colors.paper-cream-light}"
    typography: "{typography.label}"
    rounded: "{rounded.pill}"
    padding: "12px 20px"
  pill-solid-hover:
    backgroundColor: "{colors.paper-cream-light}"
    textColor: "{colors.marker-red}"
  block-red:
    backgroundColor: "{colors.marker-red}"
    textColor: "{colors.paper-cream-light}"
    rounded: "{rounded.block}"
    padding: "{spacing.section-y} clamp(20px, 4vw, 56px)"
  org-tag:
    textColor: "{colors.paper-cream-light}"
    rounded: "{rounded.pill}"
    padding: "8px 14px"
  nav-link:
    textColor: "{colors.ink}"
    typography: "{typography.label}"
---

# Design System: Make a Mark Brussels

## Overview

**Creative North Star: "The Brussels Broadsheet"**

The site reads like a two-colour broadsheet printed for the event.
Condensed uppercase headlines in red shout across warm cream paper.
Inset red blocks with rounded corners break the page into editions of the story. All photographs use neutral monochrome and reveal their original color on rollover or activation.
Density is low and the voice is loud: a few big statements, short body copy, generous section spacing.

The print metaphor sets the limits.
There are two ink families (red and warm neutral) on cream paper, with two red surfaces, two cream surfaces and two warm neutral inks creating tonal depth.
Photos sit on the page at slight angles, like clippings, with soft print shadows; everything else is flat.
The hand-drawn Brussels logo is the one human mark among the type.

**Key Characteristics:**
- Red and warm-neutral inks on cream; no unrelated accent colour.
- Condensed uppercase display type at poster scale, set tight (0.92 line height).
- Rounded red blocks inset from the viewport edge, alternating with cream sections.
- Neutral monochrome photography throughout, revealing full colour on hover, tap or keyboard activation.
- Tracked uppercase pills as the only button form.

## Colors

A print palette: red does the shouting, warm ink does the reading, cream is the paper.

### Primary
- **Marker Red** (`marker-red`): the main ink. Fills the red blocks, the footer, solid pills, the mobile menu button, and the photo-strip focus ring and scrollbar. Text inside a red block is cream.
- **Headline Red** (`headline-red`): a slightly brighter red for display type, numerals, FAQ questions, and the wordmark on cream. It also supplies `--red-surface` for the homepage process-number panels. At 3.45:1 on cream it is for large text only; small cream text does not appear on the lighter red panels.

### Neutral
- **Paper Cream** (`paper-cream`): the page background, nav background and display headings inside red blocks.
- **Paper Cream Light** (`paper-cream-light`): body text and outlines inside red blocks (4.78:1 on Marker Red).
- **Paper Cream Inset** (`paper-cream-inset`, `#EBD5BB`): darker cream behind the homepage's two equal audience panels in the refinement preview. It comes from the global site's schedule panels. Ink text has 12.16:1 contrast on it. [Review](docs/wiki/build/12-global-design-review.md)
- **Ink** (`ink`): softened near-black (`#29231D`) for body copy, nav links, and the skip link (12.28:1 on Paper Cream).
- **Ink Brown** (`ink-brown`): a lighter dark brown (`#6B5645`) for selected lead and secondary form copy (5.46:1 on Paper Cream; 6.45:1 on Paper Cream Light; 4.85:1 on Paper Cream Inset), including the audience commitment lead.
- **Duotone Tint** (`duotone-tint`): a dark red wash multiplied over greyscale photos to create the duotone.
- **Paper Cream Rule** (`paper-cream-rule`): fine decorative borders and dividers on cream surfaces, including choice-tile frames and horizontal rules.
- **Field Border** (`--field-border`): aliases Ink Brown for input, select and textarea boundaries. The owner selected this stronger border in the [visual launch review](docs/raw/2026-10-19-launch-visual-review-selection.md); it has 5.46:1 contrast against Paper Cream and 6.45:1 against the field fill.

### Named Rules
**The Warm Ink Rule.** Red, softened near-black and dark brown on cream. Use dark brown selectively for typographic hierarchy; do not add unrelated accent colours.

**The Large Red Rule.** Headline Red is used for large text and the lighter-red process-number panels. Body-size red text on cream uses Marker Red at minimum, and small cream text sits on the deeper Marker Red. Lighter-red panels carry large numerals only.

## Typography

**Display Font:** Big Shoulders Display (with Impact, sans-serif), self-hosted in weights 300 and 600.
**Body Font:** Plus Jakarta Sans (with system-ui, sans-serif), self-hosted in weights 400 and 600.

**Character:** A condensed, industrial poster face does all the talking.
A friendly, open geometric sans carries the explanations.

### Hierarchy
- **Display** (600, `clamp(56px, 9vw, 118px)`, 0.92): the homepage hero is left aligned and capped at 13ch, paired with the photo collage on desktop.
- **Headline** (600, `clamp(40px, 6vw, 78px)`, 0.92): section headings such as "Info for Makers" and "FAQ".
- **Title** (600, `clamp(26px, 3vw, 34px)`, 0.92): step titles. FAQ questions use the same face at `clamp(20px, 2.2vw, 28px)`.
- **Numeral** (300, `clamp(48px, 5vw, 64px)`): zero-padded step and FAQ numbers ("01", "02"), light weight against the semibold titles.
- **Process numeral** (300, `clamp(72px, 7vw, 104px)`, 1): homepage process numbers on lighter-red panels; uses `--fs-process-num`.
- **Body** (400, 17px, 1.6): all prose. The hero intro rises to `clamp(16px, 1.4vw, 19px)`. Measure stays between 560 and 680px.
- **Lead** (300, `--fs-lead`, 1.4): the makers-page hero introduction, in Paper Cream.
- **Commitment lead** (300, `--fs-lead`, 1.4): the paragraph beneath “What you commit to,” in Ink Brown.
- **Audience form** (17px / 1.6): labels use 600, answers and validation errors use 400. Secondary guidance, privacy and sending copy use Ink Brown and the reading text's 300 weight at the same size.
- **Label** (600, 12px, 0.12em tracking, uppercase): kickers, nav links, footer links, and pill text.

### Named Rules
**The Shout and Explain Rule.** Display type is always uppercase Big Shoulders. Anything a reader has to understand (instructions, answers, dates) is sentence-case Plus Jakarta Sans.

**The Light Numeral Rule.** Sequence numbers use the 300 weight, so the semibold title next to them stays the loudest element.

## Layout

The owner-selected distilled homepage pairs its existing headline,
introduction and status with a two-photo collage. Equal audience actions live
in the two panels below; duplicate hero links were removed by owner selection.
At 860px and below, the hero becomes
one column and displays one group image. The hero leads directly to equal
audience panels on darker cream. One red edition block combines date and youth
focus; a Brussels photo with evidence links follows. The homepage has no
process section, story strip or global FAQ. Audience pages carry process,
preparation and cost details. The original remains in the local comparison.
[Selection and implementation](docs/wiki/build/13-messaging-review.md)

A single centred column with a 1360px container and a fluid gutter (`clamp(20px, 3vw, 40px)`).
Cream sections sit directly on the page.
Red blocks are inset by the block margin on three sides, so cream paper shows around them like a page border.
Sections breathe with `clamp(56px, 8vw, 96px)` vertical padding.

Grids are simple: the homepage hero uses 1.12fr / 1fr; edition details use two equal columns; the evidence section uses 1.1fr / .9fr with a maximum 600px photo; About uses 1.2fr / 1fr. Both audience panels have equal weight and the same darker-cream surface.
Everything collapses to one column at 860px, and the nav becomes a menu toggle at 760px.
The original comparison retains its horizontally scrolling photo strip; the selected homepage uses one Brussels evidence image.
The sticky nav is about 72px tall on desktop and 84px on mobile. Anchored
sections retain the existing 72px offset. The hero and audience panels share
80px of adjacent padding on wide desktop and 48px on phones, fluid between.
[Polish selection and verification](docs/wiki/build/14-homepage-polish-review.md).

## Elevation & Depth

The page is flat.
Depth appears only on photographs, which behave like printed clippings laid on the paper.

### Shadow Vocabulary
- **Clipping** (`box-shadow: 0 10px 24px rgba(60, 30, 20, 0.18)`): all photographs use the shared `.photo-frame`, with rounded corners and no paper border.

### Named Rules
**The Only Photos Lift Rule.** Blocks, pills, tags, and text never cast shadows. If something isn't a photograph, it stays flat on the paper.

**The Clipping Tilt Rule.** Hero photos rotate -5deg and 4deg on desktop, or -2deg on mobile; strip photos rotate between -2deg and 2deg, and the group photo rotates 3deg. Alternate strip items drop 18px. Revealed photos straighten; reduced-motion users get them straight.

## Shapes

Soft rectangles and full pills.
Red blocks and the footer use the block radius (18px), photo cards the shared card radius (10px), and the skip link and small chrome around 6 to 10px.
Interactive and list elements (pills, organisation tags) are full pills.
Circles appear only as the FAQ disclosure icon and the mobile menu button.
Lines are hairlines: 1px Paper Cream Rule for decorative dividers and choice-tile frames. Text-field boundaries use the 1px Field Border token. Pill and icon outlines retain their component colors at 1.5px.

## Components

### Pills (buttons)
Ticket-stub confident: uppercase, tracked, outlined at rest, flooding with ink on hover.
- **Shape:** full pill, 1.5px border, 12px × 20px padding, Label type.
- **Outline on cream:** Marker Red border with ink text. Hover and focus flood with Marker Red and turn the text cream.
- **Outline in a red block:** a cream border in `currentColor`. Hover floods with Paper Cream Light and turns the text red.
- **Solid:** Marker Red fill with cream text, inverting to cream with red text on hover. Used only for the primary action of the moment (Apply while applications are open).
- Pills render through `flux:button` with the `pill` class, and external links open in a new tab.

The owner selected R02: a 260ms left-to-right ink sweep with a contrasting
duplicate label in the same clipped layer. The base label and background stay
steady; the overlay changes both colours together. The duplicate is hidden
from assistive technology. Shared 18px Flux arrows stay aligned with the words.
Both red/cream-light inverse pairs reach 4.78:1 contrast. Keyboard focus triggers
the sweep with a visible 3px outline; reduced motion makes the change instant.
Existing cream-filled audience hero CTAs keep their resting appearance and
sweep red. The prior audience-arrow translation is superseded by this treatment.
[Selection and receipt](docs/wiki/build/16-button-rollover-review.md#selected-implementation-receipt)

### Red Block
The broadsheet's feature box: Marker Red, 18px radius, inset from the viewport, and padded generously.
Headings inside are Paper Cream, and body text and links are Paper Cream Light.

### Photography and photo strip

The hero currently uses two temporary international photos, recorded with
source URLs in `resources/content/site.json` under `hero.photos`. They are
locally served responsive WebPs and use neutral grayscale with 1.04 contrast,
without a cream/red wash. A visible source caption identifies the global
community. Photo corners use the 10px card radius and the existing Clipping
shadow. The desktop collage tilts -5deg and 4deg; mobile shows one image at
-2deg. Reduced-motion mode removes these rotations. Replace these placeholders
with sourced Brussels photos before public launch. All three homepage photographs share
`<x-photo-reveal>` with a monochrome base, unfiltered color overlay and the
same rounded frame and shadow. [Photo selection](docs/raw/2026-10-18-all-photo-treatment-selection.md); [homepage selection](docs/raw/2026-10-18-messaging-selection.md).
- **Evidence photo:** one Brussels group photo with a 600px maximum width.
- **Original comparison only:** the former edition photo and tilted 9:16 story strip remain in the preserved version.
- **Monochrome and color reveal:** all photos use `grayscale(1) contrast(1.04)` through `.photo--monochrome`. Each reveals an unfiltered original-color overlay with a 700ms expanding reveal, 40px feather, 1.025 zoom and straightening. Click, tap or Enter pins the color image; Escape restores monochrome. Reduced-motion mode removes zoom, tilt and the transition. Without JavaScript, monochrome remains visible. The saved Riso preset belongs to the local tool and is not selected by public homepage content. [Selection and use](docs/wiki/build/11-site-photo-treatments.md).

### Organisation Tags
Cream-outlined pills inside a red block, 14px text, wrapping in a 10px-gap flow.
Linked tags underline on hover.

### FAQ Disclosure
A native `details` list with Paper Cream Rule hairlines above and below.
Each row has a light numeral, an uppercase red question, and a 28px circular plus icon that becomes a minus when the row is open.
Answers indent under the question to 680px wide.

### Homepage finishing patterns

Evidence links use underlined resource rows, Paper Cream Rule hairlines and 20px Flux
outward-arrow icons. Organiser names and roles align in two columns on desktop
and stack at 860px and below. The footer groups audience/about, community,
and privacy/contact links, with 44px minimum link height. At 760px and below,
the audience group spans the footer and the remaining groups use two columns.
The edition anchor reads "Explore ways to take part" and retains #take-part.
[Owner-selected polish](docs/wiki/build/14-homepage-polish-review.md).

### Navigation
A sticky cream bar with the uppercase red wordmark on the left and Label-style ink links on the right.
Links underline on hover and focus with a 4px offset.
Below 760px a 48px Marker Red circle with a cream hamburger opens a full-width cream drop-down; the glyph becomes a close mark while open.

## Do's and Don'ts

### Do:
- **Do** set every heading in uppercase Big Shoulders Display at 0.92 line height, coloured Headline Red on cream or Paper Cream in a red block.
- **Do** put the most important information for a phase in a red block inset by the block margin with an 18px radius.
- **Do** use the shared photo-reveal component for every public photo, with monochrome styling and the original-color reveal.
- **Do** use the solid pill for at most one primary action per view, and outline pills for everything else.
- **Do** keep body text Ink on cream or Paper Cream Light on red, at 17px with 1.6 line height.

### Don't:
- **Don't** add a third interface colour, gradients, or tints beyond the duotone wash. The owner-selected risoprint inks are confined to opted-in photographs.
- **Don't** set body-size text in Headline Red (3.45:1 on cream fails WCAG AA for normal text).
- **Don't** add shadows to blocks, pills, or cards that aren't photographs.
- **Don't** use square or slightly rounded buttons; actions are full pills.
- **Don't** replace the hand-drawn Brussels logo with a typeset version or recolour it.


## Homepage boldness and motion

The owner selected H01–H04 and explicitly kept both hero photographs.
The headline uses a homepage-only poster scale capped at 150px, with sentence
breaks from the existing content. The hero keeps its overlapping two-photo
collage at every width, including phones. The original 118px display token
continues to serve other surfaces.
[Selection](docs/raw/2026-10-18-homepage-boldness-selection.md)

The audience headings use `clamp(40px, 4.3vw, 64px)`, with equal pill actions
and matching Flux outward arrows. The later R02 button treatment supersedes
the initial arrow translation. Hero photographs enter over 650ms with an 80ms
stagger on their outer wrappers; content stays visible at rest.
[Implementation](resources/css/app.css)

The owner selected audience arrangement A. On the homepage, shared dynamic
rows align heading bottoms, description starts and action baselines.
Descriptions use a common 34ch maximum width; cards stack at 860px and below.
The production build and six-width running checks pass. Shorter-copy
suggestions remain separate review choices.
[Selection and verification](docs/wiki/build/18-audience-arrangement.md)

The evidence section now has its heading above a photo stage capped at 1200px,
followed by its caption and resource links. The whole photograph grows from
75% to full width on desktop and 90% to full width at 860px and below as its
stable stage travels from 95% to 28% of viewport height. Growth reverses with
scrolling, without pinning or changing document height. Its 1200px WebP comes
from the original 1200×900 Brussels photograph. Existing inner monochrome and
original-colour reveal treatments remain independent.
[Markup](resources/views/home.blade.php);
[scroll component](resources/js/scroll-photo.js)

Reduced-motion mode removes entrances, arrow translation and photo scaling;
runtime preference changes restore full size. Without JavaScript, the final
full-size photo remains visible. Five-width browser checks, keyboard/touch,
no-JavaScript and reduced-motion checks verify these behaviors.
[Implementation receipt](docs/wiki/build/15-homepage-boldness-review.md#selected-implementation-receipt)

This selection supersedes the earlier 600px, side-by-side evidence layout
and the phone treatment that displayed one hero photo. The general photography
and colour-reveal rules still apply.

## Audience application pages

The selected makers refinements use Marker Red for the poster opening, actions
and checked choices, with cream-light fields and cream-inset around the send
action. On desktop, a sticky introduction sits beside the grouped form; mobile
stacks it above. Fields use the existing 10px corner token, ink focus rings and
inline required markers. The send button is a full pill. Rules live in
`resources/css/makers.css`, scoped to `.audience-page`; both tracks retain
their own content and the existing process treatment.
[Owner selection and implementation](docs/wiki/build/23-makers-polish-review.md#selected-implementation-receipt).

Audience pages separate the red hero panel from the sticky nav with a fluid
20–36px gap. The nav uses a fine cream-rule bottom edge. The panel has equal
80px vertical padding at desktop widths and equal 56px padding on phones.
[Hero arrangement](docs/wiki/build/28-maker-hero-arrangement.md#vertical-spacing-refinement).


### Flux form controls and section class

Maker fields use Flux native inputs, textareas and selects, with Flux checkbox
controls and native noscript fallbacks. Language selectors fit complete labels
and remain at the body type size. Application wrappers define shared label,
error and focus behavior. The decorative section base class is `section-block`;
`block` remains Tailwind's display utility and must not receive site decoration.
[Hardening record](docs/wiki/build/24-makers-hardening.md).

### Public typography roles

Public reading paragraphs use a genuine self-hosted Plus Jakarta Sans Light
300 at 17px / 1.6. Practical controls remain Regular 400; Flux field labels
use 17px / 600. Supporting form copy also uses 17px / 400, with Ink Brown for
secondary guidance, privacy and sending status and Ink for validation errors.
These roles are scoped to
the homepage, both audience pages and privacy notice; tools and admin retain
their existing typography. The hero return action
uses a 16px back arrow, 14px Edition label and 44px target. Date metadata is
14px; the commitment heading balances its lines and the application title is
capped at 68px, with a 32px minimum to accommodate whole words at narrow widths
and enlarged text settings. Application headings use their available width
and wrap at spaces. Emergency word wrapping belongs to prose, links and errors.
The font face and fallback include a separate 300 declaration,
with public-page preload and its OFL license beside the font asset.
[Owner selection and verification](docs/wiki/build/25-makers-typography-review.md#selected-implementation-receipt).

The owner extended the makers visual and form refinements to organisations:
both audience pages share the red hero, sticky application introduction,
cream fields, Flux controls, checked-choice treatment and pill send action.
Shared audience patterns remain in `resources/css/makers.css`; shared type
roles live in `resources/css/public-typography.css`.
[Extension request](docs/raw/2026-10-18-public-typography-and-organisation-parity-request.md).
