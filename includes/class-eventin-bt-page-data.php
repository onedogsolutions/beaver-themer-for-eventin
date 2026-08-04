<?php
/**
 * Field connection getters for Eventin events.
 *
 * @package Eventin_Beaver_Themer
 */

defined( 'ABSPATH' ) || exit;

/**
 * Resolves Beaver Themer field connections to Eventin event data.
 *
 * Every getter receives the connection's saved settings object and reads the
 * current event in the loop, so the same connection works on a single event
 * layout and inside a Posts module on an event archive.
 */
final class Eventin_BT_Page_Data {

	/**
	 * Build an Event_Model for the current (or given) post.
	 *
	 * @param int|null $post_id Optional explicit post ID.
	 *
	 * @return \Etn\Core\Event\Event_Model|null
	 */
	private static function event( $post_id = null ) {
		$post_id = $post_id ? $post_id : get_the_ID();

		if ( ! $post_id || 'etn' !== get_post_type( $post_id ) || ! class_exists( 'Etn\\Core\\Event\\Event_Model' ) ) {
			return null;
		}

		return new \Etn\Core\Event\Event_Model( $post_id );
	}

	/**
	 * Resolve a date/time format from a connection's settings.
	 *
	 * @param object $settings Connection settings.
	 * @param string $fallback Format to use when none was supplied.
	 *
	 * @return string
	 */
	private static function format( $settings, $fallback ) {
		return ( isset( $settings->format ) && '' !== $settings->format ) ? $settings->format : $fallback;
	}

	/**
	 * Event start date and time.
	 *
	 * @param object $settings Connection settings.
	 *
	 * @return string
	 */
	public static function start_datetime( $settings ) {
		$event  = self::event();
		$format = self::format( $settings, get_option( 'date_format' ) . ' ' . get_option( 'time_format' ) );

		return $event ? self::safe( array( $event, 'get_start_datetime' ), $format ) : '';
	}

	/**
	 * Event end date and time.
	 *
	 * @param object $settings Connection settings.
	 *
	 * @return string
	 */
	public static function end_datetime( $settings ) {
		$event  = self::event();
		$format = self::format( $settings, get_option( 'date_format' ) . ' ' . get_option( 'time_format' ) );

		return $event ? self::safe( array( $event, 'get_end_datetime' ), $format ) : '';
	}

	/**
	 * Event start date.
	 *
	 * @param object $settings Connection settings.
	 *
	 * @return string
	 */
	public static function start_date( $settings ) {
		$event  = self::event();
		$format = self::format( $settings, get_option( 'date_format' ) );

		return $event ? self::safe( array( $event, 'get_start_date' ), $format ) : '';
	}

	/**
	 * Event start time.
	 *
	 * @param object $settings Connection settings.
	 *
	 * @return string
	 */
	public static function start_time( $settings ) {
		$event  = self::event();
		$format = self::format( $settings, get_option( 'time_format' ) );

		return $event ? self::safe( array( $event, 'get_start_time' ), $format ) : '';
	}

	/**
	 * Event end date.
	 *
	 * @param object $settings Connection settings.
	 *
	 * @return string
	 */
	public static function end_date( $settings ) {
		$event  = self::event();
		$format = self::format( $settings, get_option( 'date_format' ) );

		return $event ? self::safe( array( $event, 'get_end_date' ), $format ) : '';
	}

	/**
	 * Event end time.
	 *
	 * @param object $settings Connection settings.
	 *
	 * @return string
	 */
	public static function end_time( $settings ) {
		$event  = self::event();
		$format = self::format( $settings, get_option( 'time_format' ) );

		return $event ? self::safe( array( $event, 'get_end_time' ), $format ) : '';
	}

