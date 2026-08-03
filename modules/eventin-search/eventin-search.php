<?php
/**
 * Eventin Event Search module — wraps the Eventin [event_search_filter] shortcode.
 *
 * @package Eventin_Beaver_Themer
 */

defined( 'ABSPATH' ) || exit;

/**
 * The Eventin event search / filter form.
 */
class Eventin_BT_Search_Module extends FLBuilderModule {

	/**
	 * Constructor.
	 */
	public function __construct() {
		parent::__construct(
			array(
				'name'            => __( 'Eventin Event Search', 'eventin-beaver-themer' ),
				'description'     => __( 'The Eventin event search and filter form.', 'eventin-beaver-themer' ),
				'category'        => __( 'Eventin', 'eventin-beaver-themer' ),
				'group'           => __( 'Eventin', 'eventin-beaver-themer' ),
				'dir'             => EVENTIN_BT_DIR . 'modules/eventin-search/',
				'url'             => EVENTIN_BT_URL . 'modules/eventin-search/',
				'partial_refresh' => true,
			)
		);
	}
}

FLBuilder::register_module(
	'Eventin_BT_Search_Module',
	array(
		'general' => array(
			'title'    => __( 'General', 'eventin-beaver-themer' ),
			'sections' => array(
				'general' => array(
					'title'  => '',
					'fields' => array(
						'info' => array(
							'type' => 'raw',
							'label' => '',
							'content' => '<div class="fl-builder-info">' . esc_html__( 'This module outputs the Eventin event search and filter form. It has no settings.', 'eventin-beaver-themer' ) . '</div>',
						),
					),
				),
			),
		),
	)
);
