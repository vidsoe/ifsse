<?php namespace IFSSE;

final class Utilities {

    // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
	//
	// public
	//
	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

	public static function post_type_labels($singular = '', $plural = '', $all = true){
		if(!$singular or !$plural){
			return [];
		}
		return [
			'name' => $plural,
			'singular_name' => $singular,
			'add_new' => 'Add New',
			'add_new_item' => 'Add New ' . $singular,
			'edit_item' => 'Edit ' . $singular,
			'new_item' => 'New ' . $singular,
			'view_item' => 'View ' . $singular,
			'view_items' => 'View ' . $plural,
			'search_items' => 'Search ' . $plural,
			'not_found' => 'No ' . strtolower($plural) . ' found.',
			'not_found_in_trash' => 'No ' . strtolower($plural) . ' found in Trash.',
			'parent_item_colon' => 'Parent ' . $singular . ':',
			'all_items' => ($all ? 'All ' : '') . $plural,
			'archives' => $singular . ' Archives',
			'attributes' => $singular . ' Attributes',
			'insert_into_item' => 'Insert into ' . strtolower($singular),
			'uploaded_to_this_item' => 'Uploaded to this ' . strtolower($singular),
			'featured_image' => 'Featured image',
			'set_featured_image' => 'Set featured image',
			'remove_featured_image' => 'Remove featured image',
			'use_featured_image' => 'Use as featured image',
			'filter_items_list' => 'Filter ' . strtolower($plural) . ' list',
			'items_list_navigation' => $plural . ' list navigation',
			'items_list' => $plural . ' list',
			'item_published' => $singular . ' published.',
			'item_published_privately' => $singular . ' published privately.',
			'item_reverted_to_draft' => $singular . ' reverted to draft.',
			'item_scheduled' => $singular . ' scheduled.',
			'item_updated' => $singular . ' updated.',
		];
	}

    // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

}
