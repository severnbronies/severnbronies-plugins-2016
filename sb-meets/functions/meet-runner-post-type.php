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
 * Add BB staff identifier column header to list of meet runners. 
 * @param  array $defaults List of existing column headers.
 * @return array           List of modified column headers.
 */
function sb_runner_staff_column_title($defaults) {
	$defaults["staff"] = "Staff?";
	return $defaults;
}
add_filter("manage_meet_runner_posts_columns", "sb_runner_staff_column_title", 10);

/**
 * Add BB staff identifier column content to list of meet runners.
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
	$defaults["meet_runner"] = "Meet Runner";
	return $defaults;
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
