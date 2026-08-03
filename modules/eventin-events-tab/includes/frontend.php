<?php
/**
 * Frontend output for the Eventin Events Tab module.
 *
 * @package Eventin_Beaver_Themer
 *
 * @var object $settings Module settings.
 */

defined( 'ABSPATH' ) || exit;

echo Eventin_BT_Modules::shortcode( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	'events_tab',
	array(
		'style'               => $settings->style,
		'etn_event_col'       => $settings->etn_event_col,
		'limit'               => $settings->limit,
		'event_cat_ids'       => $settings->event_cat_ids,
		'event_tag_ids'       => $settings->event_tag_ids,
		'orderby'             => $settings->orderby,
		'order'               => $settings->order,
		'show_event_location' => $settings->show_event_location,
		'show_end_date'       => $settings->show_end_date,
	)
);
