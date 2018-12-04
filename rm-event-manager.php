<?php
/**
	* Plugin Name: Events Manager by Roar Media
	* Plugin URI: https://github.com/itskriz/rm-events-manager
	* Description: A lightweight plugin that adds an events calendar to WordPress.
	* Version: 0.2
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