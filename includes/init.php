<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// Function to register the custom post type
function pluginprefix_setup_post_type() {
    $labels = array(
        'name'               => _x('My Custom Plugin', 'post type general name'),
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
        'show_ui'            => true, // ensures that the post type is displayed in the WordPress admin dashboard.
        'show_in_menu'       => true, // ensures that the custom post type will appear in the admin menu.
        'show_in_admin_bar'  => true, // Makes the custom post type available in the top admin bar when adding new items.
        'menu_position'      => 5, // Position of the MCP tab in the menu
		'menu_icon'          => 'dashicons-sticky', 
        'supports'           => array('title', 'editor', 'thumbnail', 'excerpt', 'comments', 'revisions'), // Enable the default WordPress editor features
		'show_in_rest'       => true,  // Enable REST API for Gutenberg editor
        'hierarchical'       => false, // Set to false if it's like "posts", true if it's like "pages"
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
	unregister_post_type( 'mcp' );
	// Clear the permalinks to remove our post type's rules from the database.
	flush_rewrite_rules();
}
register_deactivation_hook( __FILE__, 'pluginprefix_deactivate' );


// Enqueue the block editor script for Gutenberg
function my_custom_block_assets() {
    wp_enqueue_script(
        'my-custom-block',
        MCP_URL.'block/build/index.js', // Path to the block build file
        array('wp-blocks', 'wp-element', 'wp-editor', 'wp-components', 'wp-data'),
        filemtime(MCP_PATH.'block/build/index.js')
    );
    wp_enqueue_style(
        'my-custom-block-styles',
        MCP_URL.'block/styles.css',
        array(),
        filemtime(MCP_PATH.'block/styles.css')
    );
}
add_action('enqueue_block_editor_assets', 'my_custom_block_assets');