---
title: Homepage photo carousel
tags: [homepage, photography, carousel, implementation]
sources:
  - "../../raw/2026-10-18-homepage-carousel-sizing-feedback.md"
  - "../../raw/2026-10-18-homepage-carousel-request.md"
  - "../../raw/2026-10-18-homepage-carousel-photo-batch.md"
  - "../../raw/2026-10-19-carousel-same-aspect-ratio.md"
  - "../../raw/2026-10-19-homepage-carousel-order-request.md"
  - "../../raw/2026-10-19-homepage-carousel-additional-photo-batch.md"
  - "../../raw/assets/photography/makers-designing-on-laptop.jpg"
  - "../../raw/assets/photography/workshop-participants-photographing.jpg"
  - "../../raw/assets/photography/makers-reviewing-work.jpg"
  - "https://www.letsmakeamark.org/resources"
  - "../../../resources/views/home.blade.php"
  - "../../../resources/content/editions/2027.json"
  - "../../../resources/js/day-carousel.js"
  - "../../../resources/js/scroll-photo.js"
  - "../../../resources/css/app.css"
  - "../../../scripts/homepage-carousel-check.cjs"
  - "../../../scripts/homepage-motion-check.cjs"
phase: Build
updated: 2026-10-19
status: eleven-slide 4:3 carousel implemented; supplied photo credits and permissions remain to confirm
---

# Homepage photo carousel

The owner requested a carousel in “A feel for the day”, using duplicates of
the existing photo until more photography is available.
[Request](../../raw/2026-10-18-homepage-carousel-request.md)

The international resources page's “Over the years” gallery supplied the
initial reference. The first implementation showed neighboring slides;
the owner then requested complete photos and a final size matching the
container. One complete photo now fills the container at the end of growth.
Natural image proportions are preserved, and the colour reveal does not zoom
or crop the image. Adjacent slides stay outside the viewport until navigation.
[Sizing feedback](../../raw/2026-10-18-homepage-carousel-sizing-feedback.md);
[Reference](https://www.letsmakeamark.org/resources)

The homepage renders eleven distinct photos in a consistent 4:3 frame, with the
existing 2026 group photo first. Following the owner's ordering request, the
bright workshop audience photo is second, the participant portrait third, the
meal-preparation photo fourth, and the dark-room audience photo last. The
three new scenes of creative and collaborative work follow the studio-team
photo and precede all three presentation images, as requested. The remaining
speaker photos retain their relative order. The portrait
serving-food photo stays catalogued but is excluded because a 4:3 crop would
remove too much of its scene. Bounded previous/next Flux buttons, native touch
scrolling and snap positions remain. Arrow keys, Home and End work within the carousel. It does not
advance automatically. Without JavaScript, the photographs remain scrollable
and the inactive controls stay hidden. Scroll growth still acts on an outer
wrapper; every slide retains the shared monochrome and original-colour reveal.
Reduced motion uses instant navigation.
[Markup](../../../resources/views/home.blade.php);
[interaction](../../../resources/js/day-carousel.js);
[growth](../../../resources/js/scroll-photo.js);
[styles](../../../resources/css/app.css)

The edition's `gallery` array holds independent file and alt-text entries. Its
order follows the earlier selection
[captured here](../../raw/2026-10-19-homepage-carousel-order-request.md) and the
new photo placement request
[captured here](../../raw/2026-10-19-homepage-carousel-additional-photo-batch.md).
Slide labels now identify each item as a photo from the day, and the section
caption describes the mixed editions without assigning every image to 2026.
Landscape images use the same centered 4:3 crop in both monochrome and original
colour reveal. Photographer credits and publication permission for supplied
archive photos remain unverified under B-007.
[Content](../../../resources/content/editions/2027.json);
[concerns](01-build-concerns.md)

## Verification

For the additional photo batch, the production Vite build succeeds. The running
homepage returns 200 and displays eleven slides with the existing group photo
first. At five widths (1440, 1024, 860, 640, and 390px), all 22 responsive and
reveal images decode; carousel frames remain 4:3 and fill the viewport without
showing a neighboring slide. Arrow, keyboard, native touch/scroll, colour
reveal, reduced-motion, no-JavaScript and overflow checks pass. Desktop and
phone captures were reviewed; there are no browser errors.
[Carousel checks](../../../scripts/homepage-carousel-check.cjs);
[motion checks](../../../scripts/homepage-motion-check.cjs);
[photo coverage](../../../tests/Feature/PhotoTreatmentTest.php)

The carousel check now compares every frame with 4:3. It also checks the final
image width against the container, that the next slide stays outside the
viewport, and that the colour reveal adds no zoom.
[Carousel checks](../../../scripts/homepage-carousel-check.cjs)

The mechanical design review reports existing literal sizes, radii and shadows
in older app/tool styles. None points to the new carousel rules, which reuse
the shared colour, type and radius tokens. Those findings are outside this
change; no detector exceptions were added. The production build retains the
existing font URL warnings; the running screenshots render the public fonts.
[Styles](../../../resources/css/app.css)
