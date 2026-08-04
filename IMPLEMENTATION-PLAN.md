# Single Event Page — Complete Beaver Themer Implementation Plan

**Goal:** Expose every data point a designer needs to build a fully custom single event page in Beaver Builder, with field connections and modules covering tickets, speakers, organizers, location, media, FAQ, socials, and schedule.

**Data verified against:** OTT Staging (Eventin + Eventin Pro active, event post ID 52042).

---

## 🔴 Critical Bug: Existing Speakers/Organizers Connections Are Broken

`Event_Model::get_speakers()` returns `[{"id": 60}, {"id": 61}, {"id": 62}]` — an array of **stdClass objects with an `id` property**, NOT `User_Model` instances.

The existing `people_names()` helper in `class-eventin-bt-page-data.php` calls `method_exists( $person, 'get_speaker_title' )`, which returns `false` on these stdClass objects. **Result: the speakers and organizers field connections always return empty string.**

**Fix:** Instantiate `Etn\Core\Speaker\User_Model` for each ID:
```php
private static function speaker_model_at( $index ) {
    $ids = self::speaker_ids();
    if ( empty( $ids ) || ! isset( $ids[ $index ] ) ) {
        return null;
    }
    if ( ! class_exists( 'Etn\\Core\\Speaker\\User_Model' ) ) {
        return null;
    }
    return new \Etn\Core\Speaker\User_Model( $ids[ $index ] );
}
```

---

## Verified Eventin Data Model (from live staging)

### Event Model Methods (`Event_Model`)

Available getters confirmed via `get_class_methods()`:
```
get_total_ticket, get_status, get_ticket, get_title, get_description,
get_social, get_address, get_start_datetime, get_end_datetime, get_timezone,
get_datetime, is_expaired, get_total_sold_ticket, get_ticket_price_by_name,
get_ticket_slug_by_name, is_enable_seatmap, has_meeting_link,
get_meeting_platform, get_attendees, get_related_events, get_speakers,
get_organizers, get_start_date, get_start_time, get_end_date, get_end_time,
get_tags, get_categories, get_data
```

### Event Post Meta (ID 52042 — verified values)

| Meta Key | Value / Structure | Notes |
|---|---|---|
| `etn_start_date` | `"2026-09-14"` | Y-m-d format |
| `etn_end_date` | `"2026-09-16"` | Y-m-d format |
| `etn_start_time` | `"9:00 AM"` | 12-hour format string |
| `etn_end_time` | `"5:00 PM"` | 12-hour format string |
| `event_timezone` | `"America/New_York"` | **No `etn_` prefix!** |
| `etn_event_location` | `{address, place_id, latitude, longitude}` | **Object, not string!** |
| `etn_event_location_type` | `"venue"` | venue / online |
| `event_type` | `"offline"` | **No `etn_` prefix!** online/offline/hybrid |
| `etn_ticket_variations` | Array of variation objects (see below) | Correct spelling (not "varations") |
| `etn_total_avaiilable_tickets` | `"400"` | Note Eventin's typo: "avaii**i**lable" |
| `etn_sold_tickets` | `"0"` | Aggregate sold count |
| `etn_event_faq` | Array of `{etn_faq_title, etn_faq_content}` | **NEW: FAQ data!** |
| `etn_event_socials` | Array of `{icon, etn_social_url}` | Social links with icon classes |
| `event_banner` | URL string | **No `etn_` prefix!** |
| `event_banner_id` | `"52038"` | **No `etn_` prefix!** Attachment ID |
| `event_logo_id` | `""` | Logo attachment ID (empty on staging) |
| `etn_event_speaker` | `[60, 61, 62]` | **User IDs** (not post IDs!) |
| `etn_event_organizer` | `[63, 64]` | **User IDs** (not post IDs!) |
| `etn_event_schedule` | `[52039, 52040, 52041]` | `etn-schedule` post IDs |
| `external_link` | `""` | External event URL |
| `recurring_enabled` | `"no"` | yes/no |
| `etn_registration_deadline` | `""` | Deadline date |
| `etn_zoom_id` | `""` | Zoom meeting ID |
| `etn_google_meet` | `""` | Google Meet link |
| `event_layout` | `"517387"` | Eventin template layout ID |
| `etn_event_logo` | `""` | Logo URL |
| `etn_event_calendar_bg` | `""` | Calendar background color |
| `ticket_template` | `"style-2"` | Ticket display style |
| `speaker_type` | `""` | Speaker display type |
| `organizer_type` | `""` | Organizer display type |

### Location Structure (`etn_event_location`)

```json
{
    "address": "Nilkhet Rd, Dhaka 1000, Bangladesh",
    "place_id": "ChIJT55ECum4VTcRpH8ZJZwqCbc",
    "latitude": 23.7339483,
    "longitude": 90.3929252
}
```

Key: Eventin uses Google Places for location. City/state/zip/country are **NOT** stored as separate meta keys — they are embedded in the full address string. We can parse the address string or expose it as-is.

### Ticket Variation Structure (`etn_ticket_variations`)

```json
{
    "etn_ticket_name": "VIP Pass",
    "etn_ticket_price": 499,
    "etn_avaiilable_tickets": 100,
    "etn_sold_tickets": 0,
    "etn_min_ticket": 1,
    "etn_max_ticket": 5,
    "etn_ticket_slug": "517382-vip-pass-27fad",
    "pending": 0,
    "optiontics_block_ids": [],
    "waiting_list_count": 0
}
```

