<?php

/**
 * Create the 'meet' post type.
 */
function sb_meet_post_type() {
	$labels = array(
		"name" => _x("Meets", "post type general name"),
		"singular_name" => _x("Meet", "post type singular name"),
		"add_new" => _x("Add New", "book"),
		"add_new_item" => __("Add New Meet"),
		"edit_item" => __("Edit Meet"),
		"new_item" => __("New Meet"),
		"all_items" => __("All Meets"),
		"view_item" => __("View Meet"),
		"search_items" => __("Search Meets"),
		"not_found" => __("No meets found"),
		"not_found_in_trash" => __("No meets found in the trash"),
		"parent_item_colon" => "",
		"menu_name" => "Meets"
	);
	$args = array(
		"labels" => $labels,
		"description" => "Contains the meets that we hold.",
		"public" => true,
		"menu_position" => 7,
		"supports" => array("title", "editor", "thumbnail", "custom-fields", "author"),
		"taxonomies" => array("category"),
		"has_archive" => true
	);
	register_post_type("meet", $args);
}
add_action("init", "sb_meet_post_type");

/**
 * Add running time column header to WP admin.
 * @param  array $defaults The existing list of column headers.
 * @return array           The modified list of column headers.
 */
function sb_meet_time_column_title($defaults) {
	$defaults["meet_time"] = "Running Time";
	return $defaults;
}
add_filter("manage_meet_posts_columns", "sb_meet_time_column_title", 10);

/**
 * Add running time column content to WP admin.
 * @param  string $column_name The current column header.
 * @param  int    $post_id     The current post ID.
 */
function sb_meet_time_column_content($column_name, $post_id) {
	if($column_name == "meet_time") {
		echo sb_meet_dates(get_field("meet_start_time", $post_id), get_field("meet_end_time", $post_id));
	}
}
add_filter("manage_meet_posts_custom_column", "sb_meet_time_column_content", 10, 2);

/**
 * Make running time sortable in WP admin.
 * @param  array $columns The existing list of sortable columns.
 * @return array          The modified list of sortable columns.
 */
function sb_meet_time_column_sortable($columns) {
	$columns["meet_time"] = "meet_time";
	return $columns;
}
add_filter("manage_edit-meet_sortable_columns", "sb_meet_time_column_sortable");

/**
 * Define how to sort running time column in WP admin.
 * @param  ??? $query I dunno, probably an object or something?
 */
function sb_meet_time_column_orderby($query) {
	$orderby = $query->get("orderby");
	if($orderby == "meet_time") {
		$query->set("meta_key", "meet_start_time");
		$query->set("orderby", "meta_value_num");
	}
}
add_action("pre_get_posts", "sb_meet_time_column_orderby");