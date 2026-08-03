<?php
/**
 * Event archive layout support for Beaver Themer.
 *
 * @package Eventin_Beaver_Themer
 */

defined( 'ABSPATH' ) || exit;

/**
 * Lets a Beaver Themer "Archive" layout take over the event archive and the
 * Eventin category / tag taxonomy archives.
 *
 * As well as stepping Eventin's archive template aside, it teaches the Beaver
 * Builder Posts module how to order an event loop by start date, matching the
 * behaviour of Beaver Themer's first-party event integrations.
 */
final class Eventin_BT_Archive {

	/**
	 * The Eventin event post type.
	 *
	 * @var string
	 */
	const POST_TYPE = 'etn';

	/**
	 * Eventin event taxonomies.
	 *
	 * @var string[]
	 */
	private static $taxonomies = array( 'etn_category', 'etn_tags' );

	/**
	 * Register hooks.
	 *
	 * @return void
	 */
	public static function init() {
		add_action( 'wp', array( __CLASS__, 'init_hooks' ) );
	}

	/**
	 * Decide whether the current request is an event archive handled by Themer.
	 *
	 * @return void
	 */
	public static function init_hooks() {
		if ( ! self::is_event_archive() ) {
			return;
		}

		add_filter( 'body_class', array( __CLASS__, 'body_class' ) );
		add_filter( 'fl_builder_loop_query', array( __CLASS__, 'builder_loop_query' ), 10, 2 );

		if ( self::has_archive_layout() ) {
			Eventin_BT_Template::disable_archive_override();
		}
	}

	/**
	 * Is the current view an event archive (or a layout previewing one)?
	 *
	 * @return bool
	 */
	private static function is_event_archive() {
		if ( is_post_type_archive( self::POST_TYPE ) || is_tax( self::$taxonomies ) ) {
			return true;
		}

		if ( 'fl-theme-layout' !== get_post_type() || ! class_exists( 'FLThemeBuilderRulesLocation' ) ) {
			return false;
		}

		$layout_type = get_post_meta( get_the_ID(), '_fl_theme_layout_type', true );

		if ( 'archive' !== $layout_type ) {
			return false;
		}

		$location = FLThemeBuilderRulesLocation::get_preview_location( get_the_ID() );

		if ( ! is_string( $location ) ) {
			return false;
		}

		foreach ( array_merge( array( self::POST_TYPE ), self::$taxonomies ) as $needle ) {
			if ( false !== stripos( $location, $needle ) ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * Does a Themer archive layout apply to the current request?
	 *
	 * @return bool
	 */
	private static function has_archive_layout() {
		if ( ! class_exists( 'FLThemeBuilderLayoutData' ) ) {
			return false;
		}

		$layouts = FLThemeBuilderLayoutData::get_current_page_layouts();

		return is_array( $layouts ) && ! empty( $layouts['archive'] );
	}

	/**
	 * Add event archive body classes to Themer archive layouts.
	 *
	 * @param string[] $classes Body classes.
	 *
	 * @return string[]
	 */
	public static function body_class( $classes ) {
		$classes[] = 'eventin-events-archive';

		return $classes;
	}

	/**
	 * Order the main-query event loop by start date for Posts modules.
	 *
	 * Only the "Main Query" data source is touched so custom queries the user
	 * configures in the module are left exactly as they set them.
	 *
	 * @param WP_Query $query    The loop query.
	 * @param object   $settings The module settings.
	 *
	 * @return WP_Query
	 */
	public static function builder_loop_query( $query, $settings ) {
		if ( ! isset( $settings->data_source ) || 'main_query' !== $settings->data_source ) {
			return $query;
		}

		global $wp_query;

		$args = array_merge(
			(array) $wp_query->query_vars,
			array(
				'post_type' => self::POST_TYPE,
				'meta_key'  => 'etn_start_date', // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
				'orderby'   => 'meta_value',
				'order'     => 'ASC',
				'paged'     => class_exists( 'FLBuilderLoop' ) ? FLBuilderLoop::get_paged() : 1,
			)
		);

		return new WP_Query( $args );
	}
}
