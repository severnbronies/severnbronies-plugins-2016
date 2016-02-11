<?php 

if(function_exists("register_field_group"))
{
	register_field_group(array (
		'id' => 'acf_location-details',
		'title' => 'Location Details',
		'fields' => array (
			array (
				'key' => 'field_5330011b130fb',
				'label' => 'Address',
				'name' => 'location_address',
				'type' => 'google_map',
				'instructions' => 'Drop a pin for this location',
				'center_lat' => '51.4481083',
				'center_lng' => '-2.5835877',
				'zoom' => 12,
				'height' => '',
			),
		),
		'location' => array (
			array (
				array (
					'param' => 'post_type',
					'operator' => '==',
					'value' => 'location',
					'order_no' => 0,
					'group_no' => 0,
				),
			),
		),
		'options' => array (
			'position' => 'normal',
			'layout' => 'default',
			'hide_on_screen' => array (
				0 => 'custom_fields',
			),
		),
		'menu_order' => 0,
	));
	register_field_group(array (
		'id' => 'acf_meet-details',
		'title' => 'Meet Details',
		'fields' => array (
			array (
				'key' => 'field_53bda6ededcb8',
				'label' => 'Meet Notes',
				'name' => 'meet_notes',
				'type' => 'textarea',
				'instructions' => 'Notes on this meet. This content is <strong>NOT</strong> rendered on the front-end of the site, and is here merely for reference and planning purposes.',
				'default_value' => '',
				'placeholder' => '',
				'maxlength' => '',
				'rows' => '',
				'formatting' => 'none',
			),
			array (
				'key' => 'field_532f5918221fb',
				'label' => 'Start Time',
				'name' => 'meet_start_time',
				'type' => 'date_time_picker',
				'instructions' => 'The starting time and date for the meet.',
				'required' => 1,
				'show_date' => 'true',
				'date_format' => 'dd/mm/yy',
				'time_format' => 'h:mm tt',
				'show_week_number' => 'false',
				'picker' => 'slider',
				'save_as_timestamp' => 'true',
				'get_as_timestamp' => 'true',
			),
			array (
				'key' => 'field_532f59a1221fc',
				'label' => 'End Time',
				'name' => 'meet_end_time',
				'type' => 'date_time_picker',
				'instructions' => 'The ending time and date for the meet.',
				'required' => 1,
				'show_date' => 'true',
				'date_format' => 'dd/mm/yy',
				'time_format' => 'h:mm tt',
				'show_week_number' => 'false',
				'picker' => 'slider',
				'save_as_timestamp' => 'true',
				'get_as_timestamp' => 'true',
			),
			array (
				'key' => 'field_533001b69737a',
				'label' => 'Location',
				'name' => 'meet_location',
				'type' => 'relationship',
				'instructions' => 'Where this meet is. ',
				'required' => 1,
				'return_format' => 'id',
				'post_type' => array (
					0 => 'location',
				),
				'taxonomy' => array (
					0 => 'all',
				),
				'filters' => array (
					0 => 'search',
				),
				'result_elements' => array (
					0 => 'post_title',
				),
				'max' => '',
			),
			array (
				'key' => 'field_53300fe71d850',
				'label' => 'Meet Runner',
				'name' => 'meet_runner',
				'type' => 'relationship',
				'instructions' => 'Who\'s in charge of this shindig?',
				'required' => 1,
				'return_format' => 'id',
				'post_type' => array (
					0 => 'meet_runner',
				),
				'taxonomy' => array (
					0 => 'all',
				),
				'filters' => array (
					0 => 'search',
				),
				'result_elements' => array (
					0 => 'post_title',
				),
				'max' => '',
			),
		),
		'location' => array (
			array (
				array (
					'param' => 'post_type',
					'operator' => '==',
					'value' => 'meet',
					'order_no' => 0,
					'group_no' => 0,
				),
			),
		),
		'options' => array (
			'position' => 'normal',
			'layout' => 'default',
			'hide_on_screen' => array (
				0 => 'custom_fields',
			),
		),
		'menu_order' => 0,
	));
	register_field_group(array (
		'id' => 'acf_meet-runner-metadata',
		'title' => 'Meet Runner Metadata',
		'fields' => array (
			array (
				'key' => 'field_53384f1437d30',
				'label' => 'Is this person a current Severn Bronies staff member?',
				'name' => 'runner_staff',
				'type' => 'checkbox',
				'choices' => array (
					'true' => 'Yes, this person is a current Severn Bronies staff member!',
				),
				'default_value' => '',
				'layout' => 'vertical',
			),
		),
		'location' => array (
			array (
				array (
					'param' => 'post_type',
					'operator' => '==',
					'value' => 'meet_runner',
					'order_no' => 0,
					'group_no' => 0,
				),
			),
		),
		'options' => array (
			'position' => 'side',
			'layout' => 'default',
			'hide_on_screen' => array (
				0 => 'custom_fields',
			),
		),
		'menu_order' => 0,
	));
	register_field_group(array (
		'id' => 'acf_meet-runner-profile',
		'title' => 'Meet Runner Profile',
		'fields' => array (
			array (
				'key' => 'field_53300f71d84e2',
				'label' => 'Small Avatar',
				'name' => 'runner_avatar',
				'type' => 'image_crop',
				'instructions' => 'To upload a new avatar, delete the existing image (by hovering over the image below and clicking the × symbol) and upload a new one. ',
				'crop_type' => 'hard',
				'target_size' => 'custom',
				'width' => 150,
				'height' => 150,
				'preview_size' => 'medium',
				'force_crop' => 'yes',
				'save_in_media_library' => 'yes',
				'retina_mode' => 'no',
				'save_format' => 'url',
			),
			array (
				'key' => 'field_562e6c282319c',
				'label' => 'Links',
				'name' => 'runner_links',
				'type' => 'textarea',
				'instructions' => 'URLs for social media or other websites you want to show off. One per line.',
				'default_value' => '',
				'placeholder' => "http://severnbronies.co.uk\nhttp://twitter.com/severnbronies\nhttp://facebook.com/severnbronies",
				'maxlength' => '',
				'rows' => 5,
				'formatting' => 'none',
			),
		),
		'location' => array (
			array (
				array (
					'param' => 'post_type',
					'operator' => '==',
					'value' => 'meet_runner',
					'order_no' => 0,
					'group_no' => 0,
				),
			),
		),
		'options' => array (
			'position' => 'normal',
			'layout' => 'default',
			'hide_on_screen' => array (
				0 => 'custom_fields',
			),
		),
		'menu_order' => 0,
	));
}