	/**
	 * Registration deadline.
	 *
	 * @param object $settings Connection settings.
	 *
	 * @return string
	 */
	public static function registration_deadline( $settings ) {
		$event = self::event();

		if ( ! $event ) {
			return '';
		}

		try {
			$deadline = $event->etn_registration_deadline;
		} catch ( \Exception $e ) {
			return '';
		}

		if ( empty( $deadline ) ) {
			return '';
		}

		$timestamp = strtotime( $deadline );

		if ( false === $timestamp ) {
			return (string) $deadline;
		}

		$format = self::format( $settings, get_option( 'date_format' ) . ' ' . get_option( 'time_format' ) );

		return date_i18n( $format, $timestamp );
	}

	/**
	 * Event timezone.
	 *
	 * @return string
	 */
	public static function timezone() {
		$event = self::event();

		if ( ! $event ) {
			return '';
		}

		$timezone = self::safe( array( $event, 'get_timezone' ) );

		if ( $timezone instanceof \DateTimeZone ) {
			return $timezone->getName();
		}

		return is_string( $timezone ) ? $timezone : '';
	}

	/**
	 * Event status (Upcoming / Ongoing / Expired).
	 *
	 * @return string
	 */
	public static function status() {
		$event = self::event();

		if ( ! $event ) {
			return '';
		}

		$status = self::safe( array( $event, 'get_status' ) );

		// get_status() returns an array ( key/value ) for published events.
		if ( is_array( $status ) ) {
			return isset( $status['value'] ) ? (string) $status['value'] : '';
		}

		return is_string( $status ) ? $status : '';
	}

	/**
	 * Event type (online / offline / hybrid).
	 *
	 * @return string
	 */
	public static function event_type() {
		$event = self::event();

		if ( ! $event ) {
			return '';
		}

		try {
			$type = $event->event_type;
		} catch ( \Exception $e ) {
			return '';
		}

		return $type ? ucfirst( (string) $type ) : '';
	}

	/**
	 * Event location / full address.
	 *
	 * @return string
	 */
	public static function address() {
		$event = self::event();

		return $event ? (string) self::safe( array( $event, 'get_address' ) ) : '';
	}

	/**
	 * Online meeting / external link URL.
	 *
	 * @return string
	 */
	public static function meeting_link() {
		$event = self::event();

		if ( ! $event ) {
			return '';
		}

		foreach ( array( 'meeting_link', 'external_link' ) as $key ) {
			try {
				$value = $event->{$key};
			} catch ( \Exception $e ) {
				$value = '';
			}

			if ( ! empty( $value ) ) {
				return esc_url( $value );
			}
		}

		return '';
	}

	/**
	 * Comma separated organizer names.
	 *
	 * @return string
	 */
	public static function organizers() {
		$names = array();

		foreach ( self::organizer_ids() as $id ) {
			$model = self::user_model( $id );

			if ( $model ) {
				$name = $model->get_speaker_title();

				if ( ! empty( $name ) ) {
					$names[] = $name;
				}
			}
		}

		return implode( ', ', $names );
	}

	/**
	 * Comma separated speaker names.
	 *
	 * @return string
	 */
	public static function speakers() {
		$names = array();

		foreach ( self::speaker_ids() as $id ) {
			$model = self::user_model( $id );

			if ( $model ) {
				$name = $model->get_speaker_title();

				if ( ! empty( $name ) ) {
					$names[] = $name;
				}
			}
		}

		return implode( ', ', $names );
	}

	/**
	 * Comma separated category names.
	 *
	 * @return string
	 */
	public static function categories() {
		$event = self::event();

		return $event ? self::term_names( self::safe( array( $event, 'get_categories' ) ) ) : '';
	}

	/**
	 * Comma separated tag names.
	 *
	 * @return string
	 */
	public static function tags() {
		$event = self::event();

		return $event ? self::term_names( self::safe( array( $event, 'get_tags' ) ) ) : '';
	}

