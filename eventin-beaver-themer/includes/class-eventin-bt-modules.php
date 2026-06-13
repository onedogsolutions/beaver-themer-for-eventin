<?php
/**
 * Registers the Eventin Beaver Builder modules.
 *
 * @package Eventin_Beaver_Themer
 */

defined( 'ABSPATH' ) || exit;

/**
 * Loads each module on `init` and provides a small shortcode-rendering helper
 * the modules share.
 *
 * Every module is a thin Beaver Builder wrapper around an Eventin shortcode (or
 * template function), so the rendered markup matches Eventin's own output and
 * stays correct as Eventin evolves.
 */
final class Eventin_BT_Modules {

	/**
	 * Module folder slugs, in the order they appear in the builder.
	 *
	 * @var string[]
	 */
	private static $modules = array(
		'eventin-events',
		'eventin-events-tab',
		'eventin-calendar',
		'eventin-search',
		'eventin-speakers',
		'eventin-schedule',
		'eventin-tickets',
	);

	/**
	 * Hook module registration.
	 *
	 * @return void
	 */
	public static function init() {
		add_action( 'init', array( __CLASS__, 'register' ), 11 );
	}

	/**
	 * Include each module file so it registers itself with Beaver Builder.
	 *
	 * @return void
	 */
	public static function register() {
		if ( ! class_exists( 'FLBuilder' ) ) {
			return;
		}

		foreach ( self::$modules as $slug ) {
			$file = EVENTIN_BT_DIR . "modules/{$slug}/{$slug}.php";

			if ( file_exists( $file ) ) {
				require_once $file;
			}
		}
	}

	/**
	 * Build an Eventin shortcode from an attribute map and render it.
	 *
	 * Empty values are dropped so each shortcode falls back to its own defaults.
	 *
	 * @param string $tag   Shortcode tag, e.g. "events".
	 * @param array  $atts  Attribute => value map.
	 *
	 * @return string Rendered shortcode output.
	 */
	public static function shortcode( $tag, array $atts ) {
		$pairs = array();

		foreach ( $atts as $key => $value ) {
			if ( '' === $value || null === $value ) {
				continue;
			}

			$pairs[] = sprintf( '%s="%s"', sanitize_key( $key ), esc_attr( $value ) );
		}

		$shortcode = '[' . $tag . ( $pairs ? ' ' . implode( ' ', $pairs ) : '' ) . ']';

		return do_shortcode( $shortcode );
	}

	/**
	 * Resolve the event ID a single-event module should render for.
	 *
	 * @param object $settings Module settings (expects event_source / event_id).
	 *
	 * @return int
	 */
	public static function resolve_event_id( $settings ) {
		$source = isset( $settings->event_source ) ? $settings->event_source : 'current';

		if ( 'custom' === $source && ! empty( $settings->event_id ) ) {
			return (int) $settings->event_id;
		}

		return (int) get_the_ID();
	}
}
