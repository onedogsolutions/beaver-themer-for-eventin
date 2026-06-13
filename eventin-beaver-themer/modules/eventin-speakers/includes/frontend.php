<?php
/**
 * Frontend output for the Eventin Speakers module.
 *
 * @package Eventin_Beaver_Themer
 *
 * @var object $settings Module settings.
 */

defined( 'ABSPATH' ) || exit;

echo Eventin_BT_Modules::shortcode( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	'speakers',
	array(
		'style'           => $settings->style,
		'etn_speaker_col' => $settings->etn_speaker_col,
		'limit'           => $settings->limit,
		'cat_id'          => $settings->cat_id,
		'orderby'         => $settings->orderby,
		'order'           => $settings->order,
	)
);
