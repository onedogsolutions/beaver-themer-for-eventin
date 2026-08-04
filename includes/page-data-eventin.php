<?php
/**
 * Registers Eventin field connections with Beaver Themer.
 *
 * Loaded on `fl_page_data_add_properties`, so FLPageData is guaranteed to exist.
 * All labels go through Eventin_BT_Labels::get() for site-level customization.
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
		'label' => Eventin_BT_Labels::get( 'group_name' ),
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
		'label'       => Eventin_BT_Labels::get( 'format' ),
		'default'     => '',
		'size'        => '8',
		'description' => __( 'PHP date format. Leave blank to use the site default.', 'eventin-beaver-themer' ),
		'placeholder' => 'F j, Y g:i a',
	),
);

/**
 * Reusable currency toggle settings field.
 *
 * @var array $eventin_bt_currency_field
 */
$eventin_bt_currency_field = array(
	'show_currency' => array(
		'type'    => 'select',
		'label'   => Eventin_BT_Labels::get( 'show_currency' ),
		'default' => '1',
		'options' => array(
			'1' => __( 'Yes', 'eventin-beaver-themer' ),
			'0' => __( 'No', 'eventin-beaver-themer' ),
		),
	),
);

/**
 * Reusable index selector settings field for indexed connections.
 *
 * @var array $eventin_bt_index_field
 */
$eventin_bt_index_field = array(
	'index' => array(
		'type'    => 'select',
		'label'   => Eventin_BT_Labels::get( 'item_selector' ),
		'default' => '0',
		'options' => array(
			'0' => __( 'First', 'eventin-beaver-themer' ),
			'1' => __( 'Second', 'eventin-beaver-themer' ),
			'2' => __( 'Third', 'eventin-beaver-themer' ),
			'3' => __( 'Fourth', 'eventin-beaver-themer' ),
			'4' => __( 'Fifth', 'eventin-beaver-themer' ),
			'5' => __( 'Sixth', 'eventin-beaver-themer' ),
			'6' => __( 'Seventh', 'eventin-beaver-themer' ),
			'7' => __( 'Eighth', 'eventin-beaver-themer' ),
			'8' => __( 'Ninth', 'eventin-beaver-themer' ),
			'9' => __( 'Tenth', 'eventin-beaver-themer' ),
		),
	),
);

/*
 * ---------------------------------------------------------------
 * Date & time
 * ---------------------------------------------------------------
 */
FLPageData::add_post_property( 'eventin_start_datetime', array(
	'label'  => Eventin_BT_Labels::get( 'event_start_datetime' ),
	'group'  => 'eventin',
	'type'   => 'string',
	'getter' => 'Eventin_BT_Page_Data::start_datetime',
) );
FLPageData::add_post_property_settings_fields( 'eventin_start_datetime', $eventin_bt_format_field );

FLPageData::add_post_property( 'eventin_end_datetime', array(
	'label'  => Eventin_BT_Labels::get( 'event_end_datetime' ),
	'group'  => 'eventin',
	'type'   => 'string',
	'getter' => 'Eventin_BT_Page_Data::end_datetime',
) );
FLPageData::add_post_property_settings_fields( 'eventin_end_datetime', $eventin_bt_format_field );

FLPageData::add_post_property( 'eventin_start_date', array(
	'label'  => Eventin_BT_Labels::get( 'event_start_date' ),
	'group'  => 'eventin',
	'type'   => 'string',
	'getter' => 'Eventin_BT_Page_Data::start_date',
) );
FLPageData::add_post_property_settings_fields( 'eventin_start_date', $eventin_bt_format_field );

FLPageData::add_post_property( 'eventin_start_time', array(
	'label'  => Eventin_BT_Labels::get( 'event_start_time' ),
	'group'  => 'eventin',
	'type'   => 'string',
	'getter' => 'Eventin_BT_Page_Data::start_time',
) );
FLPageData::add_post_property_settings_fields( 'eventin_start_time', $eventin_bt_format_field );

FLPageData::add_post_property( 'eventin_end_date', array(
	'label'  => Eventin_BT_Labels::get( 'event_end_date' ),
	'group'  => 'eventin',
	'type'   => 'string',
	'getter' => 'Eventin_BT_Page_Data::end_date',
) );
FLPageData::add_post_property_settings_fields( 'eventin_end_date', $eventin_bt_format_field );

FLPageData::add_post_property( 'eventin_end_time', array(
	'label'  => Eventin_BT_Labels::get( 'event_end_time' ),
	'group'  => 'eventin',
	'type'   => 'string',
	'getter' => 'Eventin_BT_Page_Data::end_time',
) );
FLPageData::add_post_property_settings_fields( 'eventin_end_time', $eventin_bt_format_field );

