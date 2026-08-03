<?php
/**
 * Frontend output for the Eventin Event Search module.
 *
 * @package Eventin_Beaver_Themer
 */

defined( 'ABSPATH' ) || exit;

echo Eventin_BT_Modules::shortcode( 'event_search_filter', array() ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