	/**
	 * Ticket price for the cheapest ticket, optionally with currency.
	 *
	 * @param object $settings Connection settings.
	 *
	 * @return string
	 */
	public static function ticket_price( $settings ) {
		$event = self::event();

		if ( ! $event ) {
			return '';
		}

		$variations = self::safe( array( $event, 'get_ticket' ) );

		if ( empty( $variations ) || ! is_array( $variations ) ) {
			return '';
		}

		$prices = array();

		foreach ( $variations as $variation ) {
			if ( isset( $variation['etn_ticket_price'] ) && is_numeric( $variation['etn_ticket_price'] ) ) {
				$prices[] = (float) $variation['etn_ticket_price'];
			}
		}

		if ( empty( $prices ) ) {
			return '';
		}

		$price          = min( $prices );
		$with_currency  = ! ( isset( $settings->show_currency ) && '0' === (string) $settings->show_currency );

		if ( $with_currency && class_exists( 'Etn\\Core\\Event\\Helper' ) && method_exists( 'Etn\\Core\\Event\\Helper', 'instance' ) ) {
			return \Etn\Core\Event\Helper::instance()->currency_with_position( $price );
		}

		return (string) $price;
	}

	/**
	 * Total number of tickets (-1 means unlimited).
	 *
	 * @return string
	 */
	public static function total_tickets() {
		$event = self::event();

		if ( ! $event ) {
			return '';
		}

		$total = self::safe( array( $event, 'get_total_ticket' ) );

		if ( -1 === $total ) {
			return __( 'Unlimited', 'eventin-beaver-themer' );
		}

		return is_numeric( $total ) ? (string) $total : '';
	}

	/**
	 * Number of tickets sold.
	 *
	 * @return string
	 */
	public static function tickets_sold() {
		$event = self::event();

		return $event ? (string) self::safe( array( $event, 'get_total_sold_ticket' ) ) : '';
	}

	/**
	 * Event logo attachment ID (for photo connections).
	 *
	 * @return int|string
	 */
	public static function logo_id() {
		return self::attachment_id( 'event_logo_id' );
	}

	/**
	 * Event banner attachment ID (for photo connections).
	 *
	 * @return int|string
	 */
	public static function banner_id() {
		return self::attachment_id( 'event_banner_id' );
	}

	/**
	 * Read an attachment-ID meta value from the current event.
	 *
	 * @param string $key Meta key.
	 *
	 * @return int|string
	 */
	private static function attachment_id( $key ) {
		$event = self::event();

		if ( ! $event ) {
			return '';
		}

		try {
			$id = $event->{$key};
		} catch ( \Exception $e ) {
			return '';
		}

		return ( $id && is_numeric( $id ) ) ? (int) $id : '';
	}

	/*
	 * ---------------------------------------------------------------
	 * Location helpers
	 * ---------------------------------------------------------------
	 */

	/**
	 * Read the etn_event_location array from the current event.
	 *
	 * @return array Empty array when not available.
	 */
	private static function location_data() {
		$event = self::event();

		if ( ! $event ) {
			return array();
		}

		try {
			$location = $event->etn_event_location;
		} catch ( \Exception $e ) {
			return array();
		}

		return is_array( $location ) ? $location : array();
	}

	/**
	 * Latitude from the event location.
	 *
	 * @return string
	 */
	public static function latitude() {
		$location = self::location_data();

		return isset( $location['latitude'] ) ? (string) $location['latitude'] : '';
	}

	/**
	 * Longitude from the event location.
	 *
	 * @return string
	 */
	public static function longitude() {
		$location = self::location_data();

		return isset( $location['longitude'] ) ? (string) $location['longitude'] : '';
	}

	/**
	 * Google Maps embed URL computed from lat/lng or address.
	 *
	 * @return string
	 */
	public static function map_url() {
		$location = self::location_data();

		if ( ! empty( $location['latitude'] ) && ! empty( $location['longitude'] ) ) {
			return sprintf(
				'https://maps.google.com/maps?q=%s,%s&z=15&output=embed',
				$location['latitude'],
				$location['longitude']
			);
		}

		$address = self::address();

		if ( ! empty( $address ) ) {
			return 'https://maps.google.com/maps?q=' . rawurlencode( $address ) . '&z=15&output=embed';
		}

		return '';
	}

