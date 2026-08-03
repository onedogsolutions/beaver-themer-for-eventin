<?php
/**
 * Registers Eventin field connections with Beaver Themer.
 *
 * Loaded on `fl_page_data_add_properties`, so FLPageData is guaranteed to exist.
 *
 * @package Eventin_Beaver_Themer
 */

defined( 'ABSPATH' ) || exit;

/**
 * Connection group shown in the Beaver Builder field connection menu.
 */
FLPageData::add_group(
	'eventin',
	array(
		'label' => __( 'Eventin', 'eventin-beaver-themer' ),
	)
);

/**
 * Reusable "Format" settings field for date/time connections.
 *
 * @var array $eventin_bt_format_field
 */
$eventin_bt_format_field = array(
	'format' => array(
		'type'        => 'text',
		'label'       => __( 'Format', 'eventin-beaver-themer' ),
		'default'     => '',
		'size'        => '8',
		'description' => __( 'PHP date format. Leave blank to use the site default.', 'eventin-beaver-themer' ),
		'placeholder' => 'F j, Y g:i a',
	),
);

/*
 * Date & time.
 */
FLPageData::add_post_property(
	'eventin_start_datetime',
	array(
		'label'  => __( 'Event Start Date/Time', 'eventin-beaver-themer' ),
		'group'  => 'eventin',
		'type'   => 'string',
		'getter' => 'Eventin_BT_Page_Data::start_datetime',
	)
);
FLPageData::add_post_property_settings_fields( 'eventin_start_datetime', $eventin_bt_format_field );

FLPageData::add_post_property(
	'eventin_end_datetime',
	array(
		'label'  => __( 'Event End Date/Time', 'eventin-beaver-themer' ),
		'group'  => 'eventin',
		'type'   => 'string',
		'getter' => 'Eventin_BT_Page_Data::end_datetime',
	)
);
FLPageData::add_post_property_settings_fields( 'eventin_end_datetime', $eventin_bt_format_field );

FLPageData::add_post_property(
	'eventin_start_date',
	array(
		'label'  => __( 'Event Start Date', 'eventin-beaver-themer' ),
		'group'  => 'eventin',
		'type'   => 'string',
		'getter' => 'Eventin_BT_Page_Data::start_date',
	)
);
FLPageData::add_post_property_settings_fields( 'eventin_start_date', $eventin_bt_format_field );

FLPageData::add_post_property(
	'eventin_start_time',
	array(
		'label'  => __( 'Event Start Time', 'eventin-beaver-themer' ),
		'group'  => 'eventin',
		'type'   => 'string',
		'getter' => 'Eventin_BT_Page_Data::start_time',
	)
);
FLPageData::add_post_property_settings_fields( 'eventin_start_time', $eventin_bt_format_field );

FLPageData::add_post_property(
	'eventin_end_date',
	array(
		'label'  => __( 'Event End Date', 'eventin-beaver-themer' ),
		'group'  => 'eventin',
		'type'   => 'string',
		'getter' => 'Eventin_BT_Page_Data::end_date',
	)
);
FLPageData::add_post_property_settings_fields( 'eventin_end_date', $eventin_bt_format_field );

FLPageData::add_post_property(
	'eventin_end_time',
	array(
		'label'  => __( 'Event End Time', 'eventin-beaver-themer' ),
		'group'  => 'eventin',
		'type'   => 'string',
		'getter' => 'Eventin_BT_Page_Data::end_time',
	)
);
FLPageData::add_post_property_settings_fields( 'eventin_end_time', $eventin_bt_format_field );

FLPageData::add_post_property(
	'eventin_registration_deadline',
	array(
		'label'  => __( 'Registration Deadline', 'eventin-beaver-themer' ),
		'group'  => 'eventin',
		'type'   => 'string',
		'getter' => 'Eventin_BT_Page_Data::registration_deadline',
	)
);
FLPageData::add_post_property_settings_fields( 'eventin_registration_deadline', $eventin_bt_format_field );

FLPageData::add_post_property(
	'eventin_timezone',
	array(
		'label'  => __( 'Event Timezone', 'eventin-beaver-themer' ),
		'group'  => 'eventin',
		'type'   => 'string',
		'getter' => 'Eventin_BT_Page_Data::timezone',
	)
);

