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

	function msu_add_to_all_sites( $user_id, $password='', $meta='' )  {

		if(empty($meta)) {
			$added_blog_id =get_active_blog_for_user( $user_id );
			switch_to_blog($added_blog_id);
			$role = get_userdata($user_id)->roles[0];
			restore_current_blog();
		}
		else {
			$added_blog_id = $meta['add_to_blog'];
			$role = $meta['new_role'];
		}

		$sites = wp_get_sites(array('limit' => MSU_MAX_SITES));

		foreach ( $sites AS $site ) {

			if($site['blog_id'] != $added_blog_id) {

				add_user_to_blog($site['blog_id'], $user_id, $role);

			}

		}

	}

	// When self-registred or when a super admin creates it
	add_action( 'wpmu_new_user', 'msu_add_to_all_sites', 10, 1 );

	// When admin of a subsite creates it
	add_action( 'wpmu_activate_user', 'msu_add_to_all_sites', 10, 3 );

}
