---
title: Livegang-audit 2027
tags: [audit, launch, accessibility, operations]
sources:
  - "../../raw/2026-10-19-worktree-merge-tests.json"
  - "../../raw/2026-10-19-launch-review-final-feedback.json"
  - "../../raw/2026-10-19-launch-final-fixes-verification.json"
  - "../../raw/2026-10-19-launch-visual-review-selection.md"
  - "../../raw/2026-10-19-launch-selected-fixes-verification.json"
  - "../../raw/2026-10-19-launch-security-review.md"
  - "../../raw/2026-10-19-launch-audit-request.md"
  - "../../raw/2026-10-19-launch-audit-evidence.json"
  - "01-build-concerns.md"
  - "20-application-intake.md"
  - "31-admin-workspace.md"
  - "../../../resources/views/components/edition-date.blade.php"
  - "../../../public/og.png"
phase: Build
updated: 2026-10-19
status: five finally selected P1 findings implemented and verified; L-01 retained by owner choice; B-035 partly resolved
---

# Livegang-audit, 19 oktober 2026

**De vijf definitief gekozen P1-wijzigingen zijn uitgevoerd en gecontroleerd in de draaiende site.** De oorspronkelijke tekstkleur is behouden op verzoek van de eigenaar. De operationele releasechecks blijven open. Eerst volgen de uitvoeringsresultaten, daarna de oorspronkelijke audit. De oorspronkelijke score is een momentopname van vóór de fixes, geen WCAG-certificaat of productiegoedkeuring.

## Goedgekeurde wijzigingen

De eigenaar vroeg om zichtbare voorbeelden. De laatste Lavish-selectie kiest
L-02 tot en met L-06 en vervangt de eerdere selectie van zes punten. Daarom
is de oorspronkelijke tekstkleur voor L-01 hersteld. De vijf definitief
gekozen wijzigingen blijven toegepast.
[Definitieve feedback](../../raw/2026-10-19-launch-review-final-feedback.json);
[eerdere selectie](../../raw/2026-10-19-launch-visual-review-selection.md).

| ID | Uitkomst | Bewijs |
| --- | --- | --- |
| L-01 | Niet geselecteerd in de definitieve feedback. Oorspronkelijke Paper Cream hersteld. | De browser ziet opnieuw `#F5E2CA` op rood, 4,04:1. De oorspronkelijke contrastbevinding blijft open. |
| L-02 | Afgehandeld. De invoervelden gebruiken een dunne Ink Brown-rand via `--field-border`. | Gemeten randkleur `#6B5645`, 5,46:1 tegen de pagina; desktop en mobiel bekeken. |
| L-03 | Afgehandeld. De achtergrond wordt inert zolang het mobiele menu open is. Tab en Shift+Tab blijven bij de zichtbare menubediening; Escape herstelt focus. | Vier Tab-stappen geven For makers, For organisations, About, Menu. Linkactivatie en desktop-resize herstellen de achtergrond. |
| L-04 | Afgehandeld. Het datumcomponent heeft echte, visueel verborgen datumtekst. | De toegankelijkheidsboom bevat `Saturday 27 March 2027`; het ongeldige `aria-label` is verwijderd. |
| L-05 | Afgehandeld. Alle publieke pagina's verwijzen naar `/og-2027.png`, met dimensies en alttekst. | HTTP 200; de bytes zijn identiek aan het goedgekeurde 1200×630-voorstel. De afbeelding heeft een nieuwe URL. |
| L-06 | Afgehandeld. Jongerenwerk in Brussel is verplicht voor deze editie. De intro noemt ongeveer 12 tot 25 jaar; het formulier heeft één verplichte bevestiging. | Servervalidatie vereist `youth_brussels_eligibility`. De oude key volstaat niet. Nieuwe en oude antwoorden hebben aparte adminlabels; oude records zijn niet gewijzigd. |

Bron: [definitief uitvoeringsbewijs](../../raw/2026-10-19-launch-final-fixes-verification.json).
Het [eerdere uitvoeringsbewijs](../../raw/2026-10-19-launch-selected-fixes-verification.json)
bewaart de controle vóór de laatste selectiewijziging.

