<?php
/**
 * Eventin Events module — wraps the Eventin [events] shortcode.
 *
 * @package Eventin_Beaver_Themer
 */

defined( 'ABSPATH' ) || exit;

/**
 * A grid/list of Eventin events.
 */
class Eventin_BT_Events_Module extends FLBuilderModule {

	/**
	 * Constructor.
	 */
	public function __construct() {
		parent::__construct(
			array(
				'name'            => __( 'Eventin Events', 'eventin-beaver-themer' ),
				'description'     => __( 'A grid or list of Eventin events.', 'eventin-beaver-themer' ),
				'category'        => __( 'Eventin', 'eventin-beaver-themer' ),
				'group'           => __( 'Eventin', 'eventin-beaver-themer' ),
				'dir'             => EVENTIN_BT_DIR . 'modules/eventin-events/',
				'url'             => EVENTIN_BT_URL . 'modules/eventin-events/',
				'partial_refresh' => true,
			)
		);
	}
}

FLBuilder::register_module(
	'Eventin_BT_Events_Module',
	array(
		'layout'  => array(
			'title'    => __( 'Layout', 'eventin-beaver-themer' ),
			'sections' => array(
				'layout' => array(
					'title'  => __( 'Layout', 'eventin-beaver-themer' ),
					'fields' => array(
						'style'         => array(
							'type'    => 'select',
							'label'   => __( 'Style', 'eventin-beaver-themer' ),
							'default' => 'event-1',
							'options' => array(
								'event-1' => __( 'Style 1', 'eventin-beaver-themer' ),
								'event-2' => __( 'Style 2', 'eventin-beaver-themer' ),
								'event-3' => __( 'Style 3', 'eventin-beaver-themer' ),
							),
						),
						'etn_event_col' => array(
							'type'    => 'select',
							'label'   => __( 'Columns', 'eventin-beaver-themer' ),
							'default' => '3',
							'options' => array(
								'1' => '1',
								'2' => '2',
								'3' => '3',
								'4' => '4',
							),
						),
					),
				),
				'fields' => array(
					'title'  => __( 'Display', 'eventin-beaver-themer' ),
					'fields' => array(
						'show_event_location'    => array(
							'type'    => 'select',
							'label'   => __( 'Show Location', 'eventin-beaver-themer' ),
							'default' => 'yes',
							'options' => array(
								'yes' => __( 'Yes', 'eventin-beaver-themer' ),
								'no'  => __( 'No', 'eventin-beaver-themer' ),
							),
						),
						'show_end_date'          => array(
							'type'    => 'select',
							'label'   => __( 'Show End Date', 'eventin-beaver-themer' ),
							'default' => 'no',
							'options' => array(
								'yes' => __( 'Yes', 'eventin-beaver-themer' ),
								'no'  => __( 'No', 'eventin-beaver-themer' ),
							),
						),
						'show_remaining_tickets' => array(
							'type'    => 'select',
							'label'   => __( 'Show Remaining Tickets', 'eventin-beaver-themer' ),
							'default' => 'no',
							'options' => array(
								'yes' => __( 'Yes', 'eventin-beaver-themer' ),
								'no'  => __( 'No', 'eventin-beaver-themer' ),
							),
						),
						'etn_desc_show'          => array(
							'type'    => 'select',
							'label'   => __( 'Show Description', 'eventin-beaver-themer' ),
							'default' => 'yes',
							'toggle'  => array(
								'yes' => array(
									'fields' => array( 'desc_limit' ),
								),
							),
							'options' => array(
								'yes' => __( 'Yes', 'eventin-beaver-themer' ),
								'no'  => __( 'No', 'eventin-beaver-themer' ),
							),
						),
						'desc_limit'             => array(
							'type'    => 'text',
							'label'   => __( 'Description Word Limit', 'eventin-beaver-themer' ),
							'default' => '20',
							'size'    => 5,
						),
					),
				),
			),
		),
		'content' => array(
			'title'    => __( 'Content', 'eventin-beaver-themer' ),
			'sections' => array(
				'query'  => array(
					'title'  => __( 'Query', 'eventin-beaver-themer' ),
					'fields' => array(
						'limit'              => array(
							'type'        => 'text',
							'label'       => __( 'Number of Events', 'eventin-beaver-themer' ),
							'default'     => '-1',
							'size'        => 5,
							'description' => __( 'Use -1 to show all events.', 'eventin-beaver-themer' ),
						),
						'filter_with_status' => array(
							'type'    => 'select',
							'label'   => __( 'Show Events', 'eventin-beaver-themer' ),
							'default' => '',
							'options' => array(
								''         => __( 'All', 'eventin-beaver-themer' ),
								'upcoming' => __( 'Upcoming', 'eventin-beaver-themer' ),
								'expire'   => __( 'Past', 'eventin-beaver-themer' ),
							),
						),
						'orderby'            => array(
							'type'    => 'select',
							'label'   => __( 'Order By', 'eventin-beaver-themer' ),
							'default' => 'etn_start_date',
							'options' => array(
								'etn_start_date' => __( 'Event Start Date', 'eventin-beaver-themer' ),
								'title'          => __( 'Title', 'eventin-beaver-themer' ),
								'post_date'      => __( 'Published Date', 'eventin-beaver-themer' ),
								'ID'             => __( 'ID', 'eventin-beaver-themer' ),
							),
						),
						'order'              => array(
							'type'    => 'select',
							'label'   => __( 'Order', 'eventin-beaver-themer' ),
							'default' => 'ASC',
							'options' => array(
								'ASC'  => __( 'Ascending', 'eventin-beaver-themer' ),
								'DESC' => __( 'Descending', 'eventin-beaver-themer' ),
							),
						),
					),
				),
				'filter' => array(
					'title'  => __( 'Filter', 'eventin-beaver-themer' ),
					'fields' => array(
						'event_cat_ids' => array(
							'type'        => 'text',
							'label'       => __( 'Category IDs', 'eventin-beaver-themer' ),
							'default'     => '',
							'description' => __( 'Comma-separated Eventin category term IDs. Leave blank for all.', 'eventin-beaver-themer' ),
						),
						'event_tag_ids' => array(
							'type'        => 'text',
							'label'       => __( 'Tag IDs', 'eventin-beaver-themer' ),
							'default'     => '',
							'description' => __( 'Comma-separated Eventin tag term IDs. Leave blank for all.', 'eventin-beaver-themer' ),
						),
					),
				),
			),
		),
	)
);