	/**
	 * Location type (venue / online).
	 *
	 * @return string
	 */
	public static function location_type() {
		$event = self::event();

		if ( ! $event ) {
			return '';
		}

		try {
			$type = $event->etn_event_location_type;
		} catch ( \Exception $e ) {
			return '';
		}

		return $type ? ucfirst( (string) $type ) : '';
	}

	/**
	 * External event link URL.
	 *
	 * @return string
	 */
	public static function external_link() {
		$event = self::event();

		if ( ! $event ) {
			return '';
		}

		try {
			$link = $event->external_link;
		} catch ( \Exception $e ) {
			return '';
		}

		return ! empty( $link ) ? esc_url( $link ) : '';
	}

	/*
	 * ---------------------------------------------------------------
	 * Ticket helpers (aggregate)
	 * ---------------------------------------------------------------
	 */

	/**
	 * Highest ticket price, optionally with currency.
	 *
	 * @param object $settings Connection settings.
	 *
	 * @return string
	 */
	public static function max_ticket_price( $settings ) {
		$event = self::event();

		if ( ! $event ) {
			return '';
		}

		$variations = self::safe( array( $event, 'get_ticket' ) );

		if ( empty( $variations ) || ! is_array( $variations ) ) {
			return '';
		}

		$prices = array();

		foreach ( $variations as $variation ) {
			if ( isset( $variation['etn_ticket_price'] ) && is_numeric( $variation['etn_ticket_price'] ) ) {
				$prices[] = (float) $variation['etn_ticket_price'];
			}
		}

		if ( empty( $prices ) ) {
			return '';
		}

		$price         = max( $prices );
		$with_currency = ! ( isset( $settings->show_currency ) && '0' === (string) $settings->show_currency );

		if ( $with_currency && class_exists( 'Etn\\Core\\Event\\Helper' ) && method_exists( 'Etn\\Core\\Event\\Helper', 'instance' ) ) {
			return \Etn\Core\Event\Helper::instance()->currency_with_position( $price );
		}

		return (string) $price;
	}

	/**
	 * Ticket price range formatted as "From $X to $Y".
	 *
	 * @param object $settings Connection settings.
	 *
	 * @return string
	 */
	public static function ticket_price_range( $settings ) {
		$event = self::event();

		if ( ! $event ) {
			return '';
		}

		$variations = self::safe( array( $event, 'get_ticket' ) );

		if ( empty( $variations ) || ! is_array( $variations ) ) {
			return '';
		}

		$prices = array();

		foreach ( $variations as $variation ) {
			if ( isset( $variation['etn_ticket_price'] ) && is_numeric( $variation['etn_ticket_price'] ) ) {
				$prices[] = (float) $variation['etn_ticket_price'];
			}
		}

		if ( empty( $prices ) ) {
			return '';
		}

		$min           = min( $prices );
		$max           = max( $prices );
		$with_currency = ! ( isset( $settings->show_currency ) && '0' === (string) $settings->show_currency );

		if ( $min === $max ) {
			$display = $min;

			if ( $with_currency && class_exists( 'Etn\\Core\\Event\\Helper' ) && method_exists( 'Etn\\Core\\Event\\Helper', 'instance' ) ) {
				return \Etn\Core\Event\Helper::instance()->currency_with_position( $display );
			}

			return (string) $display;
		}

		if ( $with_currency && class_exists( 'Etn\\Core\\Event\\Helper' ) && method_exists( 'Etn\\Core\\Event\\Helper', 'instance' ) ) {
			$helper = \Etn\Core\Event\Helper::instance();

			return sprintf(
				/* translators: 1: minimum price, 2: maximum price */
				__( 'From %1$s to %2$s', 'eventin-beaver-themer' ),
				$helper->currency_with_position( $min ),
				$helper->currency_with_position( $max )
			);
		}

		return sprintf(
			/* translators: 1: minimum price, 2: maximum price */
			__( 'From %1$s to %2$s', 'eventin-beaver-themer' ),
			$min,
			$max
		);
	}

