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
	$new = array();
	foreach($defaults as $key => $title) {
		if ($key == "author") {
			$new['meet_time'] = 'Running Time';
		}
		$new[$key] = $title;
	}
	return $new;
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

/**
 * ACF configuration
 */
if(function_exists("register_field_group")) {
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
}