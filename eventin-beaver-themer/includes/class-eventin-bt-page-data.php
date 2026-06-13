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
		$event = self::event();

		return $event ? self::people_names( self::safe( array( $event, 'get_organizers' ) ) ) : '';
	}

	/**
	 * Comma separated speaker names.
	 *
	 * @return string
	 */
	public static function speakers() {
		$event = self::event();

		return $event ? self::people_names( self::safe( array( $event, 'get_speakers' ) ) ) : '';
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

	/**
	 * Reduce an array of Eventin user models to a comma separated name list.
	 *
	 * @param mixed $people Array of User_Model objects.
	 *
	 * @return string
	 */
	private static function people_names( $people ) {
		if ( empty( $people ) || ! is_array( $people ) ) {
			return '';
		}

		$names = array();

		foreach ( $people as $person ) {
			if ( is_object( $person ) && method_exists( $person, 'get_speaker_title' ) ) {
				$name = $person->get_speaker_title();
				if ( ! empty( $name ) ) {
					$names[] = $name;
				}
			}
		}

		return implode( ', ', $names );
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
