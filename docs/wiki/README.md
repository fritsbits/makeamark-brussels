# Cascade working agreement

Cascade keeps the product's evidence and decisions beside its implementation.
The agent maintains the synthesis; the human curates source material and
steers questions.

## Source of truth

- `docs/raw/` contains source captures. Treat them as immutable and cite them
  by stable path or URL. Do not put rewritten analysis here.
- `docs/wiki/` contains the maintained synthesis, decisions, and open concerns.
- Code and product docs should stay in sync. Record intent, scope, structure,
  and implementation changes together.

## Phases and planes

Phases proceed in order: Discovery → Strategy → Design → Build. Within each
phase, decide only what the active phase requires. For experience decisions,
work top-down through Strategy, Scope, Structure, Skeleton, and Surface;
higher planes constrain lower ones.

Each phase has one plan (`00-…`) and one concerns register (`01-…`). A phase
closes only when its register has no unresolved Open items. Carry unresolved
items into the next phase. Later phase folders and artifacts are created when
that phase opens.

## Evidence and page conventions

Every synthesis page uses YAML frontmatter with `title`, `tags`, `sources`,
`phase`, and `updated`. Separate observation from interpretation. Cite claims;
flag anything without evidence and name material unknowns. Use stable concern
IDs and one canonical register entry per concern.

## Source workflow

1. Add an original capture to `docs/raw/` and preserve it unchanged.
2. Read it and identify evidence, interpretation, and unknowns separately.
3. Update the relevant synthesis page and cross references.
4. Update `index.md`, the active concerns register, and append to `log.md`.

## Health check

Check for contradictions, stale claims, orphaned pages, missing citations,
missing cross references, and concerns whose status differs across pages.