	/**
	 * Number of ticket variations.
	 *
	 * @return string
	 */
	public static function ticket_count() {
		$event = self::event();

		if ( ! $event ) {
			return '';
		}

		$variations = self::safe( array( $event, 'get_ticket' ) );

		return is_array( $variations ) ? (string) count( $variations ) : '0';
	}

	/**
	 * Remaining tickets (total minus sold).
	 *
	 * @return string
	 */
	public static function remaining_tickets() {
		$event = self::event();

		if ( ! $event ) {
			return '';
		}

		$total = self::safe( array( $event, 'get_total_ticket' ) );
		$sold  = self::safe( array( $event, 'get_total_sold_ticket' ) );

		if ( ! is_numeric( $total ) || ! is_numeric( $sold ) ) {
			return '';
		}

		if ( -1 === (int) $total ) {
			return __( 'Unlimited', 'eventin-beaver-themer' );
		}

		return (string) max( 0, (int) $total - (int) $sold );
	}

	/*
	 * ---------------------------------------------------------------
	 * Misc scalar getters
	 * ---------------------------------------------------------------
	 */

	/**
	 * Whether the event is recurring.
	 *
	 * @return string
	 */
	public static function is_recurring() {
		$event = self::event();

		if ( ! $event ) {
			return '';
		}

		try {
			$value = $event->recurring_enabled;
		} catch ( \Exception $e ) {
			return '';
		}

		return 'yes' === $value
			? __( 'Yes', 'eventin-beaver-themer' )
			: __( 'No', 'eventin-beaver-themer' );
	}

	/**
	 * Event permalink.
	 *
	 * @return string
	 */
	public static function event_url() {
		$post_id = get_the_ID();

		if ( ! $post_id || 'etn' !== get_post_type( $post_id ) ) {
			return '';
		}

		return get_permalink( $post_id );
	}

	/**
	 * Number of speakers linked to the event.
	 *
	 * @return string
	 */
	public static function speaker_count() {
		return (string) count( self::speaker_ids() );
	}

	/**
	 * Number of organizers linked to the event.
	 *
	 * @return string
	 */
	public static function organizer_count() {
		return (string) count( self::organizer_ids() );
	}

	/*
	 * ---------------------------------------------------------------
	 * Speaker / Organizer / Ticket / FAQ indexed accessors (Phase 2)
	 * ---------------------------------------------------------------
	 */

	/**
	 * Get a speaker User_Model at a given index.
	 *
	 * @param int $index Zero-based index.
	 *
	 * @return \Etn\Core\Speaker\User_Model|null
	 */
	private static function speaker_model_at( $index ) {
		$ids = self::speaker_ids();

		if ( ! isset( $ids[ $index ] ) ) {
			return null;
		}

		return self::user_model( $ids[ $index ] );
	}

	/**
	 * Get an organizer User_Model at a given index.
	 *
	 * @param int $index Zero-based index.
	 *
	 * @return \Etn\Core\Speaker\User_Model|null
	 */
	private static function organizer_model_at( $index ) {
		$ids = self::organizer_ids();

		if ( ! isset( $ids[ $index ] ) ) {
			return null;
		}

		return self::user_model( $ids[ $index ] );
	}

	/**
	 * Get a ticket variation array at a given index.
	 *
	 * @param int $index Zero-based index.
	 *
	 * @return array|null
	 */
	private static function ticket_at( $index ) {
		$event = self::event();

		if ( ! $event ) {
			return null;
		}

		$variations = self::safe( array( $event, 'get_ticket' ) );

		return ( is_array( $variations ) && isset( $variations[ $index ] ) ) ? $variations[ $index ] : null;
	}

