<?php
/*
Plugin Name: Severn Bronies multimedia
Plugin URI: http://severnbronies.co.uk/
Description: Adds functionality for multimedia functions. 
Version: 1.0
Author: Kimberly Grey
Author URI: http://greysadventures.com/
*/

function sb_custom_file_types($mimes) {
	$mimes["svg"] = "image/svg+xml";
	return $mimes;
}
add_filter("upload_mimes", "sb_custom_file_types");

/**
 * Add copyright field to the media uploader.
 * @param  array  $form_fields The list of existing form fields.
 * @param  object $post        The post information for this media item.
 * @return array               The amended list of form fields.
 */
function sb_copyright_field($form_fields, $post) {
	$form_fields["copyright_field"] = array(
		"label" => "Copyright",
		"value" => get_post_meta($post->ID, "_custom_copyright", true)
	);
	return $form_fields;
}
add_filter("attachment_fields_to_edit", "sb_copyright_field", null, 2);

/**
 * Tell it to save the information that's in the copyright field, of course.
 * @param  object $post       The data being saved.
 * @param  object $attachment The data on the media item.
 * @return object             The data being saved (now with media item).
 */
function sb_copyright_field_save($post, $attachment) {
	if(!empty($attachment["copyright_field"])) {
		update_post_meta($post["ID"], "_custom_copyright", $attachment["copyright_field"]);
	}
	else {
		delete_post_meta($post["ID"], "_custom_copyright");
	}
	return $post;
}
add_filter("attachment_fields_to_save", "sb_copyright_field_save", null, 2);

/**
 * Get copyright information.
 * @param  mixed  $attachment_id The ID of the attachment.
 * @return string                The copyright information for this attachment.
 */
function get_featured_image_copyright($attachment_id = null) {
	$attachment_id = (empty($attachment_id)) ? get_post_thumbnail_id() : (int)$attachment_id;
	if($attachment_id) {
		return get_post_meta($attachment_id, "_custom_copyright", true);
	}
}