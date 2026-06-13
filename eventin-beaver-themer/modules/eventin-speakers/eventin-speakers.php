<?php
/**
 * Eventin Speakers module — wraps the Eventin [speakers] shortcode.
 *
 * @package Eventin_Beaver_Themer
 */

defined( 'ABSPATH' ) || exit;

/**
 * A grid of Eventin speakers.
 */
class Eventin_BT_Speakers_Module extends FLBuilderModule {

	/**
	 * Constructor.
	 */
	public function __construct() {
		parent::__construct(
			array(
				'name'            => __( 'Eventin Speakers', 'eventin-beaver-themer' ),
				'description'     => __( 'A grid of Eventin speakers.', 'eventin-beaver-themer' ),
				'category'        => __( 'Eventin', 'eventin-beaver-themer' ),
				'group'           => __( 'Eventin', 'eventin-beaver-themer' ),
				'dir'             => EVENTIN_BT_DIR . 'modules/eventin-speakers/',
				'url'             => EVENTIN_BT_URL . 'modules/eventin-speakers/',
				'partial_refresh' => true,
			)
		);
	}
}

FLBuilder::register_module(
	'Eventin_BT_Speakers_Module',
	array(
		'general' => array(
			'title'    => __( 'General', 'eventin-beaver-themer' ),
			'sections' => array(
				'layout' => array(
					'title'  => __( 'Layout', 'eventin-beaver-themer' ),
					'fields' => array(
						'style'           => array(
							'type'    => 'select',
							'label'   => __( 'Style', 'eventin-beaver-themer' ),
							'default' => 'speaker-1',
							'options' => array(
								'speaker-1' => __( 'Style 1', 'eventin-beaver-themer' ),
								'speaker-2' => __( 'Style 2', 'eventin-beaver-themer' ),
							),
						),
						'etn_speaker_col' => array(
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
				'query'  => array(
					'title'  => __( 'Query', 'eventin-beaver-themer' ),
					'fields' => array(
						'limit'   => array(
							'type'    => 'text',
							'label'   => __( 'Number of Speakers', 'eventin-beaver-themer' ),
							'default' => '3',
							'size'    => 5,
						),
						'cat_id'  => array(
							'type'        => 'text',
							'label'       => __( 'Speaker Category IDs', 'eventin-beaver-themer' ),
							'default'     => '',
							'description' => __( 'Comma-separated speaker category term IDs. Leave blank for all.', 'eventin-beaver-themer' ),
						),
						'orderby' => array(
							'type'    => 'select',
							'label'   => __( 'Order By', 'eventin-beaver-themer' ),
							'default' => 'title',
							'options' => array(
								'title'     => __( 'Title', 'eventin-beaver-themer' ),
								'ID'        => __( 'ID', 'eventin-beaver-themer' ),
								'post_date' => __( 'Published Date', 'eventin-beaver-themer' ),
							),
						),
						'order'   => array(
							'type'    => 'select',
							'label'   => __( 'Order', 'eventin-beaver-themer' ),
							'default' => 'DESC',
							'options' => array(
								'ASC'  => __( 'Ascending', 'eventin-beaver-themer' ),
								'DESC' => __( 'Descending', 'eventin-beaver-themer' ),
							),
						),
					),
				),
			),
		),
	)
);
