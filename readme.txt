=== Beaver Themer for Eventin ===
Contributors: onedogsolutions
Tags: eventin, beaver builder, beaver themer, events, theme builder
Requires at least: 5.8
Tested up to: 7.0
Requires PHP: 7.4
Stable tag: 1.1.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.txt

Design Eventin single event and event archive layouts with Beaver Builder, using Beaver Themer.

== Description ==

Beaver Themer for Eventin connects the [Eventin](https://wordpress.org/plugins/wp-event-solution/) event management plugin to [Beaver Themer](https://www.wpbeaverbuilder.com/), the theme building add-on for Beaver Builder.

With it you can:

* Build a **Singular** Themer layout and assign it to the *Event* post type to fully design single event pages with Beaver Builder.
* Build an **Archive** Themer layout for the event archive (and the Eventin category / tag taxonomy archives).
* Connect Beaver Builder modules to event data through field connections in the **Eventin** connection group, including:
    * Start / end date & time (with custom formats)
    * Registration deadline, timezone and status
    * Event type, address and online/meeting link
    * Organizers, speakers, categories and tags
    * Ticket price, total tickets and tickets sold
    * Event logo and banner images
    * Latitude, longitude, map URL and location type
    * External link, recurring event flag
    * Per-ticket variations (name, price, available, sold, remaining) — indexed
    * Per-speaker details (name, photo, designation, company, bio, email, website, logo) — indexed
    * Per-organizer details (name, photo, email, phone, website, company, bio) — indexed
    * FAQ questions and answers — indexed
    * Speaker/organizer counts, ticket stats, price ranges

Customize the plugin's terminology through **Settings → Eventin Beaver Themer** (e.g. rename "Event" to "Class", "Speaker" to "Instructor") — no code required.

When a Themer layout is assigned to an event location, the plugin steps Eventin's own template aside so your Beaver Builder design renders. When no layout is assigned, Eventin behaves exactly as it does normally.

It also adds an **Eventin** group of Beaver Builder modules so you can rebuild the front-end of an events site (the things The Events Calendar used to render) directly in Beaver Builder:

* **Eventin Events** — a grid/list of events (replaces the events list view).
* **Eventin Events Tab** — events grouped into category tabs.
* **Eventin Events Calendar** — a monthly calendar (replaces the calendar view).
* **Eventin Event Search** — the event search / filter form.
* **Eventin Speakers** — a grid of speakers.
* **Eventin Schedule** — an event schedule as tabs or a list.
* **Eventin Event Tickets** — the ticket / registration form for an event. Uses Eventin's own ticket form, so it works with Eventin's native cart or any optional checkout integration (WooCommerce, FluentCart).

Each module renders through Eventin's own shortcodes and templates, so output matches Eventin exactly.

== Requirements ==

* Eventin (wp-event-solution)
* Beaver Builder
* Beaver Themer

== Installation ==

1. Make sure Eventin, Beaver Builder and Beaver Themer are installed and active.
2. Upload the `eventin-beaver-themer` folder to `/wp-content/plugins/`.
3. Activate the plugin through the *Plugins* screen in WordPress.
4. In *Beaver Builder > Themer Layouts*, add a new layout, choose **Singular** or **Archive**, and set its location to the *Event* post type or an event taxonomy.

== Changelog ==

= 1.1.1 =
* **Documentation correction** — clarified that Eventin has its own native cart/checkout and that WooCommerce/FluentCart are optional integrations, not plugin requirements. Updated README, STATE, readme.txt, and module docblocks accordingly.

= 1.1.0 =
* **50+ field connections** — added P1 scalar connections (latitude/longitude, map URL, location type, external link, recurring flag, ticket stats, speaker/organizer counts) and P2 indexed connections (per-speaker, per-organizer, per-ticket, FAQ details).
* **Label rewrite system** — centralized `Eventin_BT_Labels` class with three-tier override chain (defaults → admin settings → developer filter). Migrate terminology from The Events Calendar (e.g. "Event" → "Class", "Speaker" → "Instructor").
* **Admin settings page** — Settings → Eventin Beaver Themer provides UI for label customization without PHP code.
* **Speaker/organizer bug fix** — fixed broken field connections that were calling `User_Model` methods on `stdClass` objects instead of proper `Speaker\User_Model` instances.
* **Label-aware modules** — all 7 Beaver Builder modules now use `Eventin_BT_Labels` for name, category, and group labels.

= 1.0.0 =
* Initial release: Themer singular + archive layout support for Eventin events, plus Eventin field connections.
