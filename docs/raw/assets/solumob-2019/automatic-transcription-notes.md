# Automatic transcription capture

Captured locally on 2026-10-19 from the original video recorded in
[manifest.json](manifest.json). Audio and video were not uploaded.

Engine: mlx-whisper 0.4.3. Model: mlx-community/whisper-small-mlx.
The model was downloaded from Hugging Face. See
[run metadata](transcription_small_metadata.json).

## Preserved outputs

- [Raw full-video recognition](transcription_small_raw.json), with segment and word times.
- [Timestamped speech-window selection](transcription_small_timestamped.txt), retaining recognition errors and uncertainty warnings. Music-only stretches are excluded from this selection; the raw output remains unchanged.
- [French-window recognition](transcription_small_french_raw.json). Times in this clip must be interpreted with its recorded source offset.
- [Closing-window recognition](transcription_small_closing_raw.json). The recorded source clip starts at 171 seconds; clip-relative times need that offset.

These outputs were not manually checked by listening to the audio.
French speech, proper names, speech over music and the first sentence of
the closing reflection remain poorly recognized. The model also generates
unreliable text during music. These are working aids for finding passages,
not a corrected or publication-ready transcript. The supplied transcript
is independently preserved as [supplied-transcript.txt](supplied-transcript.txt).