	/**
	 * Get a FAQ item array at a given index.
	 *
	 * @param int $index Zero-based index.
	 *
	 * @return array|null
	 */
	private static function faq_at( $index ) {
		$event = self::event();

		if ( ! $event ) {
			return null;
		}

		try {
			$faqs = $event->etn_event_faq;
		} catch ( \Exception $e ) {
			return null;
		}

		return ( is_array( $faqs ) && isset( $faqs[ $index ] ) ) ? $faqs[ $index ] : null;
	}

	// -- Speaker indexed getters --

	/**
	 * Speaker name at index.
	 *
	 * @param object $settings Connection settings.
	 *
	 * @return string
	 */
	public static function speaker_name( $settings ) {
		$model = self::speaker_model_at( self::index_from( $settings ) );

		return $model ? $model->get_speaker_title() : '';
	}

	/**
	 * Speaker photo attachment ID at index.
	 *
	 * @param object $settings Connection settings.
	 *
	 * @return int|string
	 */
	public static function speaker_photo( $settings ) {
		$model = self::speaker_model_at( self::index_from( $settings ) );

		if ( ! $model ) {
			return '';
		}

		$id = $model->get_image_id();

		return $id ? (int) $id : '';
	}

	/**
	 * Speaker designation / title at index.
	 *
	 * @param object $settings Connection settings.
	 *
	 * @return string
	 */
	public static function speaker_designation( $settings ) {
		$model = self::speaker_model_at( self::index_from( $settings ) );

		return $model ? $model->get_speaker_designation() : '';
	}

	/**
	 * Speaker company name at index.
	 *
	 * @param object $settings Connection settings.
	 *
	 * @return string
	 */
	public static function speaker_company( $settings ) {
		$model = self::speaker_model_at( self::index_from( $settings ) );

		return $model ? $model->get_company_name() : '';
	}

	/**
	 * Speaker bio / summary at index.
	 *
	 * @param object $settings Connection settings.
	 *
	 * @return string
	 */
	public static function speaker_bio( $settings ) {
		$model = self::speaker_model_at( self::index_from( $settings ) );

		return $model ? $model->get_speaker_summary() : '';
	}

	/**
	 * Speaker website URL at index.
	 *
	 * @param object $settings Connection settings.
	 *
	 * @return string
	 */
	public static function speaker_website( $settings ) {
		$model = self::speaker_model_at( self::index_from( $settings ) );

		return $model ? $model->get_speaker_url() : '';
	}

	/**
	 * Speaker email at index.
	 *
	 * @param object $settings Connection settings.
	 *
	 * @return string
	 */
	public static function speaker_email( $settings ) {
		$model = self::speaker_model_at( self::index_from( $settings ) );

		return $model ? $model->get_speaker_email() : '';
	}

	/**
	 * Speaker company logo attachment ID at index.
	 *
	 * @param object $settings Connection settings.
	 *
	 * @return int|string
	 */
	public static function speaker_company_logo( $settings ) {
		$model = self::speaker_model_at( self::index_from( $settings ) );

		if ( ! $model ) {
			return '';
		}

		$id = $model->get_company_logo_id();

		return $id ? (int) $id : '';
	}

	// -- Organizer indexed getters --

	/**
	 * Organizer name at index.
	 *
	 * @param object $settings Connection settings.
	 *
	 * @return string
	 */
	public static function organizer_name( $settings ) {
		$model = self::organizer_model_at( self::index_from( $settings ) );

		return $model ? $model->get_speaker_title() : '';
	}

	/**
	 * Organizer photo attachment ID at index.
	 *
	 * @param object $settings Connection settings.
	 *
	 * @return int|string
	 */
	public static function organizer_photo( $settings ) {
		$model = self::organizer_model_at( self::index_from( $settings ) );

		if ( ! $model ) {
			return '';
		}

		$id = $model->get_image_id();

		return $id ? (int) $id : '';
	}

