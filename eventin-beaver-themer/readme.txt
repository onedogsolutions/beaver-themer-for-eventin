=== Beaver Themer for Eventin ===
Contributors: onedogsolutions
Tags: eventin, beaver builder, beaver themer, events, theme builder
Requires at least: 5.8
Tested up to: 6.5
Requires PHP: 7.4
Stable tag: 1.0.0
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

When a Themer layout is assigned to an event location, the plugin steps Eventin's own template aside so your Beaver Builder design renders. When no layout is assigned, Eventin behaves exactly as it does normally.

It also adds an **Eventin** group of Beaver Builder modules so you can rebuild the front-end of an events site (the things The Events Calendar used to render) directly in Beaver Builder:

* **Eventin Events** — a grid/list of events (replaces the events list view).
* **Eventin Events Tab** — events grouped into category tabs.
* **Eventin Events Calendar** — a monthly calendar (replaces the calendar view).
* **Eventin Event Search** — the event search / filter form.
* **Eventin Speakers** — a grid of speakers.
* **Eventin Schedule** — an event schedule as tabs or a list.
* **Eventin Event Tickets** — the ticket / registration form for an event (use it on a single event layout in place of the WooCommerce ticket form).

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

= 1.0.0 =
* Initial release: Themer singular + archive layout support for Eventin events, plus Eventin field connections.