Notes:
- `etn_ticket_price` is an **integer**, not a float/string
- `etn_avaiilable_tickets` has Eventin's internal typo (double "ii")
- `etn_ticket_description` and `etn_ticket_status` are **NOT present** in this Eventin version
- `etn_ticket_slug` is a unique identifier for the variation
- `etn_min_ticket` / `etn_max_ticket` control purchase quantity limits

### Speaker/Organizer Data Model

**Speakers and organizers are WordPress USERS**, not posts!

- `etn_event_speaker` stores an array of **user IDs** (role: `etn-speaker`)
- `etn_event_organizer` stores an array of **user IDs** (role: `etn-organizer`)
- Both use `Etn\Core\Speaker\User_Model` class (same class, different roles)

**User_Model methods:**
```
get_speaker_title()     → display_name
get_speaker_email()     → user_email
get_speaker_designation() → etn_speaker_designation user meta
get_speaker_summary()   → etn_speaker_summery user meta (note typo!)
get_speaker_socials()   → etn_speaker_social user meta (array)
get_company_name()      → etn_company_name user meta
get_speaker_url()       → etn_speaker_url user meta
get_image()             → image user meta (URL)
get_image_id()          → image_id user meta (attachment ID)
get_company_logo()      → etn_speaker_company_logo user meta
get_company_logo_id()   → etn_company_logo_id user meta
get_author_url()        → WordPress author posts URL
get_full_name()         → first_name + last_name
get_display_name()      → display_name
get_speaker_category()  → etn_speaker_category user meta
get_speaker_group()     → etn_speaker_group user meta
get_phone()             → phone user meta
get_organizer_bio()     → organizer_bio or etn_speaker_summery user meta
```

`Event_Model::get_speakers()` returns `[{"id": 60}, {"id": 61}]` — stdClass with just an `id` property.
`Event_Model::get_organizers()` returns the same structure.

### Schedule Structure (`etn-schedule` post type)

Each schedule post contains:
```json
{
    "etn_schedule_title": "Day 1 — Foundations",
    "etn_schedule_date": "2026-09-14",
    "etn_schedule_topics": [
        {
            "etn_schedule_topic": "Opening Keynote: The State of Applied AI",
            "etn_shedule_start_time": "09:00 AM",
            "etn_shedule_end_time": "10:00 AM",
            "speakers": [60],
            "etn_shedule_room": "Main Hall",
            "etn_shedule_objective": "Where applied ML is heading..."
        }
    ]
}
```

Note Eventin's consistent typo: `etn_shedule_*` (missing "c") for sub-fields.

### FAQ Structure (`etn_event_faq`)

```json
{
    "etn_faq_title": "Who should attend?",
    "etn_faq_content": "ML engineers, data scientists..."
}
```

### Social Links Structure (`etn_event_socials`)

```json
{
    "icon": "etn-icon fa-facebook-f",
    "etn_social_url": "https://facebook.com/"
}
```

---

## Gap Analysis: What We Have vs What We Need

### ✅ Already Exposed (v1.0.0) — with corrections needed

| Connection | Status | Issue |
|---|---|---|
| Start/End Date & Time | ✅ Working | — |
| Registration Deadline | ✅ Working | — |
| Timezone | ✅ Working | — |
| Status | ✅ Working | — |
| Event Type | ✅ Working | — |
| Address | ✅ Working | `get_address()` correctly returns just the address string |
| Meeting Link | ✅ Working | — |
| **Speakers** | 🔴 **Broken** | Returns `""` — needs User_Model fix |
| **Organizers** | 🔴 **Broken** | Returns `""` — needs User_Model fix |
| Categories | ✅ Working | — |
| Tags | ✅ Working | — |
| Ticket Price (from) | ✅ Working | But uses `etn_ticket_price` key correctly |
| Total Tickets | ✅ Working | — |
| Tickets Sold | ✅ Working | — |
| Logo | ✅ Working | — |
| Banner | ✅ Working | — |

### ❌ Missing — Location & Venue

| Data Point | Meta Key | Access Pattern |
|---|---|---|
| Full Address | `etn_event_location.address` | `$event->etn_event_location['address']` or `$event->get_address()` |
| Latitude | `etn_event_location.latitude` | `$event->etn_event_location['latitude']` |
| Longitude | `etn_event_location.longitude` | `$event->etn_event_location['longitude']` |
| Google Place ID | `etn_event_location.place_id` | `$event->etn_event_location['place_id']` |
| Location Type | `etn_event_location_type` | `$event->etn_event_location_type` |
| Map Embed URL | Computed from lat/lng | Build Google Maps embed URL |
| External Link | `external_link` | `$event->external_link` |

### ❌ Missing — Speakers (Per-Speaker Detail)

| Data Point | User_Model Method | Type |
|---|---|---|
| Speaker Count | `count($event->etn_event_speaker)` | string |
| Speaker Name | `get_speaker_title()` | string (indexed) |
| Speaker Photo | `get_image_id()` | photo (indexed) |
| Speaker Designation | `get_speaker_designation()` | string (indexed) |
| Speaker Company | `get_company_name()` | string (indexed) |
| Speaker Bio | `get_speaker_summary()` | string/html (indexed) |
| Speaker Website | `get_speaker_url()` | url (indexed) |
| Speaker Email | `get_speaker_email()` | string (indexed) |
| Speaker Social Links | `get_speaker_socials()` | array (indexed) |
| Speaker Company Logo | `get_company_logo_id()` | photo (indexed) |
| Speaker Author URL | `get_author_url()` | url (indexed) |
| **Speaker Grid Module** | All speakers rendered | New BB module |

