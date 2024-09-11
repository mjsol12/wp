<?php
/*
 * Plugin Name: My Custom Plugin
 * Plugin URI:  https://github.com/mjsol12/wp
 * Description: A custom WordPress plugin for demonstration.
 * Version:     1.0
 * Author:      Mark Jones Solano
 * Author URI:  https://mjsolano.com
 *  License:     GPL2
 */

 /**
 * Register the "book" custom post type
 */

 
// Function to register the custom post type
function pluginprefix_setup_post_type() {
    $labels = array(
        'name'               => _x('MCP', 'post type general name'),
        'singular_name'      => _x('MCP', 'post type singular name'),
        'menu_name'          => __('MCP'),
        'name_admin_bar'     => __('MCP'),
        'add_new'            => __('Add New'),
        'add_new_item'       => __('Add New MCP'),
        'edit_item'          => __('Edit MCP'),
        'new_item'           => __('New MCP'),
        'view_item'          => __('View MCP'),
        'all_items'          => __('All MCP'),
        'search_items'       => __('Search MCP'),
        'not_found'          => __('No MCPs found'),
        'not_found_in_trash' => __('No MCPs found in Trash')
    );
    
    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'has_archive'        => true,
        'supports'           => array('title', 'editor', 'thumbnail'),
        'show_in_menu'       => true,
        'menu_position'      => 5, // Position of the MCP tab in the menu
        'menu_icon'          => 'dashicons-admin-post', // You can change the icon if needed
    );

    register_post_type('mcp', $args);
}
add_action( 'init', 'pluginprefix_setup_post_type' );

/**
 * Activate the plugin.
 */
function pluginprefix_activate() { 
	// Trigger our function that registers the custom post type plugin.
	pluginprefix_setup_post_type(); 
	// Clear the permalinks after the post type has been registered.
	flush_rewrite_rules(); 
}
register_activation_hook( __FILE__, 'pluginprefix_activate' );

/**
 * Deactivation hook.
 */
function pluginprefix_deactivate() {
	// Unregister the post type, so the rules are no longer in memory.
	unregister_post_type( 'book' );
	// Clear the permalinks to remove our post type's rules from the database.
	flush_rewrite_rules();
}
register_deactivation_hook( __FILE__, 'pluginprefix_deactivate' );