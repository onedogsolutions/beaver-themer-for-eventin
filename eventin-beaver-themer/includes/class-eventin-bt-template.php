<?php
/**
 * Helpers for stepping Eventin aside so Beaver Themer can render.
 *
 * @package Eventin_Beaver_Themer
 */

defined( 'ABSPATH' ) || exit;

/**
 * Eventin registers `template_include` filters (priority 99) that force its own
 * single event and event archive templates. Those filters run *after* Beaver
 * Themer's, so without intervention Eventin always wins and the Themer layout
 * never renders.
 *
 * Eventin natively detects Elementor Pro and steps aside for it, but provides no
 * equivalent for other builders, so we remove its template callbacks for the
 * current request when a matching Themer layout exists. We only target the
 * specific Eventin callbacks, by class + method, leaving everything else intact.
 */
final class Eventin_BT_Template {

	/**
	 * Classes that may own Eventin's `template_include` callbacks.
	 *
	 * Both the current (4.x) class and the legacy class are handled so the
	 * integration keeps working across Eventin versions.
	 *
	 * @var string[]
	 */
	private static $owner_classes = array(
		'Eventin\\Event\\EventTemplate',
		'Etn\\Core\\Event\\Pages\\Event_single_post',
	);

	/**
	 * Remove Eventin's single event template override.
	 *
	 * @return void
	 */
	public static function disable_single_override() {
		self::remove_template_callbacks( array( 'event_single_page' ) );
	}

	/**
	 * Remove Eventin's event archive template override.
	 *
	 * @return void
	 */
	public static function disable_archive_override() {
		self::remove_template_callbacks( array( 'event_archive_template' ) );
	}

	/**
	 * Strip matching Eventin callbacks from the `template_include` filter.
	 *
	 * @param string[] $methods Method names to remove.
	 *
	 * @return void
	 */
	private static function remove_template_callbacks( array $methods ) {
		global $wp_filter;

		if ( empty( $wp_filter['template_include'] ) || ! is_object( $wp_filter['template_include'] ) ) {
			return;
		}

		$hook = $wp_filter['template_include'];

		if ( empty( $hook->callbacks ) || ! is_array( $hook->callbacks ) ) {
			return;
		}

		foreach ( $hook->callbacks as $priority => $callbacks ) {
			foreach ( $callbacks as $id => $callback ) {
				if ( empty( $callback['function'] ) ) {
					continue;
				}

				$fn = $callback['function'];

				if ( ! is_array( $fn ) || ! is_object( $fn[0] ) || ! is_string( $fn[1] ) ) {
					continue;
				}

				if ( in_array( $fn[1], $methods, true ) && in_array( get_class( $fn[0] ), self::$owner_classes, true ) ) {
					unset( $wp_filter['template_include']->callbacks[ $priority ][ $id ] );
				}
			}
		}
	}
}
