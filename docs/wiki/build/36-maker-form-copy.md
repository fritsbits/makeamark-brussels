---
title: Maker application copy and hierarchy
tags: [copy, makers, application, build]
sources:
  - "../../raw/2026-10-19-maker-form-copy-request.md"
  - "../../../resources/views/audience.blade.php"
  - "../../../resources/views/applications/context.blade.php"
  - "../../../resources/views/applications/privacy-note.blade.php"
  - "../../../resources/views/applications/maker.blade.php"
  - "../../../resources/css/public-typography.css"
phase: Build
updated: 2026-10-19
status: implemented and verified in the running product
---

# Maker application copy and hierarchy

The owner asked to remove the maker payment FAQ and state the unpaid
commitment more quietly with “pro-bono”. The owner also requested a larger
application lead, shorter privacy and language guidance, and removal of the
required-fields footer note. [Request](../../raw/2026-10-19-maker-form-copy-request.md)

## Decision and implementation

The makers FAQ is omitted; the organisation payment FAQ remains. The audience
commitment now says makers contribute a full day pro-bono and join a planning
meeting. The maker application introduction uses the shared larger lead size,
while the organisation introduction retains its existing role. Privacy copy
now reads: “We keep application records for up to three years after your latest
application. Privacy notice.” Language
guidance reads: “Choose an optional level from 1 (beginner) to 5 (fluent).”
The maker form footer no longer repeats that required fields are marked.

Required fields remain marked on their labels. The organisation form footer
and payment FAQ are unchanged. [Templates](../../../resources/views/audience.blade.php);
[maker form](../../../resources/views/applications/maker.blade.php);
[shared context and privacy copy](../../../resources/views/applications/context.blade.php).

## Verification

Running-page review confirms the maker application introduction is larger and
uses the existing Ink Brown lead treatment. The maker payment FAQ and footer
note are absent; “pro-bono” appears in the commitment, and the privacy and
language copy are shortened. The organisation FAQ and its footer guidance
remain in place.
