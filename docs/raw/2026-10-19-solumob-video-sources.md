# Solumob video and application source capture

Received from the owner on 2026-10-19 for detailed case-study research.

## Owner request

> Hé, hier is een video die ik gemaakt heb voor de organisatie SoluMob Volontair. Het is een video/vlog over de dag bij Make a Mark, waar ik deel uitmaakte van het team. We gaan nu casestudy’s maken van organisaties, waaronder dus ook een casestudy van SoluMob.
>
> Ik voeg als context ook de initiële applicatie van SoluMob toe, zodat je weet waar het over ging.
>
> Waar ik nu naar op zoek ben, en wat mijn opdracht voor jou is, is om zoveel mogelijk informatie uit die video te halen die nuttig kan zijn voor een casestudy. Je ziet bijvoorbeeld heel goed wat we allemaal hebben opgeleverd en gemaakt tijdens die dag. Dat komt in beeld. Ik vertel erover in het Nederlands, dus als je een transcript maakt, kun je die informatie daar ook uit halen. Ik vertel dat allemaal, en het komt vooral ook in beeld: op heel wat schermen zie je wat we allemaal hebben gemaakt en ook hoe de rush van zo’n dag is, enzovoort. Analyseer die video uitgebreid.
>
> Ik heb ook het transcript in de bijlage meegegeven.

## Supplied originals

- Video: `.bloom/attachments/KbiE5E/8 hrs to help local non-profits with design.mp4`.
- Transcript: `.bloom/attachments/WyjMy3/8 hrs to help local non-profits with design.txt`.
- Application: `.bloom/attachments/ehULvZ/2020 Make a Mark Brussels social profit Application (Responses) - Copy of Form Responses 1.csv`.

The [manifest](assets/solumob-2019/manifest.json) records original paths,
SHA-256 hashes, file sizes, video metadata and exact still extraction times.
The original video and CSV remain in the supplied attachment locations.

## Preserved captures

- [Original supplied transcript](assets/solumob-2019/supplied-transcript.txt), copied byte for byte.
- [Selected application fields](assets/solumob-2019/application-selected-fields.json), exact field values from CSV record 2, counting the header as record 1. Personal contact fields are omitted. Embedded line breaks mean record numbers are different from physical text line numbers.
- Twenty-three uncropped 1280 × 720 JPEG frames, listed in the manifest. Times are elapsed video time, not time of day.

## Inspection method

The video is 189.289 seconds long, 1280 × 720, 30 fps, according to ffprobe.
Visual inspection used 95 overview frames sampled every two seconds, then
full-resolution frames at the relevant boards, designs, captions and
presentation. Additional half-second samples cover 01:39 to 02:12 and 02:35
to 02:44. Working contact sheets remain in `.bloom/solumob-analysis/`.

This is sampled visual inspection, not a claim to have watched every frame
in real time. Small or blurred screen text is left unresolved. The supplied
transcript has no timecodes and is preserved with its original recognition
errors. Synthesis and interpretation belong in the
[case-study research dossier](../wiki/build/44-solumob-video-analysis.md).