FLPageData::add_post_property( 'eventin_registration_deadline', array(
	'label'  => Eventin_BT_Labels::get( 'registration_deadline' ),
	'group'  => 'eventin',
	'type'   => 'string',
	'getter' => 'Eventin_BT_Page_Data::registration_deadline',
) );
FLPageData::add_post_property_settings_fields( 'eventin_registration_deadline', $eventin_bt_format_field );

FLPageData::add_post_property( 'eventin_timezone', array(
	'label'  => Eventin_BT_Labels::get( 'event_timezone' ),
	'group'  => 'eventin',
	'type'   => 'string',
	'getter' => 'Eventin_BT_Page_Data::timezone',
) );

FLPageData::add_post_property( 'eventin_status', array(
	'label'  => Eventin_BT_Labels::get( 'event_status' ),
	'group'  => 'eventin',
	'type'   => 'string',
	'getter' => 'Eventin_BT_Page_Data::status',
) );

/*
 * ---------------------------------------------------------------
 * Location
 * ---------------------------------------------------------------
 */
FLPageData::add_post_property( 'eventin_event_type', array(
	'label'  => Eventin_BT_Labels::get( 'event_type' ),
	'group'  => 'eventin',
	'type'   => 'string',
	'getter' => 'Eventin_BT_Page_Data::event_type',
) );

FLPageData::add_post_property( 'eventin_address', array(
	'label'  => Eventin_BT_Labels::get( 'event_address' ),
	'group'  => 'eventin',
	'type'   => 'string',
	'getter' => 'Eventin_BT_Page_Data::address',
) );

FLPageData::add_post_property( 'eventin_latitude', array(
	'label'  => Eventin_BT_Labels::get( 'latitude' ),
	'group'  => 'eventin',
	'type'   => 'string',
	'getter' => 'Eventin_BT_Page_Data::latitude',
) );

FLPageData::add_post_property( 'eventin_longitude', array(
	'label'  => Eventin_BT_Labels::get( 'longitude' ),
	'group'  => 'eventin',
	'type'   => 'string',
	'getter' => 'Eventin_BT_Page_Data::longitude',
) );

FLPageData::add_post_property( 'eventin_map_url', array(
	'label'  => Eventin_BT_Labels::get( 'map_url' ),
	'group'  => 'eventin',
	'type'   => 'url',
	'getter' => 'Eventin_BT_Page_Data::map_url',
) );

FLPageData::add_post_property( 'eventin_location_type', array(
	'label'  => Eventin_BT_Labels::get( 'location_type' ),
	'group'  => 'eventin',
	'type'   => 'string',
	'getter' => 'Eventin_BT_Page_Data::location_type',
) );

FLPageData::add_post_property( 'eventin_meeting_link', array(
	'label'  => Eventin_BT_Labels::get( 'meeting_link' ),
	'group'  => 'eventin',
	'type'   => 'url',
	'getter' => 'Eventin_BT_Page_Data::meeting_link',
) );

FLPageData::add_post_property( 'eventin_external_link', array(
	'label'  => Eventin_BT_Labels::get( 'external_link' ),
	'group'  => 'eventin',
	'type'   => 'url',
	'getter' => 'Eventin_BT_Page_Data::external_link',
) );

/*
 * ---------------------------------------------------------------
 * People & taxonomies
 * ---------------------------------------------------------------
 */
FLPageData::add_post_property( 'eventin_organizers', array(
	'label'  => Eventin_BT_Labels::get( 'event_organizers' ),
	'group'  => 'eventin',
	'type'   => 'string',
	'getter' => 'Eventin_BT_Page_Data::organizers',
) );

FLPageData::add_post_property( 'eventin_speakers', array(
	'label'  => Eventin_BT_Labels::get( 'event_speakers' ),
	'group'  => 'eventin',
	'type'   => 'string',
	'getter' => 'Eventin_BT_Page_Data::speakers',
) );

FLPageData::add_post_property( 'eventin_categories', array(
	'label'  => Eventin_BT_Labels::get( 'event_categories' ),
	'group'  => 'eventin',
	'type'   => 'string',
	'getter' => 'Eventin_BT_Page_Data::categories',
) );

FLPageData::add_post_property( 'eventin_tags', array(
	'label'  => Eventin_BT_Labels::get( 'event_tags' ),
	'group'  => 'eventin',
	'type'   => 'string',
	'getter' => 'Eventin_BT_Page_Data::tags',
) );

