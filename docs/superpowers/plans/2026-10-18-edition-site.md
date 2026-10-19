# Edition Site Implementation Plan

> **For agentic workers:** Use this plan task by task. Each task ends with its own verification.

**Goal:** Build the approved phase-aware Make a Mark Brussels edition website and a repeatable CI workflow.

**Architecture:** Keep the existing Laravel/Vite/Blade stack and file-based edition content. Add explicit public routes for the edition hub and both audience journeys; let the edition status control the home actions. Add a GitHub Actions workflow that runs Laravel feature tests and the Vite production build.

**Tech Stack:** Laravel 13, PHP 8.3, Blade, Livewire/Flux Pro, Vite, Tailwind CSS 4, PHPUnit, Node 22.

**Spec:** `docs/ux/_strategy.md`, `docs/ux/_scope.md`, `docs/ux/_structure.md`, `docs/ux/_skeleton.md`, and the approved Notion sitemap under Make a Mark 2027.

## Global Constraints

- Keep the Brussels Broadsheet identity and existing self-hosted fonts, logo, and photography.
- Use the approved English-first copy direction and balance makers and social organisations.
- Do not invent venue, outcome, response-time, consent, privacy, or application-requiredness claims.
- Keep case-study pages and signup administration out of this release.
- Keep Laravel, Livewire, Flux Pro, Tailwind, and bundled Alpine; add no packages.
- Keep WCAG 2.2 AA as the accessibility target.

## Review Focus

- Unknown edition status must never expose an application action — test status-driven home behavior.
- Audience routes must preserve equal access from navigation and the edition hub — test both routes and actions.
- The form field audit and privacy decisions remain open — test that application views do not claim an active submission until the phase is open.
- All added routes must render without Vite in feature tests — test each page response.
- CI must use the project's supported PHP and Node versions — inspect workflow matrix and run the same commands locally.

## Tasks

### Task 1: Add route and phase behavior tests

Add feature tests for the edition hub, maker and organisation pages, application route availability by phase, and search metadata. Run targeted tests first and confirm the missing routes or behavior fail.

### Task 2: Build the edition hub and audience journeys

Add the 2027 edition content and route-backed navigation. Render the approved content hierarchy, preserve existing media and FAQ, and add maker, organisation, and application views using the shared layout and design tokens. Run the feature suite and production asset build.

### Task 3: Add automated build and test workflow

Add a GitHub Actions workflow using PHP 8.3 and Node 22; install locked dependencies, configure SQLite, run `php artisan test`, then `npm run build`. Run both workflow commands locally.

### Task 4: Update Cascade implementation records

Add the Build phase plan and concerns register, link the approved UX and implementation records from the wiki index, and log the implementation. Keep unresolved data-handling decisions open.
