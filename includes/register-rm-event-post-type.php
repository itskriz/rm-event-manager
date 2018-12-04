<?php
if ( ! function_exists('register_rm_event_post_type') ) {

	// Register Custom Post Type
	function register_rm_event_post_type() {

		$labels = array(
			'name'                  => _x( 'Events', 'Post Type General Name', 'roarmedia' ),
			'singular_name'         => _x( 'Event', 'Post Type Singular Name', 'roarmedia' ),
			'menu_name'             => __( 'Event Manager', 'roarmedia' ),
			'name_admin_bar'        => __( 'Event Manager', 'roarmedia' ),
			'archives'              => __( 'Event Calendar', 'roarmedia' ),
			'attributes'            => __( 'Event Attributes', 'roarmedia' ),
			'parent_item_colon'     => __( 'Parent Event:', 'roarmedia' ),
			'all_items'             => __( 'All Events', 'roarmedia' ),
			'add_new_item'          => __( 'Add New Event', 'roarmedia' ),
			'add_new'               => __( 'Add New', 'roarmedia' ),
			'new_item'              => __( 'New Event', 'roarmedia' ),
			'edit_item'             => __( 'Edit Event', 'roarmedia' ),
			'update_item'           => __( 'Update Event', 'roarmedia' ),
			'view_item'             => __( 'View Event', 'roarmedia' ),
			'view_items'            => __( 'View Events', 'roarmedia' ),
			'search_items'          => __( 'Search Event', 'roarmedia' ),
			'not_found'             => __( 'Not found', 'roarmedia' ),
			'not_found_in_trash'    => __( 'Not found in Trash', 'roarmedia' ),
			'featured_image'        => __( 'Featured Image', 'roarmedia' ),
			'set_featured_image'    => __( 'Set featured image', 'roarmedia' ),
			'remove_featured_image' => __( 'Remove featured image', 'roarmedia' ),
			'use_featured_image'    => __( 'Use as featured image', 'roarmedia' ),
			'insert_into_item'      => __( 'Insert into event', 'roarmedia' ),
			'uploaded_to_this_item' => __( 'Uploaded to this event', 'roarmedia' ),
			'items_list'            => __( 'Events list', 'roarmedia' ),
			'items_list_navigation' => __( 'Events list navigation', 'roarmedia' ),
			'filter_items_list'     => __( 'Filter events list', 'roarmedia' ),
		);
		$rewrite = array(
			'slug'                  => 'event',
			'with_front'            => true,
			'pages'                 => true,
			'feeds'                 => true,
		);
		$args = array(
			'label'                 => __( 'Event', 'roarmedia' ),
			'description'           => __( 'A post type to contain event data', 'roarmedia' ),
			'labels'                => $labels,
			'supports'              => array( 'title', 'editor', 'thumbnail', 'revisions' ),
			'taxonomies'            => array( 'rm-event-category', ' rm-event-group', ' rm-event-tag' ),
			'hierarchical'          => false,
			'public'                => true,
			'show_ui'               => true,
			'show_in_menu'          => true,
			'menu_position'         => 5,
			'menu_icon'             => 'dashicons-calendar',
			'show_in_admin_bar'     => true,
			'show_in_nav_menus'     => true,
			'can_export'            => true,
			'has_archive'           => 'events',
			'exclude_from_search'   => false,
			'publicly_queryable'    => true,
			'rewrite'               => $rewrite,
			'capability_type'       => 'post',
		);
		register_post_type( 'rm-event', $args );

	}
	add_action( 'init', 'register_rm_event_post_type', 0 );

}
?>