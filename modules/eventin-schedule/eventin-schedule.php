<?php
/**
 * Eventin Schedule module — wraps the Eventin [schedules] / [schedules_list] shortcodes.
 *
 * @package Eventin_Beaver_Themer
 */

defined( 'ABSPATH' ) || exit;

/**
 * An Eventin schedule, rendered as tabs or a list.
 */
class Eventin_BT_Schedule_Module extends FLBuilderModule {

	/**
	 * Constructor.
	 */
	public function __construct() {
		parent::__construct(
			array(
				'name'            => __( 'Eventin Schedule', 'eventin-beaver-themer' ),
				'description'     => __( 'An Eventin event schedule, as tabs or a list.', 'eventin-beaver-themer' ),
				'category'        => __( 'Eventin', 'eventin-beaver-themer' ),
				'group'           => __( 'Eventin', 'eventin-beaver-themer' ),
				'dir'             => EVENTIN_BT_DIR . 'modules/eventin-schedule/',
				'url'             => EVENTIN_BT_URL . 'modules/eventin-schedule/',
				'partial_refresh' => true,
			)
		);
	}
}

FLBuilder::register_module(
	'Eventin_BT_Schedule_Module',
	array(
		'general' => array(
			'title'    => __( 'General', 'eventin-beaver-themer' ),
			'sections' => array(
				'general' => array(
					'title'  => '',
					'fields' => array(
						'display' => array(
							'type'    => 'select',
							'label'   => __( 'Display', 'eventin-beaver-themer' ),
							'default' => 'tab',
							'options' => array(
								'tab'  => __( 'Tabs', 'eventin-beaver-themer' ),
								'list' => __( 'List', 'eventin-beaver-themer' ),
							),
						),
						'ids'     => array(
							'type'        => 'text',
							'label'       => __( 'Schedule IDs', 'eventin-beaver-themer' ),
							'default'     => '',
							'description' => __( 'Comma-separated Eventin schedule IDs.', 'eventin-beaver-themer' ),
						),
						'order'   => array(
							'type'    => 'select',
							'label'   => __( 'Order', 'eventin-beaver-themer' ),
							'default' => 'asc',
							'options' => array(
								'asc'  => __( 'Ascending', 'eventin-beaver-themer' ),
								'desc' => __( 'Descending', 'eventin-beaver-themer' ),
							),
						),
					),
				),
			),
		),
	)
);
