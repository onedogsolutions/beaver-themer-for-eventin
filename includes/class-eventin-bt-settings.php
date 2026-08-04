<?php
/**
 * Admin settings page for Beaver Themer for Eventin.
 *
 * Provides a UI at Settings → Eventin Beaver Themer where site admins
 * can customize label terminology (e.g. "Event" → "Class") without
 * writing PHP. Saved values are stored in `wp_options` and merged
 * into the label dictionary by `Eventin_BT_Labels`.
 *
 * @package Eventin_Beaver_Themer
 */

defined( 'ABSPATH' ) || exit;

/**
 * Registers the settings page, renders the form, and applies saved
 * label overrides to `Eventin_BT_Labels` at filter priority 5.
 */
final class Eventin_BT_Settings {

	/**
	 * Option key in wp_options.
	 *
	 * @var string
	 */
	const OPTION_KEY = 'eventin_bt_settings';

	/**
	 * Settings group name for the Settings API.
	 *
	 * @var string
	 */
	const GROUP = 'eventin_bt_settings_group';

	/**
	 * Page slug.
	 *
	 * @var string
	 */
	const SLUG = 'eventin-beaver-themer';

	/**
	 * Register hooks.
	 *
	 * @return void
	 */
	public static function init() {
		add_action( 'admin_menu', array( __CLASS__, 'add_menu' ) );
		add_action( 'admin_init', array( __CLASS__, 'register_settings' ) );

		// Merge saved labels into the dictionary before developer overrides.
		add_filter( 'eventin_bt_labels', array( __CLASS__, 'apply_saved_labels' ), 5 );

		// Flush the label cache when settings are saved.
		add_action( 'update_option_' . self::OPTION_KEY, array( __CLASS__, 'on_save' ) );
	}

	/**
	 * Add the settings page under the Settings menu.
	 *
	 * @return void
	 */
	public static function add_menu() {
		add_options_page(
			__( 'Eventin Beaver Themer', 'eventin-beaver-themer' ),
			__( 'Eventin Beaver Themer', 'eventin-beaver-themer' ),
			'manage_options',
			self::SLUG,
			array( __CLASS__, 'render_page' )
		);
	}

	/**
	 * Register the settings, section, and fields with the Settings API.
	 *
	 * @return void
	 */
	public static function register_settings() {
		register_setting(
			self::GROUP,
			self::OPTION_KEY,
			array(
				'sanitize_callback' => array( __CLASS__, 'sanitize' ),
			)
		);

		add_settings_section(
			'eventin_bt_labels_section',
			__( 'Label Customization', 'eventin-beaver-themer' ),
			array( __CLASS__, 'section_description' ),
			self::SLUG
		);

		// Each rewritable noun: singular + plural fields.
		$nouns = array(
			'event'     => array( 'Event', 'Events' ),
			'speaker'   => array( 'Speaker', 'Speakers' ),
			'organizer' => array( 'Organizer', 'Organizers' ),
			'ticket'    => array( 'Ticket', 'Tickets' ),
			'category'  => array( 'Category', 'Categories' ),
			'tag'       => array( 'Tag', 'Tags' ),
		);

		foreach ( $nouns as $key => list( $default_singular, $default_plural ) ) {
			add_settings_field(
				"eventin_bt_label_{$key}_singular",
				sprintf(
					/* translators: %s: default label */
					__( '%s (singular)', 'eventin-beaver-themer' ),
					$default_singular
				),
				array( __CLASS__, 'render_text_field' ),
				self::SLUG,
				'eventin_bt_labels_section',
				array(
					'key'         => $key,
					'sub_key'     => 'singular',
					'placeholder' => $default_singular,
				)
			);

			add_settings_field(
				"eventin_bt_label_{$key}_plural",
				sprintf(
					/* translators: %s: default label */
					__( '%s (plural)', 'eventin-beaver-themer' ),
					$default_plural
				),
				array( __CLASS__, 'render_text_field' ),
				self::SLUG,
				'eventin_bt_labels_section',
				array(
					'key'         => $key,
					'sub_key'     => 'plural',
					'placeholder' => $default_plural,
				)
			);
		}

		// Connection group / module category name.
		add_settings_field(
			'eventin_bt_label_group',
			__( 'Connection Group Name', 'eventin-beaver-themer' ),
			array( __CLASS__, 'render_text_field' ),
			self::SLUG,
			'eventin_bt_labels_section',
			array(
				'key'         => 'group_name',
				'placeholder' => 'Eventin',
				'description' => __( 'The group label shown in Beaver Builder\'s field connection picker and module category.', 'eventin-beaver-themer' ),
			)
		);
	}