De productiebuild slaagt. Bij de integratie naar `main` zijn de vier verouderde
copy- en markupverwachtingen bijgewerkt naar de goedgekeurde wijzigingen.
Alle 115 tests en 887 assertions slagen nu, inclusief de vier nieuwe
regressietests voor de jongerenvoorwaarde en historische antwoorden. L-07 is
opgelost. De gerichte beveiligingsreview vond geen bypass of nieuwe
datablootstelling. [Integratie en testbewijs](45-worktree-integration.md).

De browsercontrole omvat vier routes op 1440, 390 en 320px, zonder overflow of
browserfouten. De gerichte Axe-controle vindt het eerdere datumprobleem niet
meer. De oorspronkelijke contrastbevindingen voor L-01 en de footermerknaam
blijven in de ruwe contrastlijst staan; er zijn geen andere fouten gevonden.
Via de browser zijn uitsluitend ongeldige
lege aanvragen verstuurd. Er zijn geen echte e-mails verzonden.

De 18 bestaande detectornotities uit de oorspronkelijke audit zijn ongewijzigd;
de productwijzigingen voegen geen nieuwe meldingen toe. Voor de afzonderlijke
reviewpagina zijn alleen de compacte rapportschaal en actieve tabmarkering
uitgezonderd. De tijdelijke fontaliassen zijn door de echte projectfontnamen
vervangen. Die uitzonderingen gelden niet voor de website.

R-01 tot en met R-05 en de niet-geselecteerde L-01 en L-07 tot en met L-10 zijn niet
afgerond. B-035 blijft daarom deels open. De audit hieronder bewaart de
oorspronkelijke observaties en metingen.

## Implementatie-integriteit en vormgeving

Pass: de rode en warme cream-palette, Big Shoulders-displaykoppen, eigen Brussels-woordmerk en echte evenementfoto’s vormen een herkenbaar geheel. Geen generieke kaartjesfabriek, decoratieve dashboardgrafieken, glassmorphism of standaard AI-gradienten aangetroffen. Screenshots zijn bekeken op desktop en mobiel. [Browserbewijs](../../raw/2026-10-19-launch-audit-evidence.json); [ontwerpregels](../../../DESIGN.md).

## Audit Health Score van de eerste review

| Dimensie | Score | Belangrijkste observatie |
| --- | ---: | --- |
| Toegankelijkheid | 2/4 | Contrast, menufocus en onleesbare datum voor schermlezers |
| Prestaties | 3/4 | Compacte app-JS en responsive WebP; echte mobiele productiemeting ontbreekt |
| Responsive | 3/4 | Geen overflow op vijf breedtes; fallback voor mobiele navigatie ontbreekt |
| Thema en tokens | 3/4 | Samenhangende vaste lichte identiteit; enkele token- en documentatieafwijkingen |
| Implementatie-integriteit | 3/4 | Eigen visuele identiteit; verouderde deelafbeelding en testcopy |
| **Totaal** | **14/20** | **Goed fundament; gerichte fixes nodig voor vrijgave.** |

**10 concrete bevindingen: 0 P0, 6 P1, 4 P2, 0 P3.** Daarnaast staan 6 releasechecks of beslispunten hieronder. Productie-onbekenden zijn niet als bewezen productiefouten geteld. [Alle meetresultaten](../../raw/2026-10-19-launch-audit-evidence.json).

## Bevindingen

### L-01 · P1 · Kleine tekst op rood heeft te weinig contrast

