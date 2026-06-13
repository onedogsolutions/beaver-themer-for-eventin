<?php
/**
 * Eventin Event Tickets module — renders an event's ticket / registration form.
 *
 * @package Eventin_Beaver_Themer
 */

defined( 'ABSPATH' ) || exit;

/**
 * Outputs the Eventin purchase / RSVP form for an event, for use on single
 * event Themer layouts (the replacement for the WooCommerce ticket form).
 */
class Eventin_BT_Tickets_Module extends FLBuilderModule {

	/**
	 * Constructor.
	 */
	public function __construct() {
		parent::__construct(
			array(
				'name'            => __( 'Eventin Event Tickets', 'eventin-beaver-themer' ),
				'description'     => __( 'The ticket / registration form for an event.', 'eventin-beaver-themer' ),
				'category'        => __( 'Eventin', 'eventin-beaver-themer' ),
				'group'           => __( 'Eventin', 'eventin-beaver-themer' ),
				'dir'             => EVENTIN_BT_DIR . 'modules/eventin-tickets/',
				'url'             => EVENTIN_BT_URL . 'modules/eventin-tickets/',
				'partial_refresh' => true,
			)
		);
	}
}

FLBuilder::register_module(
	'Eventin_BT_Tickets_Module',
	array(
		'general' => array(
			'title'    => __( 'General', 'eventin-beaver-themer' ),
			'sections' => array(
				'general' => array(
					'title'  => '',
					'fields' => array(
						'event_source' => array(
							'type'    => 'select',
							'label'   => __( 'Event', 'eventin-beaver-themer' ),
							'default' => 'current',
							'options' => array(
								'current' => __( 'Current Event', 'eventin-beaver-themer' ),
								'custom'  => __( 'Specific Event', 'eventin-beaver-themer' ),
							),
							'toggle'  => array(
								'custom' => array(
									'fields' => array( 'event_id' ),
								),
							),
							'help'    => __( 'Use "Current Event" inside a single event Themer layout.', 'eventin-beaver-themer' ),
						),
						'event_id'     => array(
							'type'        => 'text',
							'label'       => __( 'Event ID', 'eventin-beaver-themer' ),
							'default'     => '',
							'size'        => 8,
							'description' => __( 'The ID of the event to show tickets for.', 'eventin-beaver-themer' ),
						),
					),
				),
			),
		),
	)
);
