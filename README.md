# Beaver Themer for Eventin

A Beaver Themer integration for styling single and archive layouts for [Eventin](https://wordpress.org/plugins/wp-event-solution/) with Beaver Builder.

It lets you design Eventin **single event** pages and **event archive** pages with Beaver Builder by way of Beaver Themer, and exposes Eventin event data to Beaver Builder modules through field connections.

## Features

- **Singular layouts** — assign a Themer *Singular* layout to the *Event* (`etn`) post type and design single event pages with Beaver Builder.
- **Archive layouts** — assign a Themer *Archive* layout to the event archive or the `etn_category` / `etn_tags` taxonomy archives.
- **Field connections** — an **Eventin** connection group with start/end date & time, registration deadline, timezone, status, event type, address, online/meeting link, organizers, speakers, categories, tags, ticket price, total tickets, tickets sold, logo and banner.
- **Beaver Builder modules** — an **Eventin** module group for rebuilding an events site front-end in Beaver Builder (a migration path away from The Events Calendar):
  - **Eventin Events** — event grid/list (`[events]`)
  - **Eventin Events Tab** — category-tabbed events (`[events_tab]`)
  - **Eventin Events Calendar** — monthly calendar (`[events_calendar]`)
  - **Eventin Event Search** — search/filter form (`[event_search_filter]`)
  - **Eventin Speakers** — speakers grid (`[speakers]`)
  - **Eventin Schedule** — schedule tabs/list (`[schedules]` / `[schedules_list]`)
  - **Eventin Event Tickets** — single-event ticket/registration form (Eventin's purchase form; replaces the WooCommerce ticket form on single event layouts)

  Each module renders through Eventin's own shortcodes/templates, so output matches Eventin exactly and stays correct as Eventin evolves.

When a Themer layout is assigned to an event location, the plugin removes Eventin's own single/archive `template_include` override for that request so the Beaver Builder layout renders. With no layout assigned, Eventin behaves exactly as normal.

## Migrating from The Events Calendar

This plugin is built to support replacing The Events Calendar (+ WooCommerce Event Tickets) with Eventin. The Beaver Builder modules above cover the equivalent front-end surfaces:

| The Events Calendar | Eventin Beaver Builder module |
| --- | --- |
| Calendar (month) view | Eventin Events Calendar |
| List / upcoming events | Eventin Events / Eventin Events Tab |
| Event search bar | Eventin Event Search |
| Single event page | Themer *Singular* layout + field connections |
| Ticket form (Event Tickets) | Eventin Event Tickets |

The longer-term target stack is **Eventin + FluentCart**. The Tickets module renders Eventin's own purchase/RSVP form, so it follows whichever checkout engine Eventin is configured to use (WooCommerce today, FluentCart later) without changes here.

## How it works

The plugin follows the same pattern Beaver Themer's first-party integrations use:

- Field connections are registered on `fl_page_data_add_properties` via `FLPageData::add_group()` / `add_post_property()`. The getters wrap Eventin's `Etn\Core\Event\Event_Model`.
- On `wp`, the singular and archive handlers detect an event view (including while editing a layout in the builder) and, when `FLThemeBuilderLayoutData::get_current_page_layouts()` reports a matching layout, strip the relevant `Eventin\Event\EventTemplate` callback from `template_include`.
- On event archives, the Beaver Builder Posts module's "Main Query" loop is ordered by event start date.

## Requirements

- WordPress 5.8+ / PHP 7.4+
- Eventin (wp-event-solution)
- Beaver Builder
- Beaver Themer

## Installation

The plugin lives in [`eventin-beaver-themer/`](eventin-beaver-themer/). Copy that folder into `wp-content/plugins/` and activate it, or zip it and upload it from the WordPress *Plugins* screen.

## License

GPL-2.0-or-later.
