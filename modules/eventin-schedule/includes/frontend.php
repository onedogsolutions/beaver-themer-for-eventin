<?php
/**
 * Frontend output for the Eventin Schedule module.
 *
 * @package Eventin_Beaver_Themer
 *
 * @var object $settings Module settings.
 */

defined( 'ABSPATH' ) || exit;

$eventin_bt_tag = ( isset( $settings->display ) && 'list' === $settings->display ) ? 'schedules_list' : 'schedules';

echo Eventin_BT_Modules::shortcode( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	$eventin_bt_tag,
	array(
		'ids'   => $settings->ids,
		'order' => $settings->order,
	)
);
