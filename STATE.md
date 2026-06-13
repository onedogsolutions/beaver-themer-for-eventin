# Project State

Living history and roadmap for **Beaver Themer for Eventin** (`eventin-beaver-themer`).
Keep this file up to date as features land — append to **History** and prune **Roadmap**.

- **Plugin:** Beaver Themer for Eventin
- **Slug / text domain:** `eventin-beaver-themer`
- **Author:** One Dog Solutions — https://onedog.solutions/
- **Repository:** https://github.com/onedogsolutions/beaver-themer-for-eventin
- **Current version:** 1.0.0 (unreleased — in active development)
- **Requires:** WordPress 5.8+, PHP 7.4+, Eventin (`wp-event-solution`), Beaver Builder, Beaver Themer

## Purpose & context

A bridge between [Eventin](https://wordpress.org/plugins/wp-event-solution/) and
[Beaver Themer](https://www.wpbeaverbuilder.com/) so event single/archive layouts can be
designed in Beaver Builder, with field connections and front-end modules for event data.

This plugin is the first piece of a larger migration. The driving project is replacing
**The Events Calendar + WooCommerce** with **Eventin + FluentCart** on
[overthetopcakesupplies.com](https://overthetopcakesupplies.com/). The longer-term target
stack is **Eventin + FluentCart**; the plugin renders through Eventin's own
shortcodes/templates so it follows whichever checkout engine Eventin is configured with.

Sibling project: `onedogsolutions/fluentthemer-beaver-builder`.

## Current status

Functional v1.0.0 covering the "Themer core" scope plus an events-site module set.
Verified by source analysis and `php -l`; **not yet runtime-tested on a live WordPress
install**. See [Known limitations](#known-limitations--risks).

### What works today

- **Singular layouts** — a Themer *Singular* layout assigned to the Event (`etn`) post
  type takes over single event pages. The plugin removes Eventin's own
  `template_include` override (`Eventin\Event\EventTemplate::event_single_page`) for the
  request only when a matching Themer layout exists.
- **Archive layouts** — same takeover for the event post-type archive and the
  `etn_category` / `etn_tags` taxonomy archives, plus ordering the Beaver Builder Posts
  module main-query loop by event start date.
- **Field connections** — an **Eventin** connection group (`FLPageData`) with getters
  wrapping `Etn\Core\Event\Event_Model`: start/end date & time, registration deadline,
  timezone, status, event type, address, meeting link, organizers, speakers, categories,
  tags, ticket price, total/sold tickets, logo, banner.
- **Modules** — an **Eventin** module group: Eventin Events, Events Tab, Events Calendar,
  Event Search, Speakers, Schedule, Event Tickets. Each wraps an Eventin shortcode or
  template function.
- **Dependency guard** — admin notice if Eventin or Beaver Themer is inactive.

### Architecture map

```
eventin-beaver-themer/
  eventin-beaver-themer.php              Plugin header + bootstrap on plugins_loaded
  includes/
    class-eventin-beaver-themer.php      Dependency checks, wiring
    class-eventin-bt-template.php        Removes Eventin template_include overrides
    class-eventin-bt-singular.php        Single event layout takeover (on wp)
    class-eventin-bt-archive.php         Archive/taxonomy takeover + loop query (on wp)
    class-eventin-bt-page-data.php       Field connection getters (Event_Model wrappers)
    page-data-eventin.php                FLPageData property registrations
    class-eventin-bt-modules.php         Module loader + shortcode render helper
  modules/<slug>/<slug>.php              FLBuilder::register_module
  modules/<slug>/includes/frontend.php   Renders the Eventin shortcode/template
```

Key integration facts (verified against Eventin 4.1.14 source):
- Event post type: `etn` (public, has_archive, configurable slug).
- Taxonomies: `etn_category`, `etn_tags`.
- Eventin forces its templates via `template_include` priority 99; it natively yields to
  Elementor Pro but to no other builder — hence the override removal.
- Ticket form: `etn_after_single_event_meta_ticket_form()` (+ recurring variant), loaded
  unconditionally on `init` priority 5.

## History

| Date | Version | Summary |
| --- | --- | --- |
| 2026-06-13 | 1.0.0-dev | **Eventin Beaver Builder modules** (`b80a829`) — Eventin module group for events-site parity: Events, Events Tab, Events Calendar, Event Search, Speakers, Schedule, Event Tickets. Each wraps an Eventin shortcode/template function. README migration mapping added. |
| 2026-06-13 | 1.0.0-dev | **Themer core** (`ef173e5`) — initial plugin: singular + archive layout takeover, Eventin field connections, dependency guard, readme/README. Removed reference plugin archives from the repo. |
| 2026-06-13 | — | Reference plugin archives staged for development (`f165b69`), later removed and gitignored. |
| 2026-06-13 | — | Initial commit (`0d9a20f`). |

## Roadmap

Ordered roughly by priority for the overthetopcakesupplies.com migration.

### Near term (migration blockers / parity)
- [ ] **Live-site runtime test** — install on a staging copy of the target site; verify
      single + archive takeover, field connections, and each module render correctly
      (including inside the Beaver Builder editor iframe).
- [ ] **Confirm exact TEC parity for the site** — could not crawl the live site from the
      build environment (egress blocked). Enumerate the TEC views/widgets actually in use
      and close any gaps.
- [ ] **Single-event "Event Meta" module** — a convenience block bundling date/venue/
      organizer/cost in one module for out-of-the-box parity with TEC's single template
      (in addition to field connections).
- [ ] **Taxonomy/category term pickers** — replace the comma-separated term-ID text inputs
      in modules with Beaver Builder `suggest` fields for `etn_category` / `etn_tags`.

### Mid term (polish & robustness)
- [ ] **Editor asset loading** — ensure Eventin's styles/scripts enqueue inside the BB
      editor preview without a manual refresh (calendar/search rely on late enqueues).
- [ ] **Archive field connections** — add `FLPageData::add_archive_property` items (e.g.
      event archive title/description helpers) beyond Themer's core archive fields.
- [ ] **Related / single-event modules** — port high-value single-page elements from the
      Bricks/Oxygen addons as needed (related events, countdown, location/map, FAQ).
- [ ] **Settings page** — optional toggles (e.g. force-disable Eventin templates, asset
      handling) and a status/diagnostics panel.
- [ ] **Styling controls** — expose spacing/typography/color controls on modules rather
      than relying solely on Eventin's stylesheet.

### Long term (target stack)
- [ ] **FluentCart checkout verification** — once Eventin + FluentCart is the live stack,
      confirm the Event Tickets module renders the FluentCart purchase flow correctly.
- [ ] **Full Oxygen/Bricks element parity** (optional) — port the remaining ~30 addon
      widgets as BB modules if demand warrants.
- [ ] **WordPress.org / distribution readiness** — finalize readme.txt, i18n `.pot`,
      screenshots, banner/icon assets, and a tagged release.

## Known limitations & risks

- **Not runtime-tested.** Built from source analysis + lint only.
- **Themer API reliance.** Uses `FLThemeBuilderLayoutData::get_current_page_layouts()` and
  `FLThemeBuilderRulesLocation::get_preview_location()` (sourced from Beaver Themer's own
  first-party extensions); stable but worth re-checking on major Themer updates.
- **Eventin internal coupling.** Removing Eventin's `template_include` callbacks targets
  specific class/method names; both the current and legacy class names are handled, but a
  future Eventin rename would need updating in `class-eventin-bt-template.php`.
- **Module assets in the editor.** Eventin shortcodes enqueue assets at render time, which
  can require a page refresh to appear in the BB editor preview (same as the Oxygen/Bricks
  addons).

## Decisions log

- **Scope:** v1 is "Themer core" (single/archive + field connections) plus a shortcode-
  wrapping module set — not a full port of the ~30 Oxygen/Bricks widgets.
- **Module strategy:** wrap Eventin's own shortcodes/template functions rather than
  re-implement markup, for guaranteed visual parity and forward compatibility.
- **Reference material:** the Eventin/addon source used during development is kept locally
  under `_reference/` (gitignored) and is **not** committed, to avoid redistributing paid
  code.
