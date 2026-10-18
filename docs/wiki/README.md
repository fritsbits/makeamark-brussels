# Make a Mark wiki working agreement

This wiki keeps the project's evidence and decisions in the repo.
The agent maintains the synthesis; the human curates sources and steers questions.
Structure follows the Cascade method used in the other Gather Studio projects.

## Source of truth

- `docs/raw/` holds source captures. Immutable. Cite by path or URL. No rewritten analysis.
- `docs/wiki/` holds the maintained synthesis, decisions and open concerns.

## Phases

Discovery, Strategy, Design, Build, in order.
Only the active phase has structure.
Each phase has one plan (`00-`) and one concerns register (`01-`).
A phase closes when its register has no unresolved Open items.

## Page conventions

- YAML frontmatter: `title`, `tags`, `sources`, `phase`, `updated`.
- Separate observation from interpretation. Cite claims. Name unknowns.
- Stable concern IDs (`D-###` for Discovery), never renumbered.
- Each full sentence on its own line.

## Ingest workflow

1. Put the original capture in `docs/raw/`.
2. Read it. Split evidence, interpretation, unknowns.
3. Update synthesis pages and cross references.
4. Update `index.md`, the concerns register, and append one line to `log.md`.

## Health check

Look for contradictions, stale claims, orphaned pages, missing citations, and concerns whose status differs across pages.
