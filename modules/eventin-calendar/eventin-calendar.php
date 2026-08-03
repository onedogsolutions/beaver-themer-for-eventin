<?php
/**
 * Eventin Events Calendar module — wraps the Eventin [events_calendar] shortcode.
 *
 * @package Eventin_Beaver_Themer
 */

defined( 'ABSPATH' ) || exit;

/**
 * A monthly calendar of Eventin events.
 */
class Eventin_BT_Calendar_Module extends FLBuilderModule {

	/**
	 * Constructor.
	 */
	public function __construct() {
		parent::__construct(
			array(
				'name'            => __( 'Eventin Events Calendar', 'eventin-beaver-themer' ),
				'description'     => __( 'A monthly calendar of Eventin events.', 'eventin-beaver-themer' ),
				'category'        => __( 'Eventin', 'eventin-beaver-themer' ),
				'group'           => __( 'Eventin', 'eventin-beaver-themer' ),
				'dir'             => EVENTIN_BT_DIR . 'modules/eventin-calendar/',
				'url'             => EVENTIN_BT_URL . 'modules/eventin-calendar/',
				'partial_refresh' => true,
			)
		);
	}
}

FLBuilder::register_module(
	'Eventin_BT_Calendar_Module',
	array(
		'general' => array(
			'title'    => __( 'General', 'eventin-beaver-themer' ),
			'sections' => array(
				'layout' => array(
					'title'  => __( 'Layout', 'eventin-beaver-themer' ),
					'fields' => array(
						'style'               => array(
							'type'    => 'select',
							'label'   => __( 'Style', 'eventin-beaver-themer' ),
							'default' => 'style-1',
							'options' => array(
								'style-1' => __( 'Style 1', 'eventin-beaver-themer' ),
								'style-2' => __( 'Style 2', 'eventin-beaver-themer' ),
							),
						),
						'calendar_show'       => array(
							'type'    => 'select',
							'label'   => __( 'Event List Position', 'eventin-beaver-themer' ),
							'default' => 'left',
							'options' => array(
								'left'  => __( 'Left', 'eventin-beaver-themer' ),
								'right' => __( 'Right', 'eventin-beaver-themer' ),
							),
						),
						'show_desc'           => array(
							'type'    => 'select',
							'label'   => __( 'Show Description', 'eventin-beaver-themer' ),
							'default' => 'no',
							'options' => array(
								'yes' => __( 'Yes', 'eventin-beaver-themer' ),
								'no'  => __( 'No', 'eventin-beaver-themer' ),
							),
						),
						'show_upcoming_event' => array(
							'type'    => 'select',
							'label'   => __( 'Show Upcoming List', 'eventin-beaver-themer' ),
							'default' => 'yes',
							'options' => array(
								'yes' => __( 'Yes', 'eventin-beaver-themer' ),
								'no'  => __( 'No', 'eventin-beaver-themer' ),
							),
						),
					),
				),
				'query'  => array(
					'title'  => __( 'Query', 'eventin-beaver-themer' ),
					'fields' => array(
						'limit'         => array(
							'type'    => 'text',
							'label'   => __( 'Upcoming List Limit', 'eventin-beaver-themer' ),
							'default' => '15',
							'size'    => 5,
						),
						'event_cat_ids' => array(
							'type'        => 'text',
							'label'       => __( 'Category IDs', 'eventin-beaver-themer' ),
							'default'     => '',
							'description' => __( 'Comma-separated category term IDs. Leave blank for all.', 'eventin-beaver-themer' ),
						),
					),
				),
			),
		),
	)
);