### ❌ Missing — Organizers (Per-Organizer Detail)

| Data Point | User_Model Method | Type |
|---|---|---|
| Organizer Count | `count($event->etn_event_organizer)` | string |
| Organizer Name | `get_speaker_title()` | string (indexed) |
| Organizer Photo | `get_image_id()` | photo (indexed) |
| Organizer Email | `get_speaker_email()` | string (indexed) |
| Organizer Phone | `get_phone()` | string (indexed) |
| Organizer Website | `get_speaker_url()` | url (indexed) |
| Organizer Company | `get_company_name()` | string (indexed) |
| Organizer Bio | `get_organizer_bio()` | string/html (indexed) |
| **Organizer Card Module** | All organizers rendered | New BB module |

### ❌ Missing — Tickets (Per-Variation Detail)

| Data Point | Variation Key | Type |
|---|---|---|
| Ticket Count | `count(get_ticket())` | string |
| Ticket Name | `etn_ticket_name` | string (indexed) |
| Ticket Price | `etn_ticket_price` | string (indexed, with currency option) |
| Available Tickets | `etn_avaiilable_tickets` | string (indexed) |
| Tickets Sold | `etn_sold_tickets` | string (indexed) |
| Remaining | avail - sold (computed) | string (indexed) |
| Min Purchase | `etn_min_ticket` | string (indexed) |
| Max Purchase | `etn_max_ticket` | string (indexed) |
| Ticket Slug | `etn_ticket_slug` | string (indexed) |
| Max Price | `max(etn_ticket_price)` | string |
| Price Range | "From $X to $Y" | string |
| **Ticket Pricing Table** | All variations rendered | New BB module |

### ❌ Missing — FAQ, Socials, Schedule, Misc

| Data Point | Meta Key | Type |
|---|---|---|
| FAQ Count | `count(etn_event_faq)` | string |
| FAQ Question (indexed) | `etn_faq_title` | string |
| FAQ Answer (indexed) | `etn_faq_content` | string/html |
| **FAQ Accordion Module** | All FAQs rendered | New BB module |
| Social Links | `etn_event_socials` | array → icon list module |
| **Social Links Module** | Rendered as icon bar | New BB module |
| Schedule IDs | `etn_event_schedule` | array of post IDs |
| Recurring Flag | `recurring_enabled` | string (yes/no) |
| Event Permalink | `get_permalink()` | url |
| Remaining Tickets (total) | total - sold (computed) | string |

---

## Implementation Strategy

### Phase 0: Bug Fix — Speakers & Organizers (Critical)

**File:** `includes/class-eventin-bt-page-data.php`

**Problem:** `people_names()` expects `User_Model` objects but `get_speakers()` / `get_organizers()` returns stdClass objects with just `id`.

**Fix:**
```php
private static function user_model_ids( $ids_key ) {
    $event = self::event();
    if ( ! $event ) return array();
    try {
        $ids = $event->{$ids_key};
    } catch ( \Exception $e ) {
        return array();
    }
    return is_array( $ids ) ? $ids : array();
}

private static function speaker_ids() {
    return self::user_model_ids( 'etn_event_speaker' );
}

private static function organizer_ids() {
    return self::user_model_ids( 'etn_event_organizer' );
}

private static function user_model( $user_id ) {
    if ( ! class_exists( 'Etn\\Core\\Speaker\\User_Model' ) ) {
        return null;
    }
    return new \Etn\Core\Speaker\User_Model( $user_id );
}
```

Then rewrite `organizers()` and `speakers()`:
```php
public static function speakers() {
    $names = array();
    foreach ( self::speaker_ids() as $id ) {
        $model = self::user_model( $id );
        if ( $model ) {
            $name = $model->get_speaker_title();
            if ( $name ) $names[] = $name;
        }
    }
    return implode( ', ', $names );
}

public static function organizers() {
    // Same pattern using organizer_ids()
}
```

### Phase 1: Scalar Field Connections

**New getters in `Eventin_BT_Page_Data`:**

| Getter | Source | Notes |
|---|---|---|
| `latitude()` | `$event->etn_event_location['latitude']` | |
| `longitude()` | `$event->etn_event_location['longitude']` | |
| `place_id()` | `$event->etn_event_location['place_id']` | Google Place ID |
| `location_type()` | `$event->etn_event_location_type` | venue / online |
| `map_url()` | Computed from lat/lng | `https://maps.google.com/maps?q=lat,lng&output=embed` |
| `external_link()` | `$event->external_link` | URL |
| `max_ticket_price()` | `max()` over `etn_ticket_price` | Like `ticket_price()` but max |
| `ticket_price_range()` | min + max | "From $X to $Y" |
| `ticket_count()` | `count(get_ticket())` | Number of variations |
| `remaining_tickets()` | total - sold | Aggregate remaining |
| `is_recurring()` | `$event->recurring_enabled` | "yes" / "no" |
| `event_url()` | `get_permalink()` | Event permalink |
| `speaker_count()` | `count(speaker_ids())` | |
| `organizer_count()` | `count(organizer_ids())` | |
| `faq_count()` | `count(etn_event_faq)` | |
| `social_links()` | `$event->get_social()` | Comma-separated URLs or rendered |

### Phase 2: Indexed Field Connections (Speaker / Organizer / Ticket / FAQ)

