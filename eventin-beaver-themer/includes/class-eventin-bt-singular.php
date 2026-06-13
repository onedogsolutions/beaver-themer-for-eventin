<?php
/**
 * Single event layout support for Beaver Themer.
 *
 * @package Eventin_Beaver_Themer
 */

defined( 'ABSPATH' ) || exit;

/**
 * Lets a Beaver Themer "Singular" layout take over single `etn` event pages.
 *
 * Mirrors the approach Beaver Themer's own first-party extensions use: detect
 * the event view (including while editing a layout in the builder), and when a
 * Themer singular layout applies, remove Eventin's own single-template override
 * so Themer renders instead.
 */
final class Eventin_BT_Singular {

	/**
	 * The Eventin event post type.
	 *
	 * @var string
	 */
	const POST_TYPE = 'etn';

	/**
	 * Register hooks.
	 *
	 * @return void
	 */
	public static function init() {
		add_action( 'wp', array( __CLASS__, 'init_hooks' ) );
	}

	/**
	 * Decide whether the current request is a single event handled by Themer.
	 *
	 * @return void
	 */
	public static function init_hooks() {
		if ( ! self::is_single_event() ) {
			return;
		}

		add_filter( 'body_class', array( __CLASS__, 'body_class' ) );
		add_filter( 'fl_builder_content_classes', array( __CLASS__, 'content_classes' ) );

		if ( self::has_singular_layout() ) {
			Eventin_BT_Template::disable_single_override();
		}
	}

	/**
	 * Is the current view a single event (or a layout previewing one)?
	 *
	 * @return bool
	 */
	private static function is_single_event() {
		if ( is_singular( self::POST_TYPE ) ) {
			return true;
		}

		// While editing/previewing a theme layout, the queried object is the
		// layout itself, so fall back to the layout's preview location.
		if ( 'fl-theme-layout' !== get_post_type() || ! class_exists( 'FLThemeBuilderRulesLocation' ) ) {
			return false;
		}

		$location = FLThemeBuilderRulesLocation::get_preview_location( get_the_ID() );

		return is_string( $location ) && false !== stripos( $location, 'post:' . self::POST_TYPE );
	}

	/**
	 * Does a Themer singular layout apply to the current request?
	 *
	 * @return bool
	 */
	private static function has_singular_layout() {
		if ( ! class_exists( 'FLThemeBuilderLayoutData' ) ) {
			return false;
		}

		$layouts = FLThemeBuilderLayoutData::get_current_page_layouts();

		return is_array( $layouts ) && ! empty( $layouts['singular'] );
	}

	/**
	 * Add an event body class to Themer layouts assigned to event locations.
	 *
	 * @param string[] $classes Body classes.
	 *
	 * @return string[]
	 */
	public static function body_class( $classes ) {
		$classes[] = 'single-' . self::POST_TYPE;
		$classes[] = 'eventin-single-event';

		return $classes;
	}

	/**
	 * Add an Eventin-specific class to the Themer content wrapper.
	 *
	 * @param string $classes Space separated class string.
	 *
	 * @return string
	 */
	public static function content_classes( $classes ) {
		return trim( $classes . ' eventin-single-event-content' );
	}
}
