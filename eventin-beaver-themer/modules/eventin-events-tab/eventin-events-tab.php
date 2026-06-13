<?php
/**
 * Eventin Events Tab module — wraps the Eventin [events_tab] shortcode.
 *
 * @package Eventin_Beaver_Themer
 */

defined( 'ABSPATH' ) || exit;

/**
 * Events grouped into category tabs.
 */
class Eventin_BT_Events_Tab_Module extends FLBuilderModule {

	/**
	 * Constructor.
	 */
	public function __construct() {
		parent::__construct(
			array(
				'name'            => __( 'Eventin Events Tab', 'eventin-beaver-themer' ),
				'description'     => __( 'Eventin events grouped into category tabs.', 'eventin-beaver-themer' ),
				'category'        => __( 'Eventin', 'eventin-beaver-themer' ),
				'group'           => __( 'Eventin', 'eventin-beaver-themer' ),
				'dir'             => EVENTIN_BT_DIR . 'modules/eventin-events-tab/',
				'url'             => EVENTIN_BT_URL . 'modules/eventin-events-tab/',
				'partial_refresh' => true,
			)
		);
	}
}

FLBuilder::register_module(
	'Eventin_BT_Events_Tab_Module',
	array(
		'general' => array(
			'title'    => __( 'General', 'eventin-beaver-themer' ),
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
						'show_event_location' => array(
							'type'    => 'select',
							'label'   => __( 'Show Location', 'eventin-beaver-themer' ),
							'default' => 'yes',
							'options' => array(
								'yes' => __( 'Yes', 'eventin-beaver-themer' ),
								'no'  => __( 'No', 'eventin-beaver-themer' ),
							),
						),
						'show_end_date'       => array(
							'type'    => 'select',
							'label'   => __( 'Show End Date', 'eventin-beaver-themer' ),
							'default' => 'no',
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
							'label'   => __( 'Events Per Tab', 'eventin-beaver-themer' ),
							'default' => '3',
							'size'    => 5,
						),
						'orderby'       => array(
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
						'order'         => array(
							'type'    => 'select',
							'label'   => __( 'Order', 'eventin-beaver-themer' ),
							'default' => 'ASC',
							'options' => array(
								'ASC'  => __( 'Ascending', 'eventin-beaver-themer' ),
								'DESC' => __( 'Descending', 'eventin-beaver-themer' ),
							),
						),
						'event_cat_ids' => array(
							'type'        => 'text',
							'label'       => __( 'Category IDs', 'eventin-beaver-themer' ),
							'default'     => '',
							'description' => __( 'Comma-separated category term IDs. Leave blank for all.', 'eventin-beaver-themer' ),
						),
						'event_tag_ids' => array(
							'type'        => 'text',
							'label'       => __( 'Tag IDs', 'eventin-beaver-themer' ),
							'default'     => '',
							'description' => __( 'Comma-separated tag term IDs. Leave blank for all.', 'eventin-beaver-themer' ),
						),
					),
				),
			),
		),
	)
);
