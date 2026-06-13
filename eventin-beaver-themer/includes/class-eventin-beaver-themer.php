<?php
/**
 * Main plugin bootstrap.
 *
 * @package Eventin_Beaver_Themer
 */

defined( 'ABSPATH' ) || exit;

/**
 * Wires Eventin into Beaver Themer.
 *
 * Responsibilities:
 *  - Verify that both Eventin and Beaver Themer are active.
 *  - Register Eventin field connections (FLPageData properties).
 *  - Load the singular/archive layout handlers that let Themer take over
 *    the single event and event archive templates.
 */
final class Eventin_Beaver_Themer {

	/**
	 * Singleton instance.
	 *
	 * @var Eventin_Beaver_Themer|null
	 */
	private static $instance = null;

	/**
	 * Retrieve / create the singleton instance.
	 *
	 * @return Eventin_Beaver_Themer
	 */
	public static function instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Constructor.
	 */
	private function __construct() {
		add_action( 'init', array( $this, 'load_textdomain' ), 5 );

		if ( ! $this->has_dependencies() ) {
			add_action( 'admin_notices', array( $this, 'dependency_notice' ) );
			return;
		}

		$this->load_field_connections();
		$this->load_layout_handlers();
	}

	/**
	 * Are both Eventin and Beaver Themer available?
	 *
	 * @return bool
	 */
	public function has_dependencies() {
		return $this->is_eventin_active() && $this->is_themer_active();
	}

	/**
	 * Is the Eventin (wp-event-solution) plugin active?
	 *
	 * @return bool
	 */
	public function is_eventin_active() {
		return class_exists( 'Etn\\Core\\Event\\Event_Model' );
	}

	/**
	 * Is Beaver Themer active?
	 *
	 * FLThemeBuilderLayoutData is registered by Beaver Themer (not the free
	 * Beaver Builder plugin), so checking for it confirms Themer specifically.
	 *
	 * @return bool
	 */
	public function is_themer_active() {
		return class_exists( 'FLThemeBuilderLayoutData' ) && class_exists( 'FLPageData' );
	}

	/**
	 * Load plugin translations.
	 *
	 * @return void
	 */
	public function load_textdomain() {
		load_plugin_textdomain(
			'eventin-beaver-themer',
			false,
			dirname( plugin_basename( EVENTIN_BT_FILE ) ) . '/languages'
		);
	}

	/**
	 * Register Eventin field connections with Beaver Themer.
	 *
	 * Beaver Themer fires `fl_page_data_add_properties` at the point where
	 * connection groups and properties should be registered.
	 *
	 * @return void
	 */
	private function load_field_connections() {
		add_action(
			'fl_page_data_add_properties',
			function () {
				require_once EVENTIN_BT_DIR . 'includes/class-eventin-bt-page-data.php';
				require_once EVENTIN_BT_DIR . 'includes/page-data-eventin.php';
			}
		);
	}

	/**
	 * Load the singular and archive layout handlers.
	 *
	 * @return void
	 */
	private function load_layout_handlers() {
		require_once EVENTIN_BT_DIR . 'includes/class-eventin-bt-template.php';
		require_once EVENTIN_BT_DIR . 'includes/class-eventin-bt-singular.php';
		require_once EVENTIN_BT_DIR . 'includes/class-eventin-bt-archive.php';

		Eventin_BT_Singular::init();
		Eventin_BT_Archive::init();
	}

	/**
	 * Admin notice shown when a dependency is missing.
	 *
	 * @return void
	 */
	public function dependency_notice() {
		if ( ! current_user_can( 'activate_plugins' ) ) {
			return;
		}

		$missing = array();

		if ( ! $this->is_eventin_active() ) {
			$missing[] = '<strong>Eventin</strong>';
		}

		if ( ! $this->is_themer_active() ) {
			$missing[] = '<strong>Beaver Themer</strong>';
		}

		if ( empty( $missing ) ) {
			return;
		}

		printf(
			'<div class="notice notice-warning"><p>%s</p></div>',
			sprintf(
				/* translators: %s: comma separated list of required plugin names. */
				esc_html__( 'Beaver Themer for Eventin requires the following plugin(s) to be active: %s.', 'eventin-beaver-themer' ),
				wp_kses_post( implode( ', ', $missing ) )
			)
		);
	}
}
