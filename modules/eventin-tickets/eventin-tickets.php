<?php
/**
 * Eventin Event Tickets module — renders an event's ticket / registration form.
 *
 * @package Eventin_Beaver_Themer
 */

defined( 'ABSPATH' ) || exit;

/**
 * Outputs the Eventin purchase / RSVP form for an event, for use on single
 * event Themer layouts. Uses Eventin's own ticket form, so it works with
 * Eventin's native cart or any optional checkout integration (WooCommerce,
 * FluentCart) without changes here.
 */
class Eventin_BT_Tickets_Module extends FLBuilderModule {

	/**
	 * Constructor.
	 */
	public function __construct() {
		parent::__construct(
			array(
				'name'            => 'Eventin ' . Eventin_BT_Labels::get( 'event' ) . ' ' . Eventin_BT_Labels::get( 'tickets' ),
				'description'     => __( 'The ticket / registration form for an event.', 'eventin-beaver-themer' ),
				'category'        => Eventin_BT_Labels::get( 'module_category' ),
				'group'           => Eventin_BT_Labels::get( 'module_group' ),
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
