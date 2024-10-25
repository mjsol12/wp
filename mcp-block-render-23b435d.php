<?php
/*
 * Plugin Name: My Custom Plugin
 * Plugin URI:  https://github.com/mjsol12/wp
 * Description: A custom WordPress plugin for demonstration.
 * Version:     1.0
 * Author:      Mark Jones Solano
 * Author URI:  https://mjsolano.com
 * License:     GPL2
 * Text Domain: mcp
 * Domain Path: /languages 
 * */

 if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
 
//defining the url,path and slug for the plugin
define( 'MCP_URL', plugin_dir_url(__FILE__) );
define( 'MCP_PATH', plugin_dir_path(__FILE__) );

require_once MCP_PATH . 'inc/mcp.php';
require_once MCP_PATH . 'inc/imcp-plugin.php';
require_once MCP_PATH . 'inc/mcp-plugin-activate.php';
require_once MCP_PATH . 'inc/mcp-plugin-deactivate.php';


if(class_exists( 'McpddPlugin' )) {
	$mcpaddPlugin = new McpddPlugin();
	$mcpaddPlugin->register();
}

if(class_exists( 'ImcpPluginRegister' )) {
	$mcpaddPlugin = new ImcpPluginRegister();
	$mcpaddPlugin->register();
}

if(class_exists( 'McpPluginActivate' )) {
	register_activation_hook( __FILE__, array('McpPluginActivate', 'activate'));
}

if(class_exists( 'McpPluginDeactivate' )) {
	register_deactivation_hook( __FILE__, array('McpPluginDeactivate', 'deactivate'));
}
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