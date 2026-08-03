<?php
/**
 * Fired when the plugin is uninstalled (deleted).
 *
 * No options or custom post types are created by this plugin, so there is
 * nothing to clean up today. The file is included so that WordPress finds a
 * valid uninstall handler if cleanup tasks are added in the future.
 *
 * @package Eventin_Beaver_Themer
 */

// Abort if not called by WordPress.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}
