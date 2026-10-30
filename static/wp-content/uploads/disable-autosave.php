<?php

/*
Plugin Name: Disable autosave
Description: Disables autosaving on the write/edit page/post panel
Plugin URI: http://samm.dreamhosters.com/wordpress/plugins/
Author: Sam
Version: 0.1

=== Disable autosave ===
Tags: autosave
Requires at least: 2.5
Tested up to: 2.5.1

Might stop runaway autosaving

*/

remove_action('pre_post_update', 'wp_save_post_revision' );
add_action('admin_print_scripts', 'plugin_deregister_autosave');
add_action( 'wp_print_scripts', 'disable_autosave' );

function disable_autosave() {
    wp_deregister_script('autosave');
}

function plugin_deregister_autosave() {
	define('WP_POST_REVISIONS', false);
	define('AUTOSAVE_INTERVAL', false);
	wp_deregister_script('autosave');
	echo '<!-- '.basename(__FILE__).' '.__FUNCTION__.'() -->';
}

?>