	/**
	 * Organizer email at index.
	 *
	 * @param object $settings Connection settings.
	 *
	 * @return string
	 */
	public static function organizer_email( $settings ) {
		$model = self::organizer_model_at( self::index_from( $settings ) );

		return $model ? $model->get_speaker_email() : '';
	}

	/**
	 * Organizer phone at index.
	 *
	 * @param object $settings Connection settings.
	 *
	 * @return string
	 */
	public static function organizer_phone( $settings ) {
		$model = self::organizer_model_at( self::index_from( $settings ) );

		return $model ? $model->get_phone() : '';
	}

	/**
	 * Organizer website URL at index.
	 *
	 * @param object $settings Connection settings.
	 *
	 * @return string
	 */
	public static function organizer_website( $settings ) {
		$model = self::organizer_model_at( self::index_from( $settings ) );

		return $model ? $model->get_speaker_url() : '';
	}

	/**
	 * Organizer company name at index.
	 *
	 * @param object $settings Connection settings.
	 *
	 * @return string
	 */
	public static function organizer_company( $settings ) {
		$model = self::organizer_model_at( self::index_from( $settings ) );

		return $model ? $model->get_company_name() : '';
	}

	/**
	 * Organizer bio at index.
	 *
	 * @param object $settings Connection settings.
	 *
	 * @return string
	 */
	public static function organizer_bio( $settings ) {
		$model = self::organizer_model_at( self::index_from( $settings ) );

		return $model ? $model->get_organizer_bio() : '';
	}

	// -- Ticket indexed getters --

	/**
	 * Ticket variation name at index.
	 *
	 * @param object $settings Connection settings.
	 *
	 * @return string
	 */
	public static function ticket_name( $settings ) {
		$ticket = self::ticket_at( self::index_from( $settings ) );

		return ( $ticket && isset( $ticket['etn_ticket_name'] ) ) ? (string) $ticket['etn_ticket_name'] : '';
	}

	/**
	 * Ticket variation price at index, optionally with currency.
	 *
	 * @param object $settings Connection settings.
	 *
	 * @return string
	 */
	public static function ticket_price_item( $settings ) {
		$ticket = self::ticket_at( self::index_from( $settings ) );

		if ( ! $ticket || ! isset( $ticket['etn_ticket_price'] ) || ! is_numeric( $ticket['etn_ticket_price'] ) ) {
			return '';
		}

		$price         = (float) $ticket['etn_ticket_price'];
		$with_currency = ! ( isset( $settings->show_currency ) && '0' === (string) $settings->show_currency );

		if ( $with_currency && class_exists( 'Etn\\Core\\Event\\Helper' ) && method_exists( 'Etn\\Core\\Event\\Helper', 'instance' ) ) {
			return \Etn\Core\Event\Helper::instance()->currency_with_position( $price );
		}

		return (string) $price;
	}

	/**
	 * Ticket variation available quantity at index.
	 *
	 * @param object $settings Connection settings.
	 *
	 * @return string
	 */
	public static function ticket_available( $settings ) {
		$ticket = self::ticket_at( self::index_from( $settings ) );

		return ( $ticket && isset( $ticket['etn_avaiilable_tickets'] ) ) ? (string) $ticket['etn_avaiilable_tickets'] : '';
	}

	/**
	 * Ticket variation sold count at index.
	 *
	 * @param object $settings Connection settings.
	 *
	 * @return string
	 */
	public static function ticket_sold_item( $settings ) {
		$ticket = self::ticket_at( self::index_from( $settings ) );

		return ( $ticket && isset( $ticket['etn_sold_tickets'] ) ) ? (string) $ticket['etn_sold_tickets'] : '';
	}

