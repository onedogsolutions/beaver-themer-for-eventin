<?php
/**
 * Centralized label dictionary for the plugin.
 *
 * Every user-facing string in field connections and modules goes through
 * this class so each site can customize its terminology (e.g. "Class"
 * instead of "Event", "Instructor" instead of "Speaker") via the
 * WordPress admin settings page or the `eventin_bt_labels` filter.
 *
 * @package Eventin_Beaver_Themer
 */

defined( 'ABSPATH' ) || exit;

/**
 * Resolves labels with a three-tier override chain:
 *
 *  1. Hard-coded defaults (lowest priority).
 *  2. Saved admin settings from `Eventin_BT_Settings` (priority 5).
 *  3. Developer `add_filter( 'eventin_bt_labels', ... )` (priority 10).
 */
final class Eventin_BT_Labels {

	/**
	 * Default labels.
	 *
	 * @var array
	 */
	private static $defaults = array(
		// Nouns (singular).
		'event'     => 'Event',
		'speaker'   => 'Speaker',
		'organizer' => 'Organizer',
		'ticket'    => 'Ticket',
		'category'  => 'Category',
		'tag'       => 'Tag',
		'schedule'  => 'Schedule',
		'faq'       => 'FAQ',

		// Nouns (plural).
		'events'     => 'Events',
		'speakers'   => 'Speakers',
		'organizers' => 'Organizers',
		'tickets'    => 'Tickets',
		'categories' => 'Categories',
		'tags'       => 'Tags',
		'schedules'  => 'Schedules',
		'faqs'       => 'FAQs',

		// Compound labels — event.
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
		'registration_deadline' => 'Registration Deadline',
		'meeting_link'          => 'Online/Meeting Link',

		// Compound labels — tickets.
		'ticket_price_from' => 'Ticket Price (from)',
		'total_tickets'     => 'Total Tickets',
		'tickets_sold'      => 'Tickets Sold',

		// Compound labels — location.
		'latitude'       => 'Latitude',
		'longitude'      => 'Longitude',
		'map_url'        => 'Map URL',
		'external_link'  => 'External Link',
		'location_type'  => 'Location Type',

		// Compound labels — misc.
		'event_url'          => 'Event URL',
		'is_recurring'       => 'Recurring Event',
		'remaining_tickets'  => 'Remaining Tickets',
		'max_ticket_price'   => 'Ticket Price (max)',
		'ticket_price_range' => 'Ticket Price Range',
		'ticket_count'       => 'Number of Ticket Types',
		'speaker_count'      => 'Number of Speakers',
		'organizer_count'    => 'Number of Organizers',

		// Compound labels — speaker (indexed).
		'speaker_name'         => 'Speaker Name',
		'speaker_photo'        => 'Speaker Photo',
		'speaker_designation'  => 'Speaker Title/Role',
		'speaker_company'      => 'Speaker Company',
		'speaker_bio'          => 'Speaker Bio',
		'speaker_website'      => 'Speaker Website',
		'speaker_email'        => 'Speaker Email',
		'speaker_company_logo' => 'Speaker Company Logo',

		// Compound labels — organizer (indexed).
		'organizer_name'    => 'Organizer Name',
		'organizer_photo'   => 'Organizer Photo',
		'organizer_email'   => 'Organizer Email',
		'organizer_phone'   => 'Organizer Phone',
		'organizer_website' => 'Organizer Website',
		'organizer_company' => 'Organizer Company',
		'organizer_bio'     => 'Organizer Bio',

		// Compound labels — ticket (indexed).
		'ticket_name'      => 'Ticket Name',
		'ticket_price'     => 'Ticket Price',
		'ticket_available' => 'Available Tickets',
		'ticket_sold_item' => 'Tickets Sold',
		'ticket_remaining' => 'Remaining Tickets',

		// Compound labels — FAQ (indexed).
		'faq_question' => 'FAQ Question',
		'faq_answer'   => 'FAQ Answer',

		// UI labels.
		'group_name'      => 'Eventin',
		'module_category' => 'Eventin',
		'module_group'    => 'Eventin',

		// Settings field labels.
		'show_currency' => 'Show Currency',
		'format'        => 'Format',
		'item_selector' => 'Item',
	);

	/**
	 * Cached merged labels (defaults + overrides).
	 *
	 * @var array|null
	 */
	private static $cache = null;

	/**
	 * Get a label, applying custom overrides.
	 *
	 * @param string $key Label key.
	 *
	 * @return string
	 */
	public static function get( $key ) {
		if ( null === self::$cache ) {
			self::$cache = apply_filters( 'eventin_bt_labels', self::$defaults );
		}

		return isset( self::$cache[ $key ] ) ? self::$cache[ $key ] : $key;
	}

	/**
	 * Build a compound label from a noun key and a suffix.
	 *
	 * Example: `compound( 'speaker', 'Name' )` → "Instructor Name"
	 * when the speaker noun has been overridden to "Instructor".
	 *
	 * @param string $noun_key Singular noun key (e.g. 'speaker').
	 * @param string $suffix   Label suffix (e.g. 'Name', 'Photo').
	 *
	 * @return string
	 */
	public static function compound( $noun_key, $suffix ) {
		return self::get( $noun_key ) . ' ' . $suffix;
	}

	/**
	 * Clear the label cache.
	 *
	 * Called after settings are saved so the next `get()` call picks
	 * up the new values.
	 *
	 * @return void
	 */
	public static function flush() {
		self::$cache = null;
	}
}
