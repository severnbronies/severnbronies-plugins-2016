<?php

/**
 * Create the 'meet runner' post type. 
 */
function sb_runner_post_type() {
	$labels = array(
		"name" => _x("Meet Runners", "post type general name"),
		"singular_name" => _x("Meet Runner", "post type singular name"),
		"add_new" => _x("Add New", "book"),
		"add_new_item" => __("Add New Meet Runner"),
		"edit_item" => __("Edit Meet Runner"),
		"new_item" => __("New Meet Runner"),
		"all_items" => __("All Meet Runners"),
		"view_item" => __("View Meet Runner"),
		"search_items" => __("Search Meet Runners"),
		"not_found" => __("No meet runners found"),
		"not_found_in_trash" => __("No meet runners found in the trash"),
		"parent_item_colon" => "",
		"menu_name" => "Meet Runners"
	);
	$args = array(
		"labels" => $labels,
		"description" => "Contains information about meet runners.",
		"public" => false,
		"menu_position" => 7,
		"supports" => array("title", "editor", "custom-fields", "author"),
		"has_archive" => false,
		"show_ui" => true,
		"show_in_menu" => true
	);
	register_post_type("meet_runner", $args);
}
add_action("init", "sb_runner_post_type");

/**
 * Add SB staff identifier column header to list of meet runners. 
 * @param  array $defaults List of existing column headers.
 * @return array           List of modified column headers.
 */
function sb_runner_staff_column_title($defaults) {
	$new = array();
	foreach($defaults as $key => $title) {
		if ($key == "author") {
			$new['staff'] = 'Staff?';
		}
		$new[$key] = $title;
	}
	return $new;
}
add_filter("manage_meet_runner_posts_columns", "sb_runner_staff_column_title", 10);

/**
 * Add SB staff identifier column content to list of meet runners.
 * @param  string $column_name The current column name.
 * @param  int    $post_id     The current post ID.
 */
function sb_runner_staff_column_content($column_name, $post_id) {
	if($column_name == "staff") {
		$staff_status = get_field("runner_staff", $post_id);
		if(!empty($staff_status[0]) && $staff_status[0] == "true") {
			echo "&#x2714; Yes";
		}
	}
}
add_filter("manage_meet_runner_posts_custom_column", "sb_runner_staff_column_content", 10, 2);

/**
 * Add meet runner column header to WP admin.
 * @param  array $defaults The existing list of column headers.
 * @return array           The modified list of column headers.
 */
function sb_meet_runner_column_title($defaults) {
	$new = array();
	foreach($defaults as $key => $title) {
		if ($key == "author") {
			$new['meet_runner'] = 'Meet Runner';
		}
		$new[$key] = $title;
	}
	return $new;
}
add_filter("manage_meet_posts_columns", "sb_meet_runner_column_title", 10);

/**
 * Add meet runner column content to WP admin.
 * @param  string $column_name The current column header.
 * @param  int    $post_id     The current post ID.
 */
function sb_meet_runner_column_content($column_name, $post_id) {
	if($column_name == "meet_runner") {
		$meet_runner = get_field("meet_runner", $post_id);
		for($i = 0; $i < count($meet_runner); $i++) {
			echo get_the_title($meet_runner[$i]);
			if(!empty($meet_runner[$i+1])) { echo ", "; }
		}
	}
}
add_filter("manage_meet_posts_custom_column", "sb_meet_runner_column_content", 11, 2);

/**
 * ACF configuration
 */
if(function_exists("register_field_group")) {
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
				array (
					'param' => 'user_type',
					'operator' => '==',
					'value' => 'administrator',
					'order_no' => 1,
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