FLPageData::add_post_property( 'eventin_speaker_count', array(
	'label'  => Eventin_BT_Labels::get( 'speaker_count' ),
	'group'  => 'eventin',
	'type'   => 'string',
	'getter' => 'Eventin_BT_Page_Data::speaker_count',
) );

FLPageData::add_post_property( 'eventin_organizer_count', array(
	'label'  => Eventin_BT_Labels::get( 'organizer_count' ),
	'group'  => 'eventin',
	'type'   => 'string',
	'getter' => 'Eventin_BT_Page_Data::organizer_count',
) );

/*
 * ---------------------------------------------------------------
 * Tickets (aggregate)
 * ---------------------------------------------------------------
 */
FLPageData::add_post_property( 'eventin_ticket_price', array(
	'label'  => Eventin_BT_Labels::get( 'ticket_price_from' ),
	'group'  => 'eventin',
	'type'   => 'string',
	'getter' => 'Eventin_BT_Page_Data::ticket_price',
) );
FLPageData::add_post_property_settings_fields( 'eventin_ticket_price', $eventin_bt_currency_field );

FLPageData::add_post_property( 'eventin_max_ticket_price', array(
	'label'  => Eventin_BT_Labels::get( 'max_ticket_price' ),
	'group'  => 'eventin',
	'type'   => 'string',
	'getter' => 'Eventin_BT_Page_Data::max_ticket_price',
) );
FLPageData::add_post_property_settings_fields( 'eventin_max_ticket_price', $eventin_bt_currency_field );

FLPageData::add_post_property( 'eventin_ticket_price_range', array(
	'label'  => Eventin_BT_Labels::get( 'ticket_price_range' ),
	'group'  => 'eventin',
	'type'   => 'string',
	'getter' => 'Eventin_BT_Page_Data::ticket_price_range',
) );
FLPageData::add_post_property_settings_fields( 'eventin_ticket_price_range', $eventin_bt_currency_field );

FLPageData::add_post_property( 'eventin_total_tickets', array(
	'label'  => Eventin_BT_Labels::get( 'total_tickets' ),
	'group'  => 'eventin',
	'type'   => 'string',
	'getter' => 'Eventin_BT_Page_Data::total_tickets',
) );

FLPageData::add_post_property( 'eventin_tickets_sold', array(
	'label'  => Eventin_BT_Labels::get( 'tickets_sold' ),
	'group'  => 'eventin',
	'type'   => 'string',
	'getter' => 'Eventin_BT_Page_Data::tickets_sold',
) );

FLPageData::add_post_property( 'eventin_remaining_tickets', array(
	'label'  => Eventin_BT_Labels::get( 'remaining_tickets' ),
	'group'  => 'eventin',
	'type'   => 'string',
	'getter' => 'Eventin_BT_Page_Data::remaining_tickets',
) );

FLPageData::add_post_property( 'eventin_ticket_count', array(
	'label'  => Eventin_BT_Labels::get( 'ticket_count' ),
	'group'  => 'eventin',
	'type'   => 'string',
	'getter' => 'Eventin_BT_Page_Data::ticket_count',
) );

/*
 * ---------------------------------------------------------------
 * Misc
 * ---------------------------------------------------------------
 */
FLPageData::add_post_property( 'eventin_is_recurring', array(
	'label'  => Eventin_BT_Labels::get( 'is_recurring' ),
	'group'  => 'eventin',
	'type'   => 'string',
	'getter' => 'Eventin_BT_Page_Data::is_recurring',
) );

FLPageData::add_post_property( 'eventin_event_url', array(
	'label'  => Eventin_BT_Labels::get( 'event_url' ),
	'group'  => 'eventin',
	'type'   => 'url',
	'getter' => 'Eventin_BT_Page_Data::event_url',
) );

/*
 * ---------------------------------------------------------------
 * Media
 * ---------------------------------------------------------------
 */
FLPageData::add_post_property( 'eventin_logo', array(
	'label'  => Eventin_BT_Labels::get( 'event_logo' ),
	'group'  => 'eventin',
	'type'   => 'photo',
	'getter' => 'Eventin_BT_Page_Data::logo_id',
) );

FLPageData::add_post_property( 'eventin_banner', array(
	'label'  => Eventin_BT_Labels::get( 'event_banner' ),
	'group'  => 'eventin',
	'type'   => 'photo',
	'getter' => 'Eventin_BT_Page_Data::banner_id',
) );

/*
 * ---------------------------------------------------------------
 * Speaker (indexed)
 * ---------------------------------------------------------------
 */
