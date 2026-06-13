<?php
/**
 * Frontend output for the Eventin Events Calendar module.
 *
 * @package Eventin_Beaver_Themer
 *
 * @var object $settings Module settings.
 */

defined( 'ABSPATH' ) || exit;

echo Eventin_BT_Modules::shortcode( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	'events_calendar',
	array(
		'style'               => $settings->style,
		'calendar_show'       => $settings->calendar_show,
		'show_desc'           => $settings->show_desc,
		'show_upcoming_event' => $settings->show_upcoming_event,
		'limit'               => $settings->limit,
		'event_cat_ids'       => $settings->event_cat_ids,
	)
);
