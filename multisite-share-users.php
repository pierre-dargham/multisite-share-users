<?php

/**
 * Plugin Name:         Mulsisite Share Users
 * Plugin URI:          https://github.com/pierre-dargham/multisite-share-users
 * Description:         When a new user is added in one site, add it to all the network sites
 * Author:              Pierre DARGHAM, GLOBALIS media systems
 * Author URI:          https://github.com/pierre-dargham/
 *
 * Version:             1.0
 * Requires at least:   3.5.0
 * Tested up to:        4.2
 */

if(!defined('MSU_MAX_SITES') && !function_exists('msu_add_to_all_sites') ) {

	define('MSU_MAX_SITES', 10000);

	function msu_add_to_all_sites( $blog_id, $user_id, $role )  {

		// Avoiding infinite loops
		remove_action( 'add_user_to_blog', 'msu_add_to_all_sites', 10, 3 );

		$sites = wp_get_sites(array('limit' => MSU_MAX_SITES));

		foreach ( $sites AS $site ) {

			if($site['blog_id'] != $blog_id) {

				add_user_to_blog($site['blog_id'], $user_id, $role);

			}

		}

		add_action( 'add_user_to_blog', 'msu_add_to_all_sites', 10, 3 );
	}

	add_action( 'add_user_to_blog', 'msu_add_to_all_sites', 10, 3 );

}
