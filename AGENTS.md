# Cascade

Before substantive product work, read `docs/wiki/index.md`, the active phase's
`00` plan and `01` concerns register, then the relevant evidence pages. Treat
`docs/wiki/` as the maintained product synthesis and `docs/raw/` as immutable
source material. Cite every factual claim to a source; mark interpretations
and unknowns explicitly.

## Working rules

- Follow the Cascade method in `docs/wiki/README.md`.
- Work top-down through the active phase. Do not decide later-phase structure
  before the preceding phase gate is closed.
- Keep page frontmatter, `index.md`, the active concerns register, and
  `log.md` current whenever the synthesis changes.
- Give each concern a stable ID. Closed or partly closed concerns need a
  resolution and a pointer to the decision page.
- Update documentation in the same change as related product or code changes.
- Do not mark implementation complete until it is verified in the running
  product.

## Laravel conventions

Source: [New Laravel project setup playbook](https://app.notion.com/p/334d3ecc475c81719bdcc15d82bd5b23).

- Use Laravel, Tailwind, Livewire, Flux Pro, and Livewire's bundled Alpine.
  No starter kit or additional packages without a product requirement.
- Flux first for standard UI. Use `<flux:heading level="n">` for headings,
  `<flux:text>` for copy, and Flux buttons, inputs, selects, modals, and badges.
  Authored Markdown uses semantic HTML and the global typography defaults.
- Define app-specific patterns once in `resources/css/app.css` under
  `@layer components`. Reuse named classes. Use `@apply` where useful.
  Cosmetic utilities belong in these definitions, not in Blade markup.
  Layout utilities are allowed inline.
- New pages require an agreed sitemap saved in Notion. Build content-first
  wireframes with Flux defaults; get wireframe approval before custom styling.
  The migrated Astro one-pager preserves its existing structure and design.
- Use Alpine for local UI state and Livewire for server data and actions.
  Do not duplicate state or install Alpine separately.
- If maps are needed, use Leaflet with OpenStreetMap tiles.
- Keep Flux credentials in Composer authentication outside version control.
- `CLAUDE.md` includes this file so both agents use the same conventions.
