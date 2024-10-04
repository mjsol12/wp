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

 if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
 
//defining the url,path and slug for the plugin
define( 'MCP_URL', plugin_dir_url(__FILE__) );
define( 'MCP_PATH', plugin_dir_path(__FILE__) );

class McpddPlugin 
{
	function __construct () {
		$this->create_post_type();
	}

	function register() {
		add_action('admin_enqueue_scripts', array($this, 'enqueue'));

		add_action('admin_menu', array($this, 'add_admin_pages'));
	}

	function add_admin_pages(){
		add_menu_page( 'MCP Title', 'MCP', 'manage_options', 'mcp_plugin', array( $this, 'admin_index'), 'dashicons-sticky', 5 );
	}

	function admin_index(){
		//require template

	}

	protected function create_post_type() {
		add_action( 'init', array( $this, 'custom_post_type' ) );
	}

	// Function to register the custom post type
	function custom_post_type() {
		// create a custom post type 
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

		register_post_type('mcp', $args );
	}

	function enqueue () {
		// enqueue all our scripts
		wp_enqueue_style('mystyles', plugins_url('/assets/styles.css', __FILE__ ));
		wp_enqueue_script('myPluginScript', plugins_url('/assets/myscript.js', __FILE__ ));
	}
}

if(class_exists( 'McpddPlugin' )) {
	$mcpaddPlugin = new McpddPlugin();
	$mcpaddPlugin->register();
}

require_once plugin_dir_path( __FILE__ ) . 'inc/mcp-plugin-activate.php';
register_activation_hook( __FILE__, array('McpPluginActivate', 'activate'));

require_once plugin_dir_path( __FILE__ ) . 'inc/mcp-plugin-deactivate.php';
register_deactivation_hook( __FILE__, array('McpPluginDeactivate', 'deactivate'));

function add_featured_image_to_rest() {
    // Add featured image data to the REST API for posts
    register_rest_field(
        array('post', 'mcp'), // Post types
        'featured_media_url', // The key that will be added to the REST response
        array(
            'get_callback'    => function($post_arr) {
                $featured_img_url = get_the_post_thumbnail_url($post_arr['id'], 'full');
                return $featured_img_url ?: ''; // Return empty string if no featured image
            },
            'update_callback' => null,
            'schema'          => null,
        )
    );
}
add_action('rest_api_init', 'add_featured_image_to_rest');

// list of plugin build should be registered

function my_mcp_index_register_block() {
    $custom_blocks = array (
		'mcp-card',
		'mcp-list',
		'mcp-toggle',
	);

	foreach ( $custom_blocks as $block ) {
		register_block_type( __DIR__ . '/build/blocks/' . $block );
	}
}

add_action('init', 'my_mcp_index_register_block');

function multiblock_enqueue_block_assets() {
	wp_enqueue_script(
		'multi-block-editor-js',
		plugin_dir_url( __FILE__ ) . 'build/multi-block-editor.js',
		array('wp-blocks', 'wp-components', 'wp-data', 'wp-dom-ready', 'wp-edit-post', 'wp-element', 'wp-i18n', 'wp-plugins'),
		null,
		false
	);
	
	wp_enqueue_style(
		'multi-block-editor-css',
		plugin_dir_url( __FILE__ ) . 'build/multi-block-editor.css',
		array(),
		null
	);
}
add_action( 'enqueue_block_editor_assets', 'multiblock_enqueue_block_assets' );

function multiblock_enqueue_frontend_assets() {
	wp_enqueue_style(
		'multi-block-frontend-css',
		plugin_dir_url( __FILE__ ) . 'build/style-multi-block-editor.css',
	);

	wp_enqueue_script(
		'multi-block-frontend-js',
		plugin_dir_url( __FILE__ ) . 'build/multi-block-frontend.js',
		array(),
		null,
		true
	);
}
add_action( 'wp_enqueue_scripts', 'multiblock_enqueue_frontend_assets' );