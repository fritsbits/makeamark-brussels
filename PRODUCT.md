# Product

<!-- impeccable:product-schema 1 -->

## Platform

web

## Users

Two co-primary applicant groups, weighted equally (owner decision S-002, [Strategy](docs/ux/_strategy.md)):

- **Brussels social organisations** that bring a communication, design, or digital challenge. Historical reporting suggests they lack time or resources for design and marketing.
- **Makers**: people who volunteer their creative skills for a day. Historical reporting suggests they value socially useful work and meeting peers.

Person signup covers makers only in this version.
Young people (roughly 12 to 25) are the focus of the 2027 edition and important stakeholders, but they get no signup route and no direct invitation.
The audience needs above are historical hypotheses (BRUZZ 2023, 2020 materials), not validated current research.

## Product Purpose

Make a Mark Brussels is an annual one-day make-a-thon where volunteer makers help Brussels social organisations with communication, design, and digital challenges.
The website is an edition-cycle guide.
It helps both groups understand how to apply, submit a useful application, and know what happens next.

Success: review-ready applications received by the edition deadline, tracked separately for makers and organisations (candidate North Star).
Reviewers have not yet defined "review-ready", and no targets are set.
Attendance and impact matter to the programme but cannot be attributed to the website alone.

## Positioning

Make a Mark Brussels is the Brussels chapter of the wider Make a Mark movement ([letsmakeamark.org](https://www.letsmakeamark.org/)).
It has run since 2016: eight editions and 55 organisations so far, with the ninth edition on 27 March 2027.
Organisations and makers both take an active role, and teams are matched in advance around one concrete challenge per organisation.

## Operating Context

The site moves through three phases per edition:

1. **Signup open**: separate maker and organisation applications.
2. **Signups closed**: explains what happens to applicants.
3. **After the event**: shows results and invites future interest.

Behind a public application sit selection, team matching, a planning meeting with the team and organisation, the event day, and the file handoff.
Applying, being selected, being assigned a team, and registering future interest are distinct states.

## Capabilities and Constraints

- Laravel 13, Livewire 4, Flux Pro 2, Tailwind 4. Conventions live in [AGENTS.md](AGENTS.md).
- The owner-selected distilled homepage keeps the established identity; process
  and commitments live on audience pages. [Selection](docs/wiki/build/13-messaging-review.md).
- Content is file-based in `resources/content/`. The highest edition year drives the homepage, with status `open`, `closed`, `selected`, or `done`.
- Airtable is being retired: its data moves into Laravel, which will store new signups.
- Signup administration inside the website is out of scope for this version.
- New pages need an agreed sitemap and an approved wireframe before custom styling.
- Edition status drives the public phase. A confirmation acknowledges receipt and gives a response timeline only when the team can meet it.
- During site preparation, `preparing` keeps application actions unavailable. It is a publishing safeguard, not a fourth public edition phase. Applications remain unavailable until the field review and data-handling decisions are settled.
- Maker and organisation future interest use separate forms. The application fields will be audited before deciding which are required.
- Open decisions: mapping statuses to the three phases, the submission data flow, consent, retention, import completeness, and translations (Dutch and French are intended but not scoped).

## Brand Commitments

- Name: "Make a Mark Brussels" on first mention, "Make a Mark" after, never "MAM" in public copy.
- Every page extends the existing one-pager identity: the logo, the self-hosted fonts, the palette, and the photography (owner decision 2026-10-18).
- Voice: warm host. Invitations are playful, practical copy is plain, and the site is English first. See the [tone of voice guide](docs/wiki/strategy/10-tone-of-voice.md).
- Terminology: "social organisation" on first mention, then "organisation"; "apply" rather than "register".

## Evidence on Hand

- Event photos with credits: `public/images/story-*`, `edition-2026-group`, and `resources/content/stories.json`.
- Logo: `public/images/logo-brussels.*`. Social artwork: `public/og.png`.
- 55 past organisations: `resources/content/organisations.json`.
- Two 2023 organisation testimonials and historical maker material: [additional materials](docs/raw/2026-10-18-additional-mam-materials.md).
- No measured impact data, current audience research, or attendance numbers for 2027.
  Do not invent outcomes, response times, acceptance criteria, accessibility provisions, or consent terms.

## Product Principles

1. Serve both tracks equally: every surface respects makers and organisations as active partners.
2. Make the current phase obvious: offer only the action that exists right now.
3. Spell out the commitment before the ask: unpaid time, preparation, and selection are visible up front.
4. Ground every claim: name the real work and outputs, and promise no transformation.
5. Rooted in Brussels: concrete and local, never generic charity language.

## Accessibility & Inclusion

Target WCAG 2.2 AA (owner decision 2026-10-18).
Describe young people as people with agency, never with pity or saviour framing.