	/**
	 * Ticket variation remaining (available - sold) at index.
	 *
	 * @param object $settings Connection settings.
	 *
	 * @return string
	 */
	public static function ticket_remaining( $settings ) {
		$ticket = self::ticket_at( self::index_from( $settings ) );

		if ( ! $ticket ) {
			return '';
		}

		$avail = isset( $ticket['etn_avaiilable_tickets'] ) ? (int) $ticket['etn_avaiilable_tickets'] : 0;
		$sold  = isset( $ticket['etn_sold_tickets'] ) ? (int) $ticket['etn_sold_tickets'] : 0;

		return (string) max( 0, $avail - $sold );
	}

	// -- FAQ indexed getters --

	/**
	 * FAQ question title at index.
	 *
	 * @param object $settings Connection settings.
	 *
	 * @return string
	 */
	public static function faq_question( $settings ) {
		$faq = self::faq_at( self::index_from( $settings ) );

		return ( $faq && isset( $faq['etn_faq_title'] ) ) ? (string) $faq['etn_faq_title'] : '';
	}

	/**
	 * FAQ answer content at index.
	 *
	 * @param object $settings Connection settings.
	 *
	 * @return string
	 */
	public static function faq_answer( $settings ) {
		$faq = self::faq_at( self::index_from( $settings ) );

		return ( $faq && isset( $faq['etn_faq_content'] ) ) ? wp_kses_post( $faq['etn_faq_content'] ) : '';
	}

	/*
	 * ---------------------------------------------------------------
	 * Internal helpers
	 * ---------------------------------------------------------------
	 */

	/**
	 * Read the zero-based index from a connection's settings.
	 *
	 * @param object $settings Connection settings.
	 *
	 * @return int
	 */
	private static function index_from( $settings ) {
		return isset( $settings->index ) ? (int) $settings->index : 0;
	}

	/**
	 * Get array of speaker user IDs for the current event.
	 *
	 * @return int[]
	 */
	private static function speaker_ids() {
		$event = self::event();

		if ( ! $event ) {
			return array();
		}

		try {
			$ids = $event->etn_event_speaker;
		} catch ( \Exception $e ) {
			return array();
		}

		return is_array( $ids ) ? array_map( 'intval', $ids ) : array();
	}

	/**
	 * Get array of organizer user IDs for the current event.
	 *
	 * @return int[]
	 */
	private static function organizer_ids() {
		$event = self::event();

		if ( ! $event ) {
			return array();
		}

		try {
			$ids = $event->etn_event_organizer;
		} catch ( \Exception $e ) {
			return array();
		}

		return is_array( $ids ) ? array_map( 'intval', $ids ) : array();
	}

	/**
	 * Create a Speaker/Organizer User_Model for a given user ID.
	 *
	 * @param int $user_id WordPress user ID.
	 *
	 * @return \Etn\Core\Speaker\User_Model|null
	 */
	private static function user_model( $user_id ) {
		if ( ! $user_id || ! class_exists( 'Etn\\Core\\Speaker\\User_Model' ) ) {
			return null;
		}

		return new \Etn\Core\Speaker\User_Model( $user_id );
	}

	/**
	 * Reduce an array of WP_Term objects to a comma separated name list.
	 *
	 * @param mixed $terms Array of WP_Term objects.
	 *
	 * @return string
	 */
	private static function term_names( $terms ) {
		if ( empty( $terms ) || ! is_array( $terms ) ) {
			return '';
		}

		return implode( ', ', wp_list_pluck( $terms, 'name' ) );
	}

	/**
	 * Call an Event_Model getter without letting its exceptions bubble up.
	 *
	 * Event_Model throws on undefined properties and can throw while parsing
	 * malformed dates, so every model access goes through here.
	 *
	 * @param callable $callback Callable to invoke.
	 * @param mixed    ...$args  Arguments for the callable.
	 *
	 * @return mixed Getter result, or empty string on failure.
	 */
	private static function safe( $callback, ...$args ) {
		try {
			return call_user_func_array( $callback, $args );
		} catch ( \Throwable $e ) {
			return '';
		}
	}
}
