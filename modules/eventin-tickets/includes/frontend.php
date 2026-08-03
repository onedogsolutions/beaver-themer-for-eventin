<?php
/**
 * Frontend output for the Eventin Event Tickets module.
 *
 * Renders Eventin's purchase / RSVP form for an event. Eventin's ticket form
 * functions read some values from the global post, so we set it up for the
 * target event and restore it afterwards.
 *
 * @package Eventin_Beaver_Themer
 *
 * @var object $settings Module settings.
 */

defined( 'ABSPATH' ) || exit;

$eventin_bt_event_id = Eventin_BT_Modules::resolve_event_id( $settings );

if ( ! $eventin_bt_event_id || ! function_exists( 'etn_after_single_event_meta_ticket_form' ) ) {
	return;
}

$eventin_bt_event = get_post( $eventin_bt_event_id );

if ( ! $eventin_bt_event || 'etn' !== $eventin_bt_event->post_type ) {
	if ( class_exists( 'FLBuilderModel' ) && FLBuilderModel::is_builder_active() ) {
		echo '<p>' . esc_html__( 'No event selected. Place this module on a single event layout, or choose a specific event.', 'eventin-beaver-themer' ) . '</p>';
	}
	return;
}

global $post;
$eventin_bt_original_post = $post;

// phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
$post = $eventin_bt_event;
setup_postdata( $post );

echo '<div class="eventin-bt-tickets etn-event-wrapper">';
etn_after_single_event_meta_ticket_form( $eventin_bt_event_id );

if ( function_exists( 'etn_after_single_event_meta_recurring_event_ticket_form' ) ) {
	etn_after_single_event_meta_recurring_event_ticket_form( $eventin_bt_event_id );
}
echo '</div>';

wp_reset_postdata();
// phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
$post = $eventin_bt_original_post;
