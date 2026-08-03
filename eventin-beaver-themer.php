<?php
/**
 * Plugin Name:       Beaver Themer for Eventin
 * Plugin URI:        https://github.com/onedogsolutions/beaver-themer-for-eventin
 * Description:        Integrates Eventin with Beaver Themer so you can design single event and event archive layouts with Beaver Builder, plus field connections for event data.
 * Version:           1.0.0
 * Author:            One Dog Solutions
 * Author URI:        https://onedog.solutions/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       eventin-beaver-themer
 * Domain Path:       /languages
 * Requires Plugins:  wp-event-solution
 * Requires at least: 5.8
 * Requires PHP:      7.4
 *
 * @package Eventin_Beaver_Themer
 */

defined( 'ABSPATH' ) || exit;

define( 'EVENTIN_BT_VERSION', '1.0.0' );
define( 'EVENTIN_BT_FILE', __FILE__ );
define( 'EVENTIN_BT_DIR', plugin_dir_path( __FILE__ ) );
define( 'EVENTIN_BT_URL', plugin_dir_url( __FILE__ ) );

require_once EVENTIN_BT_DIR . 'includes/class-eventin-beaver-themer.php';

/**
 * Boot the plugin once all other plugins are loaded so that both
 * Beaver Themer and Eventin have had a chance to register their classes.
 */
function eventin_beaver_themer() {
	return Eventin_Beaver_Themer::instance();
}

add_action( 'plugins_loaded', 'eventin_beaver_themer', 99 );
