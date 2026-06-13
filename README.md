# Beaver Themer for Eventin

A Beaver Themer integration for styling single and archive layouts for [Eventin](https://wordpress.org/plugins/wp-event-solution/) with Beaver Builder.

It lets you design Eventin **single event** pages and **event archive** pages with Beaver Builder by way of Beaver Themer, and exposes Eventin event data to Beaver Builder modules through field connections.

## Features

- **Singular layouts** — assign a Themer *Singular* layout to the *Event* (`etn`) post type and design single event pages with Beaver Builder.
- **Archive layouts** — assign a Themer *Archive* layout to the event archive or the `etn_category` / `etn_tags` taxonomy archives.
- **Field connections** — an **Eventin** connection group with start/end date & time, registration deadline, timezone, status, event type, address, online/meeting link, organizers, speakers, categories, tags, ticket price, total tickets, tickets sold, logo and banner.

When a Themer layout is assigned to an event location, the plugin removes Eventin's own single/archive `template_include` override for that request so the Beaver Builder layout renders. With no layout assigned, Eventin behaves exactly as normal.

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