FLPageData::add_post_property(
	'eventin_status',
	array(
		'label'  => __( 'Event Status', 'eventin-beaver-themer' ),
		'group'  => 'eventin',
		'type'   => 'string',
		'getter' => 'Eventin_BT_Page_Data::status',
	)
);

/*
 * Location.
 */
FLPageData::add_post_property(
	'eventin_event_type',
	array(
		'label'  => __( 'Event Type', 'eventin-beaver-themer' ),
		'group'  => 'eventin',
		'type'   => 'string',
		'getter' => 'Eventin_BT_Page_Data::event_type',
	)
);

FLPageData::add_post_property(
	'eventin_address',
	array(
		'label'  => __( 'Event Address', 'eventin-beaver-themer' ),
		'group'  => 'eventin',
		'type'   => 'string',
		'getter' => 'Eventin_BT_Page_Data::address',
	)
);

FLPageData::add_post_property(
	'eventin_meeting_link',
	array(
		'label'  => __( 'Online/Meeting Link', 'eventin-beaver-themer' ),
		'group'  => 'eventin',
		'type'   => 'url',
		'getter' => 'Eventin_BT_Page_Data::meeting_link',
	)
);

/*
 * People & taxonomies.
 */
FLPageData::add_post_property(
	'eventin_organizers',
	array(
		'label'  => __( 'Event Organizers', 'eventin-beaver-themer' ),
		'group'  => 'eventin',
		'type'   => 'string',
		'getter' => 'Eventin_BT_Page_Data::organizers',
	)
);

FLPageData::add_post_property(
	'eventin_speakers',
	array(
		'label'  => __( 'Event Speakers', 'eventin-beaver-themer' ),
		'group'  => 'eventin',
		'type'   => 'string',
		'getter' => 'Eventin_BT_Page_Data::speakers',
	)
);

FLPageData::add_post_property(
	'eventin_categories',
	array(
		'label'  => __( 'Event Categories', 'eventin-beaver-themer' ),
		'group'  => 'eventin',
		'type'   => 'string',
		'getter' => 'Eventin_BT_Page_Data::categories',
	)
);

FLPageData::add_post_property(
	'eventin_tags',
	array(
		'label'  => __( 'Event Tags', 'eventin-beaver-themer' ),
		'group'  => 'eventin',
		'type'   => 'string',
		'getter' => 'Eventin_BT_Page_Data::tags',
	)
);

/*
 * Tickets.
 */
FLPageData::add_post_property(
	'eventin_ticket_price',
	array(
		'label'  => __( 'Ticket Price (from)', 'eventin-beaver-themer' ),
		'group'  => 'eventin',
		'type'   => 'string',
		'getter' => 'Eventin_BT_Page_Data::ticket_price',
	)
);
FLPageData::add_post_property_settings_fields(
	'eventin_ticket_price',
	array(
		'show_currency' => array(
			'type'    => 'select',
			'label'   => __( 'Show Currency', 'eventin-beaver-themer' ),
			'default' => '1',
			'options' => array(
				'1' => __( 'Yes', 'eventin-beaver-themer' ),
				'0' => __( 'No', 'eventin-beaver-themer' ),
			),
		),
	)
);

FLPageData::add_post_property(
	'eventin_total_tickets',
	array(
		'label'  => __( 'Total Tickets', 'eventin-beaver-themer' ),
		'group'  => 'eventin',
		'type'   => 'string',
		'getter' => 'Eventin_BT_Page_Data::total_tickets',
	)
);

FLPageData::add_post_property(
	'eventin_tickets_sold',
	array(
		'label'  => __( 'Tickets Sold', 'eventin-beaver-themer' ),
		'group'  => 'eventin',
		'type'   => 'string',
		'getter' => 'Eventin_BT_Page_Data::tickets_sold',
	)
);

/*
 * Media.
 */
FLPageData::add_post_property(
	'eventin_logo',
	array(
		'label'  => __( 'Event Logo', 'eventin-beaver-themer' ),
		'group'  => 'eventin',
		'type'   => 'photo',
		'getter' => 'Eventin_BT_Page_Data::logo_id',
	)
);

FLPageData::add_post_property(
	'eventin_banner',
	array(
		'label'  => __( 'Event Banner', 'eventin-beaver-themer' ),
		'group'  => 'eventin',
		'type'   => 'photo',
		'getter' => 'Eventin_BT_Page_Data::banner_id',
	)
);
