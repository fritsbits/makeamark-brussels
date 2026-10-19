---
title: Risoprint studio surface brief
tags: [ux, surface, internal-tool]
sources:
  - "../../PRODUCT.md"
  - "../../DESIGN.md"
  - "../../resources/views/tools/riso.blade.php"
  - "../../resources/js/riso.js"
  - "../../resources/js/riso-engine.js"
  - "../../resources/css/app.css"
  - "../../.bloom/attachments/WC2nsN/Pasted 2026-10-18 at 15.46.20.png"
  - "../../.bloom/attachments/oCPVLu/Pasted 2026-10-18 at 15.46.37.png"
phase: Build
updated: 2026-10-18
status: implementation record; visual decisions are implementer assumptions
---

# Risoprint studio

## Scope and mode

**Operate mode.** Work on one photo, inspect the result, adjust the treatment,
and download an image or reusable settings. The implemented `/tools/riso` surface
is an internal local/testing tool outside the public sitemap. It does not
extend the applicant journeys described in [PRODUCT.md](../../PRODUCT.md).
This scope comes from the current task; the hierarchy and visual choices below
are implementer assumptions from that request. No user visual approval is
recorded here.

## Task and control hierarchy

The implemented order is photo selection, a dominant live preview, treatment
controls, then export. The header provides **Change photo**. The preview pairs
**Risoprint / Original** comparison with filename, dimensions, local-processing
copy, loading state, errors, and status feedback. These are observations of
the [view](../../resources/views/tools/riso.blade.php).

The treatment rail groups controls by task:

1. **Reference, Soft print, Red only**, with Reset returning to Reference.
2. **Inks & paper:** midtone, shadow, and paper colours, plus ink opacity.
3. **Make your mark:** contrast, exposure, shadow coverage, ink grain, grain
   size, misalignment, paper texture, and border.
4. **Download PNG**, followed by the secondary Save treatment and Load
   treatment actions. [View](../../resources/views/tools/riso.blade.php),
   [interaction code](../../resources/js/riso.js).

At desktop width, the preview occupies the flexible column beside a compact
control rail and stays visible while scrolling. Below 850px the preview and
controls stack, with controls following the image. This prioritises evaluating
the output while operating the tool. The layout is an implementer choice,
observed in the [local styles](../../resources/css/app.css).

## Visual authority and output reference

The interface inherits the existing Brussels Broadsheet world: cream paper,
red and ink chrome, Big Shoulders Display headings, Plus Jakarta Sans copy,
hairline rules, and pill actions. [DESIGN.md](../../DESIGN.md) remains the
global authority, supported by the inherited identity commitment in
[PRODUCT.md](../../PRODUCT.md). This brief introduces no global token changes.

The supplied [treated image](../../.bloom/attachments/WC2nsN/Pasted%202026-10-18%20at%2015.46.20.png)
is the output reference; the supplied
[source photo](../../.bloom/attachments/oCPVLu/Pasted%202026-10-18%20at%2015.46.37.png)
is its photographic input. They guide the treatment, not a replacement visual
identity. The implemented Reference preset uses red midtones, blue shadows,
warm paper, grain, texture, and ink misalignment. The engine separates ink
coverage from luminance and reduces overlapping red beneath shadow ink.
These are implementation observations, not a claim that the reference has
been visually matched or approved. [Engine](../../resources/js/riso-engine.js).

The compact tool scale is deliberate: labels and control text are 12px,
introductory copy is 14px (13px below 550px), the wordmark is 28px (23px below
550px), and the treatment section title is 30px. Colour swatches use 6px corners
and the select uses 8px corners; actions remain pills. These local choices
support a dense operating surface without redefining global prose or heading
tokens. The photo canvas alone uses the existing clipping shadow,
`0 10px 24px rgba(60, 30, 20, .18)`, matching DESIGN's photograph-only depth
prose. [Local styles](../../resources/css/app.css),
[depth and shape rules](../../DESIGN.md#elevation--depth).

## Export and persistence

PNG export offers a photo long edge of 1600, 2400, or 3200px, defaulting to
2400px. The paper border adds pixels outside that photo size. Export always
renders the treatment, including while Original is selected for comparison;
changing dimensions does not recover source detail. Save treatment downloads
versioned JSON settings; Load treatment validates and normalises them. There
is no saved photo library in this surface. Uploaded images are decoded and
processed in the browser, using object URLs rather than an upload request.
[Interaction code](../../resources/js/riso.js),
[settings normalisation](../../resources/js/riso-engine.js).

Desktop/mobile screenshots, the exported image and the running interactions
were checked in the parent build session. The final visual review found no
material defects. See the [verification record](../wiki/build/10-risoprint-tool.md#verification).
These checks do not establish full accessibility conformance.
