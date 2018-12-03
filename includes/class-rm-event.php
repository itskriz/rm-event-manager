<?php
/*
	Class: RM_Event
*/


class RM_Event {
	// Default Vars from WP_Post object
	/*
	$ID = 0; // int
	$event_author = ''; //	string	The post author's user ID (numeric string)
	$event_name = ''; //	string	The post's slug
	$event_type = 'rm_event'; //	string	See Post Types
	$event_title = '' //	string	The title of the post
	$event_date = ''; //	string	Format: 0000-00-00 00:00:00
	$event_date_gmt = ''; //	string	Format: 0000-00-00 00:00:00
	$event_content = ''; //	string	The full content of the post
	$event_excerpt = ''; //	string	User-defined post excerpt
	$event_status = ''; //	string	See get_event_status for values
	$comment_status = ''; //	string	Returns: { open, closed }
	$ping_status = ''; //	string	Returns: { open, closed }
	$event_password = ''; //	string	Returns empty string if no password
	$event_parent = ''; //	int	Parent Post ID (default 0)
	$event_modified = ''; //	string	Format: 0000-00-00 00:00:00
	$event_modified_gmt = ''; //	string	Format: 0000-00-00 00:00:00
	$comment_count = ''; //	string	Number of comments on post (numeric string)
	$menu_order = ''; //	string	Order value as set through page-attribute when enabled (numeric string. Defaults to 0)
	*/

//// Constructor
	/*
		Args: $post_id, 
	*/
	public function __construct($post_id = null) {
		// Check if $post_id provided
		if ($post_id === null || !is_int($post_id)) {
			// Post is null or post ID isn't an integer
			// Get most recent published event post
			$get_posts = get_posts(
				array(
					'post_type'		=> 'rm-event',
					'numberposts'	=> 1,
					'order'				=> 'DESC',
					'post_status'	=> 'publish',
				)
			);
			$post_id = $get_posts[0]->ID;
		}
		$event = get_post($post_id);
	}

}

?>