- **Locatie / categorie:** resources/css/makers.css:17,32 tot 33; resources/css/public-typography.css; datum en editieblok. Toegankelijkheid.
- **Bewijs:** Axe meet 4,04:1 voor Paper Cream op Marker Red. Maand/jaar zijn 18px; de inleidingen en editiecopy zijn op mobiel 20px en licht van gewicht. Daar geldt 4,5:1. Op desktop zijn de grotere leads wel groot genoeg voor de lagere drempel.
- **Impact:** Bezoekers met verminderd contrastzicht lezen juist de datum en praktische uitleg moeilijker.
- **Actie:** Gebruik voor deze kleine teksten Paper Cream Light: de bestaande combinatie haalt 4,78:1. Bewaar het huidige cream voor grote displaytekst.
- **Norm:** [WCAG 1.4.3](https://www.w3.org/WAI/WCAG22/Understanding/contrast-minimum.html).
- **Passende vervolgstap:** `$impeccable harden`.

### L-02 · P1 · Formuliervelden hebben onvoldoende zichtbare grenzen

- **Locatie / categorie:** resources/css/makers.css:58 tot 61; maker- en organisatieformulier. Toegankelijkheid.
- **Bewijs:** Gemeten veldrand #DFC3A3 tegen pagina #F5E2CA: 1,33:1. Rand tegen veldvulling #FFF6EA: 1,57:1. Vulling tegen pagina: 1,18:1. Rand en vulling bieden dus geen 3:1 herkenningsgrens.
- **Impact:** Voor bezoekers met verminderd zicht is moeilijk te zien waar een leeg invoerveld begint en eindigt.
- **Actie:** Definieer een donkerder gedeeld token voor interactieve veldranden, met minstens 3:1 tegen de aangrenzende oppervlakte. Decoratieve scheidingslijnen mogen subtiel blijven.
- **Norm:** [WCAG 1.4.11](https://www.w3.org/WAI/WCAG22/Understanding/non-text-contrast.html).
- **Passende vervolgstap:** `$impeccable harden`.

### L-03 · P1 · Toetsenbordfocus verdwijnt achter het geopende menu

- **Locatie / categorie:** resources/views/sections/nav.blade.php:1 tot 11; resources/css/app.css:381 tot 390. Toegankelijkheid / mobiel.
- **Bewijs:** Op 390px gaat Tab na de drie menulinks naar Apply now en de fotoknoppen in de afgedekte main. De achtergrondscroll is vergrendeld, maar de achtergrond blijft focusbaar. Escape sluit wel correct.
- **Impact:** Een toetsenbordgebruiker bedient onzichtbare onderdelen en verliest zijn plaats.
- **Actie:** Maak main/footer inert zolang de overlay open is; houd de focus bij de zichtbare navigatie en herstel focus op de menuknop bij sluiten.
- **Norm:** [WCAG 2.4.11](https://www.w3.org/WAI/WCAG22/Understanding/focus-not-obscured-minimum.html).
- **Passende vervolgstap:** `$impeccable harden`.

### L-04 · P1 · De evenementdatum ontbreekt in de toegankelijkheidsboom

- **Locatie / categorie:** resources/views/components/edition-date.blade.php:5 tot 10. Toegankelijkheid.
- **Bewijs:** Alle datumtekst heeft aria-hidden=true. Het bovenliggende time gebruikt een daar niet toegelaten aria-label. Axe meldt aria-prohibited-attr; Chromium geeft als toegankelijke snapshot uitsluitend "- time", zonder datum.
- **Impact:** Een schermlezer krijgt op de drie belangrijkste pagina’s niet de informatie die ziende bezoekers in de datumstempel zien.
- **Actie:** Geef time echte leesbare tekst, bijvoorbeeld een visueel verborgen volledige datum naast de decoratieve stempel. Verwijder het niet-ondersteunde label en controleer de toegankelijkheidsboom opnieuw.
- **Norm:** Semantiek / tekstalternatief.
- **Passende vervolgstap:** `$impeccable harden`.

### L-05 · P1 · De deelafbeelding adverteert nog de editie van 2026

- **Locatie / categorie:** public/og.png; resources/views/components/layouts/app.blade.php:21. Inhoud / delen.
- **Bewijs:** De huidige afbeelding vermeldt "Eighth edition", "Make a Mark for the Planet" en "20 and 21 March 2026". Alle publieke pagina’s delen deze afbeelding.
- **Impact:** Een gedeelde link in chat of sociale media toont een verkeerde datum en een verkeerd thema.
- **Actie:** Maak een 2027-versie met de juiste editie, jongerenfocus en 27 maart 2027; controleer de uiteindelijke preview en vernieuw zo nodig de cache.
- **Norm:** Geen WCAG-criterium.
- **Passende vervolgstap:** `$impeccable clarify`.

### L-06 · P1 · Organisaties missen de jongerenfocus als ze direct binnenkomen

- **Locatie / categorie:** resources/views/audience.blade.php:19; resources/views/applications/organisation.blade.php:58; resources/content/editions/2027.json. Inhoud / aanvraag.
- **Bewijs:** De homepage noemt organisaties die jongeren in Brussel ondersteunen. De organisatiepagina en geschiktheidsvraag noemen alleen een project en mensen in Brussel. De bronbrief noemt jongeren van ongeveer 12 tot 25 jaar.
- **Impact:** Een bezoeker van een directe organisatielink kan een aanvraag doen zonder de specifieke focus van deze editie te kennen.
- **Actie:** Leg de jongerenfocus vóór het formulier uit en bepaal expliciet of werken met jongeren een harde toelatingsvoorwaarde is. Voeg die voorwaarde niet stilzwijgend toe aan validatie.
- **Norm:** Geen WCAG-criterium; huidige editiebrief.
- **Passende vervolgstap:** `$impeccable clarify`.

### L-07 · P2 · Vier tests verwachten nog vroegere, goedgekeurd gewijzigde copy

**Opgelost bij de worktree-integratie:** de verwachte koppen, footerlink,
commitmentcopy en homepageactie volgen nu de vastgelegde keuzes. De volledige
suite slaagt met 115 tests en 887 assertions.
[Integratierecord en uitvoer](45-worktree-integration.md).

- **Locatie / categorie:** tests/Feature/EditionWebsiteTest.php:17,33; tests/Feature/MessagingPreviewTest.php:19,38. Kwaliteitsborging.
- **Bewijs:** 111 tests uitgevoerd: 107 geslaagd, 4 gefaald. De eerste fouten verwachten vroegere doelgroepkoppen, makertitel, footerlink en verwijderde betalings-FAQ. Sommige latere assertions in dezelfde tests zijn eveneens verouderd.
- **Impact:** De suite geeft geen groen releasesignaal; nieuwe regressies zijn minder makkelijk te onderscheiden van bekende ruis.
- **Actie:** Werk de betrokken copyverwachtingen en de overige assertions in die testketens bij naar de vastgelegde keuzes. Laat de volledige suite daarna één keer groen worden.
- **Norm:** Releasecontrole.
- **Passende vervolgstap:** `$impeccable harden`.

### L-08 · P2 · Mislukte ontvangstmail heeft geen herstelpad

- **Locatie / categorie:** app/Http/Controllers/ApplicationSubmissionController.php:106 tot 112. Betrouwbaarheid.
- **Bewijs:** Een mail wordt alleen bij een nieuw record synchroon geprobeerd. Bij een fout wordt gelogd; een herhaalde aanvraag vindt het bestaande record en probeert de mail niet opnieuw.
- **Impact:** Een tijdelijke mailstoring kan de ontvangstmail voor die aanvraag permanent verliezen. De aanvraag zelf blijft wel bewaard en de bevestigingspagina is eerlijk over niet-verzonden mail.
- **Actie:** Voorzie een idempotente queuejob met retries of een expliciete beheeractie om opnieuw te verzenden, met zicht op mislukkingen.
- **Norm:** Betrouwbaarheid.
- **Passende vervolgstap:** `$impeccable harden`.

### L-09 · P2 · Mobiele hoofdnavigatie werkt niet zonder JavaScript

- **Locatie / categorie:** resources/css/app.css:386 tot 389; resources/views/sections/nav.blade.php. Robuustheid / mobiel.
- **Bewijs:** Met JavaScript uit staat #nav-links op alle drie geteste mobiele routes verborgen. De menuknop kan de benodigde Alpine-status niet veranderen. De aanvraagformulieren en native checkboxfallbacks blijven wel aanwezig.
- **Impact:** Bij geblokkeerd of mislukt JavaScript vallen de hoofdnavigatielinks weg, hoewel inhoud en footer nog bereikbaar zijn.
- **Actie:** Maak navigatie standaard beschikbaar en verberg haar pas wanneer de menuverbetering actief is, of voeg een compacte noscript-navigatie toe.
- **Norm:** Progressive enhancement.
- **Passende vervolgstap:** `$impeccable harden`.

### L-10 · P2 · Twee optionele vaardigheidsvragen overlappen

- **Locatie / categorie:** resources/views/applications/maker.blade.php:76 tot 77; B-001. Formuliercopy.
- **Bewijs:** Het formulier vraagt zowel "Other skills you could bring" als "Other skills". Interpretatie: het verschil is voor een aanvrager niet duidelijk. De huidige verplichtheid van velden wacht volgens B-001 nog op een reviewercontrole.
- **Impact:** Aanvragers moeten uitzoeken of ze zichzelf moeten herhalen; reviewers krijgen mogelijk dubbel ingevulde antwoorden.
- **Actie:** Doe één gerichte veldenreview: verduidelijk het onderscheid of combineer deze vragen. Bevestig ook welke portfolio-, telefoon- en beschikbaarheidsvragen echt verplicht moeten zijn.
- **Norm:** Geen WCAG-overtreding vastgesteld.
- **Passende vervolgstap:** `$impeccable clarify`.

## Operationele releasechecks en beslissingen

Deze punten zijn deels al bestaande open zorgen en deels nog onbevestigde productie-instellingen. Mail versturen, gebruikers provisioneren en deployen zijn niet uitgevoerd in deze review.

### R-01 · Werkende productie-e-mail

**Nog niet op productie geverifieerd.** Configureer Resend, een geverifieerde afzender en de juiste mailbox. Verifieer ontvangstmail én wachtwoordherstel met een afgesproken proef op productie. In deze lokale workspace staat mail op log; dat bewijst niets over de productieserver. Tijdens deze audit zijn geen echte mails verstuurd.

Bron: B-002; ApplicationSubmissionController.php:106; docs/wiki/build/20-application-intake.md:127.

### R-02 · Echte beheerders en opvolging van aanvragen

**Operationele voorbereiding staat open.** Voorzie de vier persoonlijke accounts en spreek af wie nieuwe aanvragen opvolgt. De afgesproken interne alerts zijn nog niet gebouwd; een expliciete dagelijkse admincontrole is een mogelijke tijdelijke werkwijze. Gebruik een schone productiedatabase of een bewuste import van echte records, zonder de lokale previewlogin en demoaanvragen.

Bron: B-009 / B-010; docs/wiki/build/31-admin-workspace.md:145,224.

### R-03 · Fotorechten en credits bevestigen

**Bevestiging ontbreekt in het dossier.** Bevestig publicatietoestemming en exacte naamsvermelding voor de geselecteerde archieffoto’s. Dit is een open bewijsstuk, geen vaststelling dat toestemming ontbreekt.

Bron: B-007; docs/wiki/build/01-build-concerns.md:131.

### R-04 · Productie-installatie en herstel verifiëren

**Productieserver niet onderzocht.** Controleer APP_ENV=production, debug uit, eigen sleutel en productie-URL, HTTPS en redirects, migraties, CI met Flux-authenticatie, back-ups en een werkende herstelproef. Verifieer anti-framing, nosniff en overige beveiligingsheaders op de echte host. De lokale ontbrekende headers zijn geen bewijs van een productieprobleem.

Bron: README.md; bootstrap/app.php; lokale configuratie- en headercontrole.

### R-05 · De beloofde bewaartermijn uitvoerbaar maken

**Verantwoordelijkheid nog vast te leggen.** Wijs iemand aan voor de jaarlijkse review en leg verwijdering of anonimisering binnen drie jaar vast, inclusief omgang met back-ups. Een uitvoerbare handmatige procedure kan volstaan; automatisering is geen voorwaarde voor deze audit.

Bron: resources/views/privacy.blade.php:20,42; B-002.

### R-06 · Praktische keuzes en latere fases plannen

**Deels na livegang mogelijk.** Bevestig de veldenreview en editiegeschiktheid. De locatie mag eerlijk "to be confirmed" blijven; publiceer een sluitings- of antwoorddatum zodra het team die kan waarmaken. Rond de Airtable-migratie af vóór het oude systeem verdwijnt en bouw de gesloten/na-eventfase vóór die nodig wordt.

Bron: B-001, B-003, B-004; docs/ux/_scope.md; docs/ux/_structure.md.

## Wat al goed zit

- De vier publieke pagina’s geven HTTP 200 op 1440, 1024, 768, 390 en 320px: 20 pagina/breedte-combinaties, zonder horizontale overflow, JavaScriptfouten of mislukte assetrequests in de meetronde.
- Canonicals, beschrijvingen, sitemap en robots-bestand zijn aanwezig. Zeven van acht uitgaande homepage-URL’s geven HTTP 200 op HEAD; LinkedIn geeft 405 en is daardoor niet als kapot aangemerkt.
- De app gebruikt responsive WebP, lazy loading voor de galerij, zelfgehoste fonts en een rustige reduced-motionvariant. De productiebuild slaagt: app-JS 3,70 kB gzip, CSS 59,85 kB gzip, exclusief afzonderlijk geladen Livewire/Flux. De lokale snelheid is geen bewijs van Core Web Vitals op mobiel internet.
- Beide aanvraagformulieren hebben servervalidatie en een gefocuste foutensamenvatting. De audit verstuurde uitsluitend ongeldige lege aanvragen; er zijn geen echte nieuwe aanmeldingen of e-mails aangemaakt. Zonder JavaScript blijven de formulieren en native checkboxfallbacks aanwezig.
- Autorisatie, CSRF, throttling, ondertekende formuliernonces en atomische replaybescherming zijn aanwezig. De code-inspectie vond geen bevestigde kritieke of hoog exploiteerbare kwetsbaarheid. Composer audit voor de lockfile en npm audit voor productiedependencies melden geen bekende kwetsbaarheden. Dit is geen penetratietest.
- De contrast- en ARIA-bevindingen blijven gelijk bij een donkere systeemvoorkeur; er is geen extra kapot donker thema aangetroffen. Verminderde beweging geeft fotoschaal 1 en geen navtransitie.

Bron: [auditbewijs](../../raw/2026-10-19-launch-audit-evidence.json), [intake](20-application-intake.md), [adminwerkruimte](31-admin-workspace.md).

## Patroon en detectortriage

De voornaamste patronen zitten in gedeelde componenten: dezelfde cream-op-roodkeuze, dezelfde veldrand, hetzelfde datumcomponent en hetzelfde menu. Eén gerichte fix per component bereikt meerdere pagina’s. De tests zijn achtergelopen op goedgekeurde copywijzigingen. Operationele afspraken over mail en opvolging zijn nog niet op dezelfde hoogte als de interface.

De Impeccable-detector meldt 18 punten: 16 advisories over niet exact gedocumenteerde maten/radii/kleuren en 2 waarschuwingen over het Arial-fallbackfont. Die fallback is doelbewust; fotoclip-schaduwen zijn in DESIGN.md beschreven. Deze waarschuwingen zijn daarom geen 18 extra gebruikersproblemen. De overige maat- en radiusafwijkingen zijn documentatie-/tokenhygiëne, geen bewezen livegangblokkade. De buildwaarschuwingen over font-URL’s betekenen eveneens niet dat de fonts ontbreken: de browserronde had geen falende fontrequests. De footermerknaam uit de Axe-contrastlijst is niet als extra normale tekstfout geteld; merknamen vallen onder de [logo-uitzondering](https://www.w3.org/WAI/WCAG22/Understanding/contrast-minimum.html).

## Aanpak in volgorde

1. `$impeccable harden`: L-01 tot en met L-04; daarna L-07 tot en met L-09. De mailherstelcode is backendwerk buiten visuele styling.
2. `$impeccable clarify`: L-05, L-06 en L-10. Het deelbeeld zelf vraagt ook een passende grafische export; deze commandnaam vervangt die assettaak niet.
3. Sluit R-01 tot en met R-05 vóór echte aanvragen; plan de latere-fasetaken uit R-06 op tijd. Bewaar foto- en productiebevestigingen als bewijs.
4. Doe één volledige groene test/buildronde en een afgesproken productieproef voor beide aanvraagroutes, ontvangstmail, beheertoegang en wachtwoordherstel.
5. `$impeccable polish`: laatste visuele afronding na de functionele fixes. Herhaal daarna `$impeccable audit` voor de resterende releasecriteria.

B-035 sluit pas wanneer de P1-bevindingen verholpen zijn en de productiechecks aantoonbaar zijn afgerond. Historische import en de gesloten/na-eventfase hebben een eigen beslismoment onder B-003/B-004.
