# Astro source capture, 2026-10-18

The owner identified the existing site as the Astro one-pager in `/site`
during the Laravel conversion request on 2026-10-18.

The source checkout is `/Users/frederikvincx/Documents/makeamark/site`,
commit `74b55fa9c11bed88b4addc0c0005fc02aad65827`. It had a clean working
tree when inspected. The active Bloom worktree did not contain this nested,
separately versioned repository. The parent repository ignores `/site/`.

Source inventory:

- `src/pages/index.astro`: one homepage with navigation, hero, stories,
  edition, maker information, organisations, FAQ, About, and footer.
- `src/content/data/site.json`: Make a Mark Brussels copy, navigation,
  contact and social links, production domain `https://letsmakeamark.brussels`.
- `src/content/editions/2026.md`: authored 2026 edition with status `done`.
- `src/content/data/organisations.json`: 55 organisation records.
- `src/content/data/stories.json`: nine stories in authored order.
- `src/content/faq/*.md`: four ordered FAQ entries.
- `src/lib/edition-status.ts`: open with URL links to applications;
  closed/selected are text; done links to Instagram. Missing open URL
  falls back to closed wording. No date-based status changes.
- `src/components/Nav.astro`: mobile menu toggle and close on section selection.
- `src/components/Faq.astro`: native independent details disclosures.
- `src/layouts/Base.astro`, `astro.config.mjs`, `public/`: metadata,
  canonical and sitemap URLs, social image, favicon, robots, self-hosted fonts.
- `tests/dist.test.ts`, `src/lib/edition-status.test.ts`: existing behavior checks.

Setup preferences were read from the owner's [Laravel setup
playbook](https://app.notion.com/p/334d3ecc475c81719bdcc15d82bd5b23).
Its licensing credentials are intentionally omitted from this capture.