	/**
	 * Section description text.
	 *
	 * @return void
	 */
	public static function section_description() {
		echo '<p>' . esc_html__(
			'Customize the terminology used in Beaver Builder field connections and modules. Leave a field blank to use the default label. For example, change "Event" to "Class" and "Speaker" to "Instructor" for a training site.',
			'eventin-beaver-themer'
		) . '</p>';
	}

	/**
	 * Render a text input field.
	 *
	 * @param array $args Field arguments.
	 *
	 * @return void
	 */
	public static function render_text_field( $args ) {
		$options = get_option( self::OPTION_KEY, array() );
		$key     = $args['key'];
		$value   = '';

		if ( isset( $args['sub_key'] ) ) {
			$nested_key = $key . '_' . $args['sub_key'];
			$value      = isset( $options[ $nested_key ] ) ? $options[ $nested_key ] : '';
			$name       = sprintf( '%s[%s]', self::OPTION_KEY, $nested_key );
		} else {
			$value = isset( $options[ $key ] ) ? $options[ $key ] : '';
			$name  = sprintf( '%s[%s]', self::OPTION_KEY, $key );
		}

		printf(
			'<input type="text" name="%s" value="%s" placeholder="%s" class="regular-text" />',
			esc_attr( $name ),
			esc_attr( $value ),
			esc_attr( $args['placeholder'] )
		);

		if ( ! empty( $args['description'] ) ) {
			printf( '<p class="description">%s</p>', esc_html( $args['description'] ) );
		}
	}

	/**
	 * Sanitize the settings input.
	 *
	 * @param mixed $input Raw input.
	 *
	 * @return array
	 */
	public static function sanitize( $input ) {
		if ( ! is_array( $input ) ) {
			return array();
		}

		$clean = array();

		foreach ( $input as $key => $value ) {
			$clean[ sanitize_key( $key ) ] = sanitize_text_field( $value );
		}

		return $clean;
	}

	/**
	 * Render the settings page.
	 *
	 * @return void
	 */
	public static function render_page() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		echo '<div class="wrap">';
		printf( '<h1>%s</h1>', esc_html__( 'Eventin Beaver Themer Settings', 'eventin-beaver-themer' ) );
		echo '<form method="post" action="options.php">';
		settings_fields( self::GROUP );
		do_settings_sections( self::SLUG );
		submit_button();
		echo '</form>';
		echo '</div>';
	}

	/**
	 * Flush the label cache when settings are saved.
	 *
	 * @return void
	 */
	public static function on_save() {
		if ( class_exists( 'Eventin_BT_Labels' ) ) {
			Eventin_BT_Labels::flush();
		}
	}

	/**
	 * Merge saved label overrides into the defaults.
	 *
	 * Hooked to `eventin_bt_labels` at priority 5, so developer
	 * filter overrides at priority 10 take precedence.
	 *
	 * @param array $labels Default labels.
	 *
	 * @return array
	 */
	public static function apply_saved_labels( $labels ) {
		$saved = get_option( self::OPTION_KEY, array() );

		if ( empty( $saved ) || ! is_array( $saved ) ) {
			return $labels;
		}

		// Map saved singular/plural pairs to label keys.
		$noun_map = array(
			'event'     => array( 'event', 'events' ),
			'speaker'   => array( 'speaker', 'speakers' ),
			'organizer' => array( 'organizer', 'organizers' ),
			'ticket'    => array( 'ticket', 'tickets' ),
			'category'  => array( 'category', 'categories' ),
			'tag'       => array( 'tag', 'tags' ),
		);

		foreach ( $noun_map as $noun => list( $singular_key, $plural_key ) ) {
			$singular = isset( $saved[ $noun . '_singular' ] ) ? $saved[ $noun . '_singular' ] : '';
			$plural   = isset( $saved[ $noun . '_plural' ] ) ? $saved[ $noun . '_plural' ] : '';

			if ( '' !== $singular ) {
				$labels[ $singular_key ] = $singular;
			}

			if ( '' !== $plural ) {
				$labels[ $plural_key ] = $plural;
			}
		}

		// Group name cascades to module category and module group.
		$group = isset( $saved['group_name'] ) ? $saved['group_name'] : '';

		if ( '' !== $group ) {
			$labels['group_name']      = $group;
			$labels['module_category'] = $group;
			$labels['module_group']    = $group;
		}

		return $labels;
	}
}
