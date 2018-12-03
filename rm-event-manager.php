<?php
/**
	* Plugin Name: Events Manager by Roar Media
	* Plugin URI: https://github.com/itskriz/rm-events-manager
	* Description: A lightweight plugin that adds an events calendar to WordPress.
	* Version: 0.1
	* Author: Kris Williams/Roar Media
	* Author URI: mailto:webmaster@roarmedia.com
**/
	

	// Get ACF Fields

	// Get Includes
	$includes = glob( plugin_dir_path(__FILE__) . 'includes/*.php' );
	foreach ($includes as $file) {
		include_once ($file);
	}

	// Register Events post type
	add_action( 'init', 'register_rm_event_post_type', 0 );

	// Register Venues post type if enabled
	$enable_venues = get_field('rm_event_enable_venues', 'option');
	if ($enable_venues) {
		add_action( 'init', 'register_rm_event_venue_post_type', 0 );
	}
	
?>