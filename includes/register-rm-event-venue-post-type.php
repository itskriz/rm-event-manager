<?php
if ( ! function_exists('register_rm_event_venue_post_type') ) {

	// Register Custom Post Type
	function register_rm_event_venue_post_type() {

		$labels = array(
			'name'                  => _x( 'Venues', 'Post Type General Name', 'roarmedia' ),
			'singular_name'         => _x( 'Venue', 'Post Type Singular Name', 'roarmedia' ),
			'menu_name'             => __( 'Venues', 'roarmedia' ),
			'name_admin_bar'        => __( 'Venue', 'roarmedia' ),
			'archives'              => __( 'Venue Archives', 'roarmedia' ),
			'attributes'            => __( 'Venue Attributes', 'roarmedia' ),
			'parent_item_colon'     => __( 'Parent Venue:', 'roarmedia' ),
			'all_items'             => __( 'All Venus', 'roarmedia' ),
			'add_new_item'          => __( 'Add New Venue', 'roarmedia' ),
			'add_new'               => __( 'Add New', 'roarmedia' ),
			'new_item'              => __( 'New Venue', 'roarmedia' ),
			'edit_item'             => __( 'Edit Venue', 'roarmedia' ),
			'update_item'           => __( 'Update Venue', 'roarmedia' ),
			'view_item'             => __( 'View Venue', 'roarmedia' ),
			'view_items'            => __( 'View Venues', 'roarmedia' ),
			'search_items'          => __( 'Search Venue', 'roarmedia' ),
			'not_found'             => __( 'Not found', 'roarmedia' ),
			'not_found_in_trash'    => __( 'Not found in Trash', 'roarmedia' ),
			'featured_image'        => __( 'Featured Image', 'roarmedia' ),
			'set_featured_image'    => __( 'Set featured image', 'roarmedia' ),
			'remove_featured_image' => __( 'Remove featured image', 'roarmedia' ),
			'use_featured_image'    => __( 'Use as featured image', 'roarmedia' ),
			'insert_into_item'      => __( 'Insert into venue', 'roarmedia' ),
			'uploaded_to_this_item' => __( 'Uploaded to this venue', 'roarmedia' ),
			'items_list'            => __( 'Venues list', 'roarmedia' ),
			'items_list_navigation' => __( 'Venues list navigation', 'roarmedia' ),
			'filter_items_list'     => __( 'Filter venues list', 'roarmedia' ),
		);
		$args = array(
			'label'                 => __( 'Venue', 'roarmedia' ),
			'description'           => __( 'A post type to contain venue data', 'roarmedia' ),
			'labels'                => $labels,
			'supports'              => array( 'title', 'editor', 'thumbnail', 'revisions' ),
			'hierarchical'          => false,
			'public'                => false,
			'show_ui'               => true,
			'show_in_menu'          => false,
			'menu_position'         => 5,
			'menu_icon'             => 'dashicons-location',
			'show_in_admin_bar'     => true,
			'show_in_nav_menus'     => true,
			'can_export'            => true,
			'has_archive'           => false,
			'exclude_from_search'   => true,
			'publicly_queryable'    => true,
			'rewrite'               => false,
			'capability_type'       => 'post',
		);
		register_post_type( 'rm-event-venue', $args );

	}
	//add_action( 'init', 'register_rm_event_venue_post_type', 0 );

}
?>