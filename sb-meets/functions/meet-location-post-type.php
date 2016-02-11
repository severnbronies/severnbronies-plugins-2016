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
	$defaults["location"] = "Location";
	return $defaults;
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