**Helper methods:**
```php
private static function speaker_model_at( $index ) {
    $ids = self::speaker_ids();
    if ( ! isset( $ids[ $index ] ) ) return null;
    return self::user_model( $ids[ $index ] );
}

private static function organizer_model_at( $index ) {
    $ids = self::organizer_ids();
    if ( ! isset( $ids[ $index ] ) ) return null;
    return self::user_model( $ids[ $index ] );
}

private static function ticket_at( $index ) {
    $event = self::event();
    if ( ! $event ) return null;
    $variations = self::safe( array( $event, 'get_ticket' ) );
    return ( is_array( $variations ) && isset( $variations[ $index ] ) )
        ? $variations[ $index ] : null;
}

private static function faq_at( $index ) {
    $event = self::event();
    if ( ! $event ) return null;
    try { $faqs = $event->etn_event_faq; } catch ( \Exception $e ) { return null; }
    return ( is_array( $faqs ) && isset( $faqs[ $index ] ) ) ? $faqs[ $index ] : null;
}
```

**New indexed connections (each with an `index` settings field):**

| Connection ID | Label | Getter | Type |
|---|---|---|---|
| `eventin_speaker_name` | Speaker Name | `speaker_name($s)` | string |
| `eventin_speaker_photo` | Speaker Photo | `speaker_photo($s)` | photo |
| `eventin_speaker_designation` | Speaker Title | `speaker_designation($s)` | string |
| `eventin_speaker_company` | Speaker Company | `speaker_company($s)` | string |
| `eventin_speaker_bio` | Speaker Bio | `speaker_bio($s)` | html |
| `eventin_speaker_website` | Speaker Website | `speaker_website($s)` | url |
| `eventin_speaker_email` | Speaker Email | `speaker_email($s)` | string |
| `eventin_speaker_company_logo` | Speaker Company Logo | `speaker_company_logo($s)` | photo |
| `eventin_organizer_name` | Organizer Name | `organizer_name($s)` | string |
| `eventin_organizer_photo` | Organizer Photo | `organizer_photo($s)` | photo |
| `eventin_organizer_email` | Organizer Email | `organizer_email($s)` | string |
| `eventin_organizer_phone` | Organizer Phone | `organizer_phone($s)` | string |
| `eventin_organizer_website` | Organizer Website | `organizer_website($s)` | url |
| `eventin_organizer_company` | Organizer Company | `organizer_company($s)` | string |
| `eventin_organizer_bio` | Organizer Bio | `organizer_bio($s)` | html |
| `eventin_ticket_name` | Ticket Name | `ticket_name($s)` | string |
| `eventin_ticket_price_item` | Ticket Price | `ticket_price_item($s)` | string |
| `eventin_ticket_available` | Available Tickets | `ticket_available($s)` | string |
| `eventin_ticket_sold_item` | Tickets Sold | `ticket_sold_item($s)` | string |
| `eventin_ticket_remaining` | Remaining Tickets | `ticket_remaining($s)` | string |
| `eventin_faq_question` | FAQ Question | `faq_question($s)` | string |
| `eventin_faq_answer` | FAQ Answer | `faq_answer($s)` | html |

**Index settings field:**
```php
$eventin_bt_index_field = array(
    'index' => array(
        'type'    => 'select',
        'label'   => __( 'Item', 'eventin-beaver-themer' ),
        'default' => '0',
        'options' => array(
            '0' => __( 'First', 'eventin-beaver-themer' ),
            '1' => __( 'Second', 'eventin-beaver-themer' ),
            '2' => __( 'Third', 'eventin-beaver-themer' ),
            '3' => __( 'Fourth', 'eventin-beaver-themer' ),
            '4' => __( 'Fifth', 'eventin-beaver-themer' ),
            '5' => __( 'Sixth', 'eventin-beaver-themer' ),
            '6' => __( 'Seventh', 'eventin-beaver-themer' ),
            '7' => __( 'Eighth', 'eventin-beaver-themer' ),
            '8' => __( 'Ninth', 'eventin-beaver-themer' ),
            '9' => __( 'Tenth', 'eventin-beaver-themer' ),
        ),
    ),
);
```

### Phase 3: New Beaver Builder Modules

#### Eventin Speaker Grid
Renders all event speakers as a styled grid. Uses `User_Model` to fetch each speaker's photo, name, designation, company, bio, social links.

Settings: event source, layout (grid/list), columns (2-4), show/hide toggles, photo size, bio length.

#### Eventin Organizer Card
Renders organizer info block. Uses `User_Model` for name, photo, email, phone, website, company, bio.

Settings: event source, layout (card/inline), show/hide toggles.

#### Eventin Ticket Pricing Table
Renders all ticket variations as pricing cards/table. Distinct from the existing Tickets module (purchase form).

Settings: event source, layout (table/cards/list), show/hide toggles, currency display.

#### Eventin FAQ Accordion
Renders FAQ items as an expand/collapse accordion or simple list.

Settings: event source, style (accordion/list), open first item toggle.

#### Eventin Social Links
Renders social media links as an icon bar. Uses Eventin's icon classes (`etn-icon fa-*`).

Settings: event source, style (icons/text/both), size.

#### Eventin Event Meta Block
Convenience bundle: date, time, venue, cost, organizer in one module.

Settings: show/hide sections, date/time format, currency toggle, icon style.

#### Eventin Location Map
Google Maps / OpenStreetMap embed using lat/lng from `etn_event_location`.

Settings: provider, height, zoom, API key, show venue name.

---

## 🏷️ Label Rewrite System (Cross-Cutting Concern)

### Problem

