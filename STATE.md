# Project State

Living history and roadmap for **Beaver Themer for Eventin** (`eventin-beaver-themer`).
Keep this file up to date as features land — append to **History** and prune **Roadmap**.

- **Plugin:** Beaver Themer for Eventin
- **Slug / text domain:** `eventin-beaver-themer`
- **Author:** One Dog Solutions — https://onedog.solutions/
- **Repository:** https://github.com/onedogsolutions/beaver-themer-for-eventin
- **Current version:** 1.1.1 (released 2026-08-04)
- **Requires:** WordPress 5.8+, PHP 7.4+, Eventin (`wp-event-solution`), Beaver Builder, Beaver Themer

## Purpose & context

A bridge between [Eventin](https://wordpress.org/plugins/wp-event-solution/) and
[Beaver Themer](https://www.wpbeaverbuilder.com/) so event single/archive layouts can be
designed in Beaver Builder, with field connections and front-end modules for event data.

This plugin is the first piece of a larger migration. The driving project is replacing
**The Events Calendar + WooCommerce Event Tickets** with **Eventin** on
[overthetopcakesupplies.com](https://overthetopcakesupplies.com/). Eventin has its own
native cart and checkout; WooCommerce and FluentCart are optional integrations rather
than requirements. The plugin renders through Eventin's own shortcodes/templates so it
follows whichever checkout engine Eventin is configured to use.

Sibling project: `onedogsolutions/fluentthemer-beaver-builder`.

## Current status

Version 1.1.0 — "Themer core + full connections + label rewrite". All field connections
verified against live OTT Staging data via Novamira MCP. Speaker/organizer bug fixed.
Label rewrite system operational with admin UI. See
[Known limitations](#known-limitations--risks).

### What works today

- **Singular layouts** — a Themer *Singular* layout assigned to the Event (`etn`) post
  type takes over single event pages. The plugin removes Eventin's own
  `template_include` override (`Eventin\Event\EventTemplate::event_single_page`) for the
  request only when a matching Themer layout exists.
- **Archive layouts** — same takeover for the event post-type archive and the
  `etn_category` / `etn_tags` taxonomy archives, plus ordering the Beaver Builder Posts
  module main-query loop by event start date.
- **Field connections** — an **Eventin** connection group (`FLPageData`) with 50+
  getters wrapping `Event_Model` and `Speaker\User_Model`: start/end date & time,
  registration deadline, timezone, status, event type, address, meeting link,
  organizers, speakers, categories, tags, ticket price, total/sold tickets, logo,
  banner, latitude/longitude, location data, external link, recurring flag, per-ticket
  variations (indexed), per-speaker and per-organizer details (indexed), FAQ items
  (indexed), and more. See [IMPLEMENTATION-PLAN.md](IMPLEMENTATION-PLAN.md) for the
  full connection reference.
- **Label rewrite system** — centralized `Eventin_BT_Labels` class with a three-tier
  override chain (defaults → admin settings → developer filter). Sites migrating from
  The Events Calendar can rename "Event" → "Class", "Speaker" → "Instructor", etc.
  without code.
- **Admin settings page** — **Settings → Eventin Beaver Themer** in wp-admin provides
  a UI for label customization (noun singular/plural pairs, module group name).
- **Modules** — an **Eventin** module group (label-aware): Eventin Events, Events Tab,
  Events Calendar, Event Search, Speakers, Schedule, Event Tickets. Each wraps an
  Eventin shortcode or template function.
- **Dependency guard** — admin notice if Eventin or Beaver Themer is inactive.

### Architecture map

```
eventin-beaver-themer/
  eventin-beaver-themer.php              Plugin header + bootstrap on plugins_loaded
  uninstall.php                          WordPress uninstall handler (no-op today)
  IMPLEMENTATION-PLAN.md                 Detailed data model + phased build plan
  includes/
    class-eventin-beaver-themer.php      Dependency checks, wiring
    class-eventin-bt-labels.php          Centralized label dictionary (3-tier overrides)
    class-eventin-bt-settings.php        Admin settings page for label customization
    class-eventin-bt-template.php        Removes Eventin template_include overrides
    class-eventin-bt-singular.php        Single event layout takeover (on wp)
    class-eventin-bt-archive.php         Archive/taxonomy takeover + loop query (on wp)
    class-eventin-bt-page-data.php       Field connection getters (Event_Model + User_Model)
    page-data-eventin.php                FLPageData property registrations (50+ connections)
    class-eventin-bt-modules.php         Module loader + shortcode render helper
  modules/<slug>/<slug>.php              FLBuilder::register_module (label-aware)
  modules/<slug>/includes/frontend.php   Renders the Eventin shortcode/template
  languages/
    eventin-beaver-themer.pot            Translation template (99 strings)
  tools/
    generate-pot.php                     Regenerates the .pot from source
```

Key integration facts (verified against Eventin 4.1.14 source + live OTT Staging MCP queries):
- Event post type: `etn` (public, has_archive, configurable slug).
- Taxonomies: `etn_category`, `etn_tags`.
- Speakers and organizers are WordPress users with roles `etn-speaker` / `etn-organizer`,
  accessed via `Etn\Core\Speaker\User_Model`.
- Location stored as serialized object (`address`, `latitude`, `longitude`, `place_id`).
- Ticket variations stored as serialized post meta with specific key names (note Eventin
  typos: `etn_avaiilable_tickets`, `etn_total_avaiilable_tickets`).
- Eventin forces its templates via `template_include` priority 99; it natively yields to
  Elementor Pro but to no other builder — hence the override removal.
- Ticket form: `etn_after_single_event_meta_ticket_form()` (+ recurring variant), loaded
  unconditionally on `init` priority 5.

## History

| Date | Version | Summary |
| --- | --- | --- |
| 2026-08-04 | 1.1.1 | **Documentation correction** — clarified that Eventin has its own native cart/checkout and that WooCommerce and FluentCart are optional integrations, not plugin requirements. Updated README.md, STATE.md, readme.txt, and Eventin Tickets module docblock. No runtime changes. |
| 2026-08-03 | 1.1.0 | **Full connections, labels, bug fix** — 50+ field connections (P1 scalars: lat/lng, map URL, location type, external link, recurring flag, ticket stats, speaker/organizer counts; P2 indexed: per-speaker 8 fields, per-organizer 7 fields, per-ticket 5 fields, FAQ 2 fields). Label rewrite system (`Eventin_BT_Labels` with 3-tier override chain). Admin settings page at Settings → Eventin Beaver Themer for label customization without code. Fixed broken speaker/organizer getters (were calling `User_Model` methods on `stdClass` objects). All 7 modules retrofitted to use `Eventin_BT_Labels`. Verified on live OTT Staging via Novamira MCP. |
| 2026-08-03 | 1.0.0-dev | **Distribution housekeeping** — replaced GPLv3 license with GPLv2 (matches plugin header), lean plugin-specific `.gitignore`, added `uninstall.php`, `languages/eventin-beaver-themer.pot` (99 strings) with `tools/generate-pot.php` regeneration script, updated `readme.txt` Tested up to 7.0. Zip packaged for live-site testing. |
| 2026-06-13 | 1.0.0-dev | **Eventin Beaver Builder modules** (`b80a829`) — Eventin module group for events-site parity: Events, Events Tab, Events Calendar, Event Search, Speakers, Schedule, Event Tickets. Each wraps an Eventin shortcode/template function. README migration mapping added. |
| 2026-06-13 | 1.0.0-dev | **Themer core** (`ef173e5`) — initial plugin: singular + archive layout takeover, Eventin field connections, dependency guard, readme/README. Removed reference plugin archives from the repo. |
| 2026-06-13 | — | Reference plugin archives staged for development (`f165b69`), later removed and gitignored. |
| 2026-06-13 | — | Initial commit (`0d9a20f`). |

## Roadmap

Ordered roughly by priority for the overthetopcakesupplies.com migration.

### Near term (migration blockers / parity)
- [ ] **Live-site runtime test** — install on a staging copy of the target site; verify
      single + archive takeover, all 50+ field connections, and each module render
      correctly (including inside the Beaver Builder editor iframe).
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
- [x] **Settings page** — admin UI for label customization at Settings → Eventin Beaver Themer.
- [ ] **Styling controls** — expose spacing/typography/color controls on modules rather
      than relying solely on Eventin's stylesheet.

### Long term (target stack)
- [ ] **FluentCart checkout verification (optional)** — if the site later enables FluentCart with Eventin, confirm the Event Tickets module renders the FluentCart purchase flow correctly.
- [ ] **Full Oxygen/Bricks element parity** (optional) — port the remaining ~30 addon
      widgets as BB modules if demand warrants.
- [x] **i18n `.pot` file** — `languages/eventin-beaver-themer.pot` with 99 translatable strings; `tools/generate-pot.php` regenerates from source.
- [ ] **WordPress.org / distribution readiness** — finalize screenshots, banner/icon assets, and a tagged release.

## Known limitations & risks

- **Runtime-tested on staging.** Field connections verified against live OTT Staging via Novamira MCP. Built from source analysis + lint + live MCP queries.
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
