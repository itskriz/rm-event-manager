<?php
/**
	* Plugin Name: Event Manager by Roar Media
	* Plugin URI: https://github.com/itskriz/rm-event-manager
	* Description: A lightweight plugin that adds an events calendar to WordPress.
	* Version: a0.4
	* Author: Kris Williams/Roar Media
	* Author URI: mailto:webmaster@roarmedia.com
**/
	
/*
	ACF PRO IS REQUIRED FOR THIS PLUGIN TO WORK
	ACF MUST BE INSTALLED AND ACTIVATED
*/

//// BEGIN ACF CHECK ////
if (class_exists('acf')) {

	// Get ACF Fields

	// Add options page
	if (function_exists('acf_add_options_page')) {
		acf_add_options_page(
			array(
				'page_title'	=> 'Events Manager Settings',
				'menu_title'	=> 'Settings',
				'parent_slug'	=> 'edit.php?post_type=rm-event'
			)
		);
	}

	// Function to move Venues to submenus
	function rm_event_venue_submenu() {
	 	add_submenu_page( 'edit.php?post_type=rm-event', 'Venues', 'Venues', 'manage_options', 'edit.php?post_type=rm-event-venue');
	}

	// Get Includes
	$includes = glob( plugin_dir_path(__FILE__) . 'includes/*.php' );
	foreach ($includes as $file) {
		require_once ($file);
	}

	// Register Events post type
	add_action( 'init', 'register_rm_event_post_type', 0 );

	// Register Venues post type if enabled
	$enable_venues = get_field('rm_event_enable_venues', 'option');
	if ($enable_venues) {
		add_action( 'init', 'register_rm_event_venue_post_type', 0 );
		add_action('admin_menu','rm_event_venue_submenu');
	}

	// Load Template Files
	function rm_event_single_template($single) {
		global $post;
		if ($post->post_type == 'rm-event') {
			if (file_exists(plugin_dir_path(__FILE__) . 'templates/single-rm-event.php')) {
				return plugin_dir_path(__FILE__) . 'templates/single-rm-event.php';
			}
		}
		return $single;
	}
	add_filter ('single_template', 'rm_event_single_template');

}
//// END ACF CHECK ////

?>