FLPageData::add_post_property( 'eventin_speaker_name', array(
	'label'  => Eventin_BT_Labels::get( 'speaker_name' ),
	'group'  => 'eventin',
	'type'   => 'string',
	'getter' => 'Eventin_BT_Page_Data::speaker_name',
) );
FLPageData::add_post_property_settings_fields( 'eventin_speaker_name', $eventin_bt_index_field );

FLPageData::add_post_property( 'eventin_speaker_photo', array(
	'label'  => Eventin_BT_Labels::get( 'speaker_photo' ),
	'group'  => 'eventin',
	'type'   => 'photo',
	'getter' => 'Eventin_BT_Page_Data::speaker_photo',
) );
FLPageData::add_post_property_settings_fields( 'eventin_speaker_photo', $eventin_bt_index_field );

FLPageData::add_post_property( 'eventin_speaker_designation', array(
	'label'  => Eventin_BT_Labels::get( 'speaker_designation' ),
	'group'  => 'eventin',
	'type'   => 'string',
	'getter' => 'Eventin_BT_Page_Data::speaker_designation',
) );
FLPageData::add_post_property_settings_fields( 'eventin_speaker_designation', $eventin_bt_index_field );

FLPageData::add_post_property( 'eventin_speaker_company', array(
	'label'  => Eventin_BT_Labels::get( 'speaker_company' ),
	'group'  => 'eventin',
	'type'   => 'string',
	'getter' => 'Eventin_BT_Page_Data::speaker_company',
) );
FLPageData::add_post_property_settings_fields( 'eventin_speaker_company', $eventin_bt_index_field );

FLPageData::add_post_property( 'eventin_speaker_bio', array(
	'label'  => Eventin_BT_Labels::get( 'speaker_bio' ),
	'group'  => 'eventin',
	'type'   => 'string',
	'getter' => 'Eventin_BT_Page_Data::speaker_bio',
) );
FLPageData::add_post_property_settings_fields( 'eventin_speaker_bio', $eventin_bt_index_field );

FLPageData::add_post_property( 'eventin_speaker_website', array(
	'label'  => Eventin_BT_Labels::get( 'speaker_website' ),
	'group'  => 'eventin',
	'type'   => 'url',
	'getter' => 'Eventin_BT_Page_Data::speaker_website',
) );
FLPageData::add_post_property_settings_fields( 'eventin_speaker_website', $eventin_bt_index_field );

FLPageData::add_post_property( 'eventin_speaker_email', array(
	'label'  => Eventin_BT_Labels::get( 'speaker_email' ),
	'group'  => 'eventin',
	'type'   => 'string',
	'getter' => 'Eventin_BT_Page_Data::speaker_email',
) );
FLPageData::add_post_property_settings_fields( 'eventin_speaker_email', $eventin_bt_index_field );

FLPageData::add_post_property( 'eventin_speaker_company_logo', array(
	'label'  => Eventin_BT_Labels::get( 'speaker_company_logo' ),
	'group'  => 'eventin',
	'type'   => 'photo',
	'getter' => 'Eventin_BT_Page_Data::speaker_company_logo',
) );
FLPageData::add_post_property_settings_fields( 'eventin_speaker_company_logo', $eventin_bt_index_field );

/*
 * ---------------------------------------------------------------
 * Organizer (indexed)
 * ---------------------------------------------------------------
 */
FLPageData::add_post_property( 'eventin_organizer_name', array(
	'label'  => Eventin_BT_Labels::get( 'organizer_name' ),
	'group'  => 'eventin',
	'type'   => 'string',
	'getter' => 'Eventin_BT_Page_Data::organizer_name',
) );
FLPageData::add_post_property_settings_fields( 'eventin_organizer_name', $eventin_bt_index_field );

FLPageData::add_post_property( 'eventin_organizer_photo', array(
	'label'  => Eventin_BT_Labels::get( 'organizer_photo' ),
	'group'  => 'eventin',
	'type'   => 'photo',
	'getter' => 'Eventin_BT_Page_Data::organizer_photo',
) );
FLPageData::add_post_property_settings_fields( 'eventin_organizer_photo', $eventin_bt_index_field );

FLPageData::add_post_property( 'eventin_organizer_email', array(
	'label'  => Eventin_BT_Labels::get( 'organizer_email' ),
	'group'  => 'eventin',
	'type'   => 'string',
	'getter' => 'Eventin_BT_Page_Data::organizer_email',
) );
FLPageData::add_post_property_settings_fields( 'eventin_organizer_email', $eventin_bt_index_field );

