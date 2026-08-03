<?php
/**
 * Frontend output for the Eventin Events module.
 *
 * @package Eventin_Beaver_Themer
 *
 * @var object $settings Module settings.
 */

defined( 'ABSPATH' ) || exit;

echo Eventin_BT_Modules::shortcode( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	'events',
	array(
		'style'                  => $settings->style,
		'etn_event_col'          => $settings->etn_event_col,
		'limit'                  => $settings->limit,
		'event_cat_ids'          => $settings->event_cat_ids,
		'event_tag_ids'          => $settings->event_tag_ids,
		'orderby'                => $settings->orderby,
		'order'                  => $settings->order,
		'filter_with_status'     => $settings->filter_with_status,
		'show_event_location'    => $settings->show_event_location,
		'show_end_date'          => $settings->show_end_date,
		'show_remaining_tickets' => $settings->show_remaining_tickets,
		'etn_desc_show'          => $settings->etn_desc_show,
		'desc_limit'             => $settings->desc_limit,
	)
);