This plugin is replacing The Events Calendar across **multiple sites** we manage. TEC supported a built-in label rewrite feature so each site could use its own terminology:

| Site | Event | Speaker | Organizer | Ticket |
|---|---|---|---|---|
| **Default** | Event | Speaker | Organizer | Ticket |
| Baking classes site | Class | Instructor | Host | Registration |
| Conference site | Session | Presenter | Coordinator | Pass |
| Workshop site | Workshop | Facilitator | Host | Spot |
| Another site | Event | Speaker | Organizer | Ticket |

Some sites will keep the defaults entirely. The plugin must ship with generic Event/Speaker/Organizer/Ticket labels and let each site override only what it needs — without touching plugin code.

Our plugin needs the same per-site label customization capability.

### Scope of Labels to Rewrite

**Field connection labels** (in Beaver Builder's connection picker):
| Default Label | Rewritable To (example) |
|---|---|
| Event Start Date/Time | Class Start Date/Time |
| Event Speakers | Class Instructors |
| Speaker Name | Instructor Name |
| Speaker Photo | Instructor Photo |
| Speaker Title | Instructor Title |
| Speaker Company | Instructor Company |
| Speaker Bio | Instructor Bio |
| Event Organizers | Class Hosts |
| Organizer Name | Host Name |
| Ticket Price (from) | Registration Price (from) |
| Total Tickets | Total Spots |
| Tickets Sold | Spots Filled |
| Event Status | Class Status |
| Event Type | Class Type |
| Event Address | Class Location |
| Event Categories | Class Categories |
| Event Tags | Class Tags |

**Connection group name:**
| Default | Rewritable To (example) |
|---|---|
| Eventin | Classes (or keep Eventin) |

**Module names and descriptions:**
| Default | Rewritable To (example) |
|---|---|
| Eventin Event Tickets | Class Registrations |
| Eventin Speakers | Instructors |
| Eventin Events | Classes |
| Eventin Event Calendar | Class Calendar |
| Eventin Speaker Grid | Instructor Grid |

**Module category/group label:**
| Default | Rewritable To (example) |
|---|---|
| Eventin | Classes |

### Architecture: `Eventin_BT_Labels` Class

A centralized label dictionary with a WordPress filter for customization.

**New file:** `includes/class-eventin-bt-labels.php`

```php
final class Eventin_BT_Labels {

    /**
     * Default labels. Every user-facing string in the plugin goes through here.
     */
    private static $defaults = array(
        // Nouns (singular)
        'event'        => 'Event',
        'speaker'      => 'Speaker',
        'organizer'    => 'Organizer',
        'ticket'       => 'Ticket',
        'category'     => 'Category',
        'tag'          => 'Tag',
        'schedule'     => 'Schedule',
        'faq'          => 'FAQ',

        // Nouns (plural)
        'events'       => 'Events',
        'speakers'     => 'Speakers',
        'organizers'   => 'Organizers',
        'tickets'      => 'Tickets',
        'categories'   => 'Categories',
        'tags'         => 'Tags',
        'schedules'    => 'Schedules',
        'faqs'         => 'FAQs',

        // Compound labels
        'event_start_datetime'  => 'Event Start Date/Time',
        'event_end_datetime'    => 'Event End Date/Time',
        'event_start_date'      => 'Event Start Date',
        'event_end_date'        => 'Event End Date',
        'event_start_time'      => 'Event Start Time',
        'event_end_time'        => 'Event End Time',
        'event_status'          => 'Event Status',
        'event_type'            => 'Event Type',
        'event_address'         => 'Event Address',
        'event_timezone'        => 'Event Timezone',
        'event_logo'            => 'Event Logo',
        'event_banner'          => 'Event Banner',
        'event_categories'      => 'Event Categories',
        'event_tags'            => 'Event Tags',
        'event_organizers'      => 'Event Organizers',
        'event_speakers'        => 'Event Speakers',
        'ticket_price_from'     => 'Ticket Price (from)',
        'total_tickets'         => 'Total Tickets',
        'tickets_sold'          => 'Tickets Sold',
        'registration_deadline' => 'Registration Deadline',
        'meeting_link'          => 'Online/Meeting Link',
        'speaker_name'          => 'Speaker Name',
        'speaker_photo'         => 'Speaker Photo',
        'speaker_designation'   => 'Speaker Title/Role',
        'speaker_company'       => 'Speaker Company',
        'speaker_bio'           => 'Speaker Bio',
        'speaker_website'       => 'Speaker Website',
        'speaker_email'         => 'Speaker Email',
        'speaker_company_logo'  => 'Speaker Company Logo',
        'organizer_name'        => 'Organizer Name',
        'organizer_photo'       => 'Organizer Photo',
        'organizer_email'       => 'Organizer Email',
        'organizer_phone'       => 'Organizer Phone',
        'organizer_website'     => 'Organizer Website',
        'organizer_company'     => 'Organizer Company',
        'organizer_bio'         => 'Organizer Bio',
        'ticket_name'           => 'Ticket Name',
        'ticket_price'          => 'Ticket Price',
        'ticket_available'      => 'Available Tickets',
        'ticket_sold_item'      => 'Tickets Sold',
        'ticket_remaining'      => 'Remaining Tickets',
        'faq_question'          => 'FAQ Question',
        'faq_answer'            => 'FAQ Answer',

        // Group name
        'group_name'            => 'Eventin',

        // Module category
        'module_category'       => 'Eventin',
        'module_group'          => 'Eventin',
    );

    /**
     * Get a label, applying the site's custom overrides.
     *
     * Usage in field connection registration:
     *   'label' => Eventin_BT_Labels::get( 'event_speakers' )
     *
     * Usage with sprintf for dynamic labels:
     *   sprintf( Eventin_BT_Labels::get( 'speaker_name' ) )
     *
     * @param string $key Label key.
     * @return string
     */
    public static function get( $key ) {
        $labels = apply_filters( 'eventin_bt_labels', self::$defaults );
        return isset( $labels[ $key ] ) ? $labels[ $key ] : $key;
    }

    /**
     * Build a compound label from noun parts.
     *
     * For labels not in the static dictionary:
     *   Eventin_BT_Labels::compound( 'speaker', 'Name' )
     *   → "Instructor Name" (if speaker → Instructor)
     *
     * @param string $noun_key  Singular noun key (e.g. 'speaker').
     * @param string $suffix    Label suffix (e.g. 'Name', 'Photo').
     * @return string
     */
    public static function compound( $noun_key, $suffix ) {
        return self::get( $noun_key ) . ' ' . $suffix;
    }
}
```

### How Sites Customize Labels

Each site adds its own filter in a site-specific plugin or the theme's `functions.php`. Sites that want the defaults add nothing.

**Example: Baking classes site**
```php
add_filter( 'eventin_bt_labels', function( $labels ) {
    $labels['event']       = 'Class';
    $labels['events']      = 'Classes';
    $labels['speaker']     = 'Instructor';
    $labels['speakers']    = 'Instructors';
    $labels['organizer']   = 'Host';
    $labels['organizers']  = 'Hosts';
    $labels['ticket']      = 'Registration';
    $labels['tickets']     = 'Registrations';
    $labels['group_name']      = 'Classes';
    $labels['module_category'] = 'Classes';
    $labels['module_group']    = 'Classes';
    return $labels;
});
```

**Example: Conference site**
```php
add_filter( 'eventin_bt_labels', function( $labels ) {
    $labels['event']       = 'Session';
    $labels['events']      = 'Sessions';
    $labels['speaker']     = 'Presenter';
    $labels['speakers']    = 'Presenters';
    $labels['ticket']      = 'Pass';
    $labels['tickets']     = 'Passes';
    $labels['group_name']      = 'Conference';
    $labels['module_category'] = 'Conference';
    $labels['module_group']    = 'Conference';
    return $labels;
});
```

**No filter = defaults.** Sites that don't add the filter see "Event", "Speaker", "Organizer", "Ticket" — the standard Eventin terminology.

**The `eventin_bt_labels` filter still works** for developer-level overrides (e.g., in a site-specific plugin or theme `functions.php`). The filter runs after the saved option, so code overrides always win over the admin UI.

### Admin Settings Page

A WordPress admin page where site admins configure labels through a UI — no PHP required.

**Menu location:** Settings → Eventin Beaver Themer (under the Settings menu, not its own top-level item, to stay lightweight).

**New file:** `includes/class-eventin-bt-settings.php`

```php
final class Eventin_BT_Settings {

    const OPTION_KEY = 'eventin_bt_settings';

    public static function init() {
        add_action( 'admin_menu', array( __CLASS__, 'add_menu' ) );
        add_action( 'admin_init', array( __CLASS__, 'register_settings' ) );
    }

    public static function add_menu() {
        add_options_page(
            __( 'Eventin Beaver Themer', 'eventin-beaver-themer' ),
            __( 'Eventin Beaver Themer', 'eventin-beaver-themer' ),
            'manage_options',
            'eventin-beaver-themer',
            array( __CLASS__, 'render_page' )
        );
    }

    public static function register_settings() {
        register_setting( 'eventin_bt_settings_group', self::OPTION_KEY, array(
            'sanitize_callback' => array( __CLASS__, 'sanitize' ),
        ) );

        add_settings_section(
            'eventin_bt_labels_section',
            __( 'Label Customization', 'eventin-beaver-themer' ),
            array( __CLASS__, 'section_description' ),
            'eventin-beaver-themer'
        );

        // Each noun gets singular + plural fields
        $nouns = array(
            'event'     => array( 'Event',     'Events' ),
            'speaker'   => array( 'Speaker',   'Speakers' ),
            'organizer' => array( 'Organizer', 'Organizers' ),
            'ticket'    => array( 'Ticket',    'Tickets' ),
            'category'  => array( 'Category',  'Categories' ),
            'tag'       => array( 'Tag',       'Tags' ),
        );

        foreach ( $nouns as $key => list( $default_singular, $default_plural ) ) {
            add_settings_field(
                "eventin_bt_label_{$key}_singular",
                /* translators: %s: default label */
                sprintf( __( '%s (singular)', 'eventin-beaver-themer' ), $default_singular ),
                array( __CLASS__, 'render_text_field' ),
                'eventin-beaver-themer',
                'eventin_bt_labels_section',
                array(
                    'key'         => $key,
                    'sub_key'     => 'singular',
                    'placeholder' => $default_singular,
                )
            );

            add_settings_field(
                "eventin_bt_label_{$key}_plural",
                sprintf( __( '%s (plural)', 'eventin-beaver-themer' ), $default_plural ),
                array( __CLASS__, 'render_text_field' ),
                'eventin-beaver-themer',
                'eventin_bt_labels_section',
                array(
                    'key'         => $key,
                    'sub_key'     => 'plural',
                    'placeholder' => $default_plural,
                )
            );
        }

        // Group/category label
        add_settings_field(
            'eventin_bt_label_group',
            __( 'Connection Group Name', 'eventin-beaver-themer' ),
            array( __CLASS__, 'render_text_field' ),
            'eventin-beaver-themer',
            'eventin_bt_labels_section',
            array(
                'key'         => 'group_name',
                'placeholder' => 'Eventin',
                'description' => __( 'The group label shown in Beaver Builder\'s field connection picker.', 'eventin-beaver-themer' ),
            )
        );
    }

    public static function section_description() {
        echo '<p>' . esc_html__(
            'Customize the terminology used in Beaver Builder field connections and modules. '
            . 'Leave a field blank to use the default. For example, change "Event" to "Class" '
            . 'and "Speaker" to "Instructor" for a training site.',
            'eventin-beaver-themer'
        ) . '</p>';
    }

    public static function render_text_field( $args ) {
        $options = get_option( self::OPTION_KEY, array() );
        $key     = $args['key'];
        $value   = '';

        if ( isset( $args['sub_key'] ) ) {
            $value = isset( $options[ $key ][ $args['sub_key'] ] )
                ? $options[ $key ][ $args['sub_key'] ] : '';
        } else {
            $value = isset( $options[ $key ] ) ? $options[ $key ] : '';
        }

        printf(
            '<input type="text" name="%s[%s]%s" value="%s" '
            . 'placeholder="%s" class="regular-text" />',
            esc_attr( self::OPTION_KEY ),
            esc_attr( $key ),
            isset( $args['sub_key'] ) ? '[' . esc_attr( $args['sub_key'] ) . ']' : '',
            esc_attr( $value ),
            esc_attr( $args['placeholder'] )
        );

        if ( ! empty( $args['description'] ) ) {
            printf( '<p class="description">%s</p>', esc_html( $args['description'] ) );
        }
    }

    public static function sanitize( $input ) {
        $clean = array();
        foreach ( (array) $input as $key => $value ) {
            if ( is_array( $value ) ) {
                $clean[ $key ] = array_map( 'sanitize_text_field', $value );
            } else {
                $clean[ $key ] = sanitize_text_field( $value );
            }
        }
        return $clean;
    }

    public static function render_page() {
        echo '<div class="wrap">';
        echo '<h1>' . esc_html__( 'Eventin Beaver Themer Settings', 'eventin-beaver-themer' ) . '</h1>';
        echo '<form method="post" action="options.php">';
        settings_fields( 'eventin_bt_settings_group' );
        do_settings_sections( 'eventin-beaver-themer' );
        submit_button();
        echo '</form>';
        echo '</div>';
    }

    /**
     * Read saved settings and merge into the label dictionary.
     *
     * Called by Eventin_BT_Labels at priority 5 (before the
     * developer filter at default priority 10).
     *
     * @param array $labels Default labels.
     * @return array
     */
    public static function apply_saved_labels( $labels ) {
        $saved = get_option( self::OPTION_KEY, array() );

        if ( empty( $saved ) || ! is_array( $saved ) ) {
            return $labels;
        }

        $nouns = array( 'event', 'speaker', 'organizer', 'ticket', 'category', 'tag' );

        foreach ( $nouns as $noun ) {
            if ( ! empty( $saved[ $noun ]['singular'] ) ) {
                $labels[ $noun ] = $saved[ $noun ]['singular'];
            }
            if ( ! empty( $saved[ $noun ]['plural'] ) ) {
                $labels[ $noun . 's' ] = $saved[ $noun ]['plural'];
            }
        }

        if ( ! empty( $saved['group_name'] ) ) {
            $labels['group_name']      = $saved['group_name'];
            $labels['module_category'] = $saved['group_name'];
            $labels['module_group']    = $saved['group_name'];
        }

        return $labels;
    }
}
```

**Settings page UI:**

```
Settings → Eventin Beaver Themer
─────────────────────────────────────────
Label Customization

Customize the terminology used in Beaver Builder field connections
and modules. Leave a field blank to use the default.

 Event (singular)       [ Class          ]
 Events (plural)        [ Classes        ]
 Speaker (singular)     [ Instructor     ]
 Speakers (plural)      [ Instructors    ]
 Organizer (singular)   [ Host           ]
 Organizers (plural)    [ Hosts          ]
 Ticket (singular)      [ Registration   ]
 Tickets (plural)       [ Registrations  ]
 Category (singular)    [                ]   ← blank = default
 Tags (plural)          [                ]   ← blank = default
 Connection Group Name  [ Classes        ]

                          [ Save Changes ]
```

**How saved labels flow into `Eventin_BT_Labels`:**

```php
// In Eventin_BT_Labels::get():
public static function get( $key ) {
    $labels = apply_filters( 'eventin_bt_labels', self::$defaults );
    return isset( $labels[ $key ] ) ? $labels[ $key ] : $key;
}

// In Eventin_BT_Settings::init():
add_filter( 'eventin_bt_labels', array( __CLASS__, 'apply_saved_labels' ), 5 );
```

**Override priority:** Defaults (lowest) → Admin UI saved values (priority 5) → Developer `add_filter` in theme/plugin (priority 10, highest). This means:
- Sites with no config see defaults
- Sites that use the admin UI get their custom labels
- Developers can still override via filter for advanced cases (e.g., conditional labels per post type)

### What Changes Across the Codebase

**`page-data-eventin.php`** — Every `FLPageData::add_post_property()` call replaces hard-coded labels:
```php
// Before:
'label'  => __( 'Event Start Date/Time', 'eventin-beaver-themer' ),

// After:
'label'  => Eventin_BT_Labels::get( 'event_start_datetime' ),
```

The group name also becomes dynamic:
```php
// Before:
FLPageData::add_group( 'eventin', array(
    'label' => __( 'Eventin', 'eventin-beaver-themer' ),
) );

// After:
FLPageData::add_group( 'eventin', array(
    'label' => Eventin_BT_Labels::get( 'group_name' ),
) );
```

**Module files** — Each module's `name`, `description`, `category`, and `group` use labels:
```php
// Before:
'name'     => __( 'Eventin Event Tickets', 'eventin-beaver-themer' ),
'category' => __( 'Eventin', 'eventin-beaver-themer' ),
'group'    => __( 'Eventin', 'eventin-beaver-themer' ),

// After:
'name'     => Eventin_BT_Labels::compound( 'event', 'Tickets' ),
'category' => Eventin_BT_Labels::get( 'module_category' ),
'group'    => Eventin_BT_Labels::get( 'module_group' ),
```

**Module settings labels** — Field labels like `__( 'Event', ... )` and `__( 'Current Event', ... )` use `Eventin_BT_Labels::get('event')` etc.

> **Important:** The filter runs at registration time (`fl_page_data_add_properties` / `init`), so label overrides must be registered before that point. The filter approach (not an options lookup at render time) keeps this efficient.

### Label Rewrite Affects All Phases

Every phase from P1 onward must use `Eventin_BT_Labels::get()` instead of hard-coded strings. This means:
- **Phase 0 (bug fix):** No label changes needed — the existing code already uses `__()` which we'll migrate later.
- **Phase 1 (scalar connections):** New getters register labels via `Eventin_BT_Labels::get()`.
- **Phase 2 (indexed connections):** Same pattern.
- **Phase 3 (modules):** All module names/settings use labels.
- **Existing connections:** Retrofit `page-data-eventin.php` to use `Eventin_BT_Labels::get()` instead of `__()`.

> **Migration note for existing connections:** Changing labels on already-saved connections doesn't break them — the connection ID (`eventin_ticket_price`) stays the same; only the display label in the BB picker changes.

---

## Implementation Order

| Priority | Phase | Effort | Impact |
|---|---|---|---|
| 🔴 P0 | Bug fix: speakers/organizers | Low | Critical — existing connections are broken |
| 🔴 P0.5 | Label infrastructure + settings page | Low | Foundation — all new code uses it |
| 🟠 P1 | Scalar field connections (~16 new) | Low | High — fills flat data gaps |
| 🟡 P2 | Indexed field connections (~22 new) | Medium | High — per-speaker/organizer/ticket/FAQ |
| 🟢 P3 | New BB modules (7 new) | Higher | Polish — rich rendered blocks |
| ⚪ P4 | Archive & loop enhancements | Low | Lower — archive page context |

**P0.5 (Label Infrastructure) is the foundation phase** — create `Eventin_BT_Labels`, `Eventin_BT_Settings` (admin page + saved option), retrofit `page-data-eventin.php` and existing modules, so all subsequent phases build on the label system from day one.

---

## File Structure After Implementation

```
includes/
  class-eventin-bt-labels.php         NEW — centralized label dictionary + filter
  class-eventin-bt-settings.php       NEW — admin settings page (Settings → Eventin Beaver Themer)
  class-eventin-bt-page-data.php     ~800 lines (from 488: +bug fix, +35 getters, +helpers)
  page-data-eventin.php               ~600 lines (from 283: +38 property registrations, all labels via Eventin_BT_Labels)
  class-eventin-bt-modules.php        ~115 lines (+7 module slugs)
modules/
  eventin-events/                     (existing)
  eventin-events-tab/                 (existing)
  eventin-calendar/                   (existing)
  eventin-search/                     (existing)
  eventin-speakers/                   (existing — global speaker list)
  eventin-schedule/                   (existing)
  eventin-tickets/                    (existing — purchase form)
  eventin-speaker-grid/               NEW — per-event speaker grid
  eventin-organizer/                  NEW — per-event organizer card
  eventin-ticket-table/               NEW — per-event pricing table
  eventin-faq/                        NEW — FAQ accordion
  eventin-social-links/               NEW — social icon bar
  eventin-event-meta/                 NEW — bundled event details block
  eventin-location-map/               NEW — map embed
```

---

## Testing Checklist

- [ ] Event with multiple speakers (verify User_Model resolves correctly)
- [ ] Event with multiple organizers
- [ ] Event with multiple ticket variations (verify integer prices format correctly)
- [ ] Event with location data (verify lat/lng extraction from object)
- [ ] Event with FAQ items
- [ ] Event with social links
- [ ] Online event (meeting link, location_type = online)
- [ ] Event with no speakers, no tickets, no location (edge cases)
- [ ] Unlimited tickets scenario
- [ ] Recurring event
- [ ] All connections inside BB Posts module loop on archive layout
- [ ] Layout preview mode in BB editor
- [ ] **Label rewrite (admin UI):** change labels in Settings → Eventin Beaver Themer, verify all BB connection labels, module names, and settings update
- [ ] **Label rewrite (filter):** verify `add_filter('eventin_bt_labels', ...)` overrides the admin UI values
- [ ] **Label rewrite (defaults):** verify a site with no settings and no filter shows standard Event/Speaker/Organizer/Ticket labels
- [ ] **Label rewrite:** verify connections still work after label change (IDs don't change, only display labels)
