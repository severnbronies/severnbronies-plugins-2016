<?php

/**
 * Create 'location' post type.
 */
function sb_location_post_type() {
	$labels = array(
		'name' => _x('Locations', 'post type general name'),
		'singular_name' => _x('Location', 'post type singular name'),
		'add_new' => _x('Add New', 'book'),
		'add_new_item' => __('Add New Location'),
		'edit_item' => __('Edit Location'),
		'new_item' => __('New Location'),
		'all_items' => __('All Locations'),
		'view_item' => __('View Location'),
		'search_items' => __('Search Locations'),
		'not_found' => __('No locations found'),
		'not_found_in_trash' => __('No locations found in the trash'),
		'parent_item_colon' => '',
		'menu_name' => 'Locations'
	);
	$args = array(
		'labels' => $labels,
		'description' => 'Contains meet locations and venues.',
		'public' => false,
		'menu_position' => 7,
		'supports' => array('title', 'custom-fields', 'author'),
		'has_archive' => false,
		'show_ui' => true,
		'show_in_menu' => true
	);
	register_post_type('location', $args);
}
add_action('init', 'sb_location_post_type');

/**
 * Add location column header to WP admin.
 * @param  array $defaults The existing list of column headers.
 * @return array           The modified list of column headers.
 */
function sb_meet_location_column_title($defaults) {
	$new = array();
	foreach($defaults as $key => $title) {
		if ($key == "author") {
			$new['location'] = 'Location';
		}
		$new[$key] = $title;
	}
	return $new;
}
add_filter("manage_meet_posts_columns", "sb_meet_location_column_title", 10);

/**
 * Add location column content to WP admin.
 * @param  string $column_name The current column header.
 * @param  int    $post_id     The current post ID.
 */
function sb_meet_location_column_content($column_name, $post_id) {
	if($column_name == "location") {
		$meet_location = get_field("meet_location", $post_id);
		for($i = 0; $i < count($meet_location); $i++) {
			$address = get_field("location_address", $meet_location[$i]);
			echo get_the_title($meet_location[$i]) . "<br>" . $address["address"];
			if(!empty($meet_location[$i+1])) { echo "<br>"; }
		}
	}
}
add_filter("manage_meet_posts_custom_column", "sb_meet_location_column_content", 10, 2);

/**
 * Add locality column header to list of meet locations. 
 * @param  array $defaults List of existing column headers.
 * @return array           List of modified column headers.
 */
function sb_meet_locality_column_title($defaults) {
	$new = array();
	foreach($defaults as $key => $title) {
		if ($key == "author") {
			$new['locality'] = 'Locality';
		}
		$new[$key] = $title;
	}
	return $new;
}
add_filter("manage_location_posts_columns", "sb_meet_locality_column_title", 10);

/**
 * Add locality column content to list of meet locations.
 * @param  string $column_name The current column name.
 * @param  int    $post_id     The current post ID.
 */
function sb_meet_locality_column_content($column_name, $post_id) {
	if($column_name == "locality") {
		echo get_field("location_locality", $post_id);
	}
}
add_filter("manage_location_posts_custom_column", "sb_meet_locality_column_content", 10, 2);

/**
 * ACF configuration
 */
if(function_exists("register_field_group")) {
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
			array (
				'key' => 'field_56c487d97e01f',
				'label' => 'Locality',
				'name' => 'location_locality',
				'type' => 'select',
				'required' => 1,
				'choices' => array (
					'Bristol' => 'Bristol',
					'Cardiff' => 'Cardiff',
					'Newport' => 'Newport',
					'Weston-super-Mare' => 'Weston-super-Mare',
					'' => 'Other',
				),
				'default_value' => '',
				'allow_null' => 1,
				'multiple' => 0,
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
}