FLPageData::add_post_property( 'eventin_organizer_phone', array(
	'label'  => Eventin_BT_Labels::get( 'organizer_phone' ),
	'group'  => 'eventin',
	'type'   => 'string',
	'getter' => 'Eventin_BT_Page_Data::organizer_phone',
) );
FLPageData::add_post_property_settings_fields( 'eventin_organizer_phone', $eventin_bt_index_field );

FLPageData::add_post_property( 'eventin_organizer_website', array(
	'label'  => Eventin_BT_Labels::get( 'organizer_website' ),
	'group'  => 'eventin',
	'type'   => 'url',
	'getter' => 'Eventin_BT_Page_Data::organizer_website',
) );
FLPageData::add_post_property_settings_fields( 'eventin_organizer_website', $eventin_bt_index_field );

FLPageData::add_post_property( 'eventin_organizer_company', array(
	'label'  => Eventin_BT_Labels::get( 'organizer_company' ),
	'group'  => 'eventin',
	'type'   => 'string',
	'getter' => 'Eventin_BT_Page_Data::organizer_company',
) );
FLPageData::add_post_property_settings_fields( 'eventin_organizer_company', $eventin_bt_index_field );

FLPageData::add_post_property( 'eventin_organizer_bio', array(
	'label'  => Eventin_BT_Labels::get( 'organizer_bio' ),
	'group'  => 'eventin',
	'type'   => 'string',
	'getter' => 'Eventin_BT_Page_Data::organizer_bio',
) );
FLPageData::add_post_property_settings_fields( 'eventin_organizer_bio', $eventin_bt_index_field );

/*
 * ---------------------------------------------------------------
 * Ticket (indexed)
 * ---------------------------------------------------------------
 */
FLPageData::add_post_property( 'eventin_ticket_name', array(
	'label'  => Eventin_BT_Labels::get( 'ticket_name' ),
	'group'  => 'eventin',
	'type'   => 'string',
	'getter' => 'Eventin_BT_Page_Data::ticket_name',
) );
FLPageData::add_post_property_settings_fields( 'eventin_ticket_name', $eventin_bt_index_field );

FLPageData::add_post_property( 'eventin_ticket_price_item', array(
	'label'  => Eventin_BT_Labels::get( 'ticket_price' ),
	'group'  => 'eventin',
	'type'   => 'string',
	'getter' => 'Eventin_BT_Page_Data::ticket_price_item',
) );
$eventin_bt_index_currency_field = array_merge( $eventin_bt_index_field, $eventin_bt_currency_field );
FLPageData::add_post_property_settings_fields( 'eventin_ticket_price_item', $eventin_bt_index_currency_field );

FLPageData::add_post_property( 'eventin_ticket_available', array(
	'label'  => Eventin_BT_Labels::get( 'ticket_available' ),
	'group'  => 'eventin',
	'type'   => 'string',
	'getter' => 'Eventin_BT_Page_Data::ticket_available',
) );
FLPageData::add_post_property_settings_fields( 'eventin_ticket_available', $eventin_bt_index_field );

FLPageData::add_post_property( 'eventin_ticket_sold_item', array(
	'label'  => Eventin_BT_Labels::get( 'ticket_sold_item' ),
	'group'  => 'eventin',
	'type'   => 'string',
	'getter' => 'Eventin_BT_Page_Data::ticket_sold_item',
) );
FLPageData::add_post_property_settings_fields( 'eventin_ticket_sold_item', $eventin_bt_index_field );

FLPageData::add_post_property( 'eventin_ticket_remaining', array(
	'label'  => Eventin_BT_Labels::get( 'ticket_remaining' ),
	'group'  => 'eventin',
	'type'   => 'string',
	'getter' => 'Eventin_BT_Page_Data::ticket_remaining',
) );
FLPageData::add_post_property_settings_fields( 'eventin_ticket_remaining', $eventin_bt_index_field );

/*
 * ---------------------------------------------------------------
 * FAQ (indexed)
 * ---------------------------------------------------------------
 */
FLPageData::add_post_property( 'eventin_faq_question', array(
	'label'  => Eventin_BT_Labels::get( 'faq_question' ),
	'group'  => 'eventin',
	'type'   => 'string',
	'getter' => 'Eventin_BT_Page_Data::faq_question',
) );
FLPageData::add_post_property_settings_fields( 'eventin_faq_question', $eventin_bt_index_field );

FLPageData::add_post_property( 'eventin_faq_answer', array(
	'label'  => Eventin_BT_Labels::get( 'faq_answer' ),
	'group'  => 'eventin',
	'type'   => 'string',
	'getter' => 'Eventin_BT_Page_Data::faq_answer',
) );
FLPageData::add_post_property_settings_fields( 'eventin_faq_answer', $eventin_bt_index_field );
