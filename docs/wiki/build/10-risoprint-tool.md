---
title: Internal risoprint photo tool
tags: [build, photography, tooling]
sources:
  - "../../raw/2026-10-18-risoprint-tool-request.md"
  - "../../../.bloom/attachments/WC2nsN/Pasted 2026-10-18 at 15.46.20.png"
  - "../../../.bloom/attachments/oCPVLu/Pasted 2026-10-18 at 15.46.37.png"
  - "../../../resources/js/riso-engine.js"
  - "../../../resources/js/riso.js"
  - "../../../routes/web.php"
phase: Build
updated: 2026-10-18
---

# Internal risoprint photo tool

The [surface brief](../../ux/riso-studio.md) records the tool's task hierarchy
and local design decisions.

## Request and interpretation

The owner requested a small photo treatment configurator and supplied a
reference and a source photo. [Request](../../raw/2026-10-18-risoprint-tool-request.md)

Visual interpretation: the reference combines red midtones, blue shadows,
speckled ink, slightly shifted ink plates, and cream paper. The implementation
approximates that treatment with deterministic raster grain and two ink
separations. It does not reproduce the reference's tape or drawn marks.
[Reference](../../../.bloom/attachments/WC2nsN/Pasted%202026-10-18%20at%2015.46.20.png)

Implementation assumption: this is an internal creative tool, separate from
the approved public website sitemap. No owner approval of a new public page
or a site-wide photo treatment is implied. B-005 records the implementation
boundary. [Build concerns](01-build-concerns.md)

## Behavior

`/tools/riso` loads the supplied 440 × 330 photo, with colour pickers for the
two inks and paper, controls for each ink's strength, contrast, exposure,
shadow coverage, grain amount/size, plate offset, paper texture and border.
Reference, soft-print and red-only presets offer starting points. The original
comparison preserves the treatment settings.
[View](../../../resources/views/tools/riso.blade.php);
[renderer](../../../resources/js/riso-engine.js)

Photos selected from the computer stay in the browser. The tool exports a
PNG and versioned JSON settings, and can load those settings for another
photo. Photo long-edge export choices are 1600, 2400 and 3200 pixels; the
paper border adds to those dimensions. Upscaling does not restore detail
missing from the source. Settings use bounded values and allowlisted keys.
[Browser component](../../../resources/js/riso.js)

Both tool and sample routes require the local/testing environment at
registration and request time. The tool sends `noindex, nofollow` and is absent
from public navigation and the XML sitemap. No dependency, server upload,
stored photo, application workflow, or public photo treatment was added.
[Routes](../../../routes/web.php)

## Verification

Verified on 2026-10-18 at
`https://makeamark-southern-ocean.test/tools/riso`:

- `php artisan test --compact`: 14 tests, 47 assertions pass, including runtime
  production denial for both internal routes.
- `node --test tests/js/riso.test.mjs`: four checks pass for paper colour,
  printed shadows, deterministic grain, ink removal and settings bounds.
- `npm run build`: passes with the existing runtime font-reference warnings.
- `NODE_PATH="$(npm root -g)" node scripts/riso-check.cjs`: passes at 1440px
  and 390px. Checks cover comparison, grain controls, reset, settings round
  trip, PNG dimensions, photo upload, malformed JSON, compact export select,
  no horizontal overflow and no browser/network errors.
- Desktop/mobile screenshots and exported PNG were inspected in
  `/tmp/makeamark-riso/`. A stretched export select caused by the shared
  `.block` class was corrected with a scoped component rule.

The treatment remains an editable approximation. A subsequent owner request
selected a saved preset for the homepage group photo only; its reusable site
integration is recorded in [selected photo treatments](11-site-photo-treatments.md).
No physical print separations or colour-profile accuracy are promised.
