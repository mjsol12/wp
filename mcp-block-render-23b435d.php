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
require_once MCP_PATH . 'inc/imcp.php';
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

// Hook into the 'admin_menu' action
add_action('admin_menu', 'my_custom_menu');

function my_custom_menu() {
    add_menu_page(
        'Plugin Uploaded',           // Page title
        'Plugin Uploaded',           // Menu title
        'manage_options',           // Capability
        'my-custom-menu-slug',      // Menu slug
        'my_custom_menu_page',      // Function to display the page
        'dashicons-admin-generic',  // Icon
    );
}

function my_custom_menu_page() {
    $plugin_slug = 'example-plugin-slug'; // Replace with your plugin slug
    $plugin_info = get_plugin_info($plugin_slug);

    if ($plugin_info) {
        echo '<h1>' . esc_html($plugin_info->name) . '</h1>';
        echo '<p><strong>Version:</strong> ' . esc_html($plugin_info->version) . '</p>';
        echo '<p>' . esc_html($plugin_info->description) . '</p>';
        echo '<p><strong>Author:</strong> <a href="' . esc_url($plugin_info->homepage) . '">' . esc_html($plugin_info->author) . '</a></p>';
    } else {
        echo '<p>Unable to retrieve plugin information.</p>';
    }
}

// Function to get plugin information
function get_plugin_info($plugin_slug) {
	// Ensure that plugins_api() is called within the WordPress environment
    if ( ! function_exists( 'get_plugins' ) ) {
		require_once ABSPATH . 'wp-admin/includes/plugin.php';
	  }
	  $all_plugins = get_plugins();
	
    // Start output
    echo '<div class="wrap">';
    echo '<h1>All Installed Plugins</h1>';
    echo '<ul>';

    // Loop through each plugin and display its name and description
    foreach ($all_plugins as $plugin_file => $plugin_data) {
        echo '<li>';
		if(is_plugin_active($plugin_slug)) {
			echo '<strong>' . esc_html($plugin_data['Name']) . ' Active</strong>'; // Plugin Name
		} else {
			echo '<strong>' . esc_html($plugin_data['Name']) . ' Not Active</strong>'; // Plugin Name
		}
        echo '<br>';
        echo esc_html($plugin_data['Description']); // Plugin Description
        echo '<br>';
        echo '<em>Version: ' . esc_html($plugin_data['Version']) . '</em>'; // Plugin Version
        echo '</li><hr>'; // Add a horizontal line for separation
    }

    echo '</ul>';
    echo '</div>';
}

// add_action('admin_init', 'install_required_plugin');

// function install_required_plugin() {
//     $plugin_slug = 'block-builder'; // Replace with the slug of the desired plugin
//     $plugin_name = 'Elementor Blocks for Gutenberg'; // Replace with the name of the desired plugin
	
// 	if ( ! function_exists( 'get_plugins' ) ) {
// 		require_once ABSPATH . 'wp-admin/includes/plugin.php';
// 	}
	
//     // Check if the plugin is already activated
//     if (!is_plugin_active($plugin_slug . '/' . $plugin_slug . '.php')) {
//         // Attempt to install the plugin
//         $installed_plugins = get_plugins();

//         if (!array_key_exists($plugin_slug . '/' . $plugin_slug . '.php', $installed_plugins)) {
//             // Fetch the plugin from the WordPress Plugin Repository
//             $plugin_info = plugins_api('plugin_information', array('slug' => $plugin_slug));

//             if (!is_wp_error($plugin_info)) {
//                 $zip_url = $plugin_info->download_link;

//                 // Use the WordPress filesystem functions
//                 require_once( ABSPATH . 'wp-admin/includes/file.php' );
//                 require_once( ABSPATH . 'wp-admin/includes/misc.php' );
//                 require_once( ABSPATH . 'wp-admin/includes/plugin.php' );

//                 // Set up the WordPress filesystem
//                 $creds = request_filesystem_credentials( site_url() . '/wp-admin/', '', false, false, null );
//                 if ( ! WP_Filesystem( $creds ) ) {
//                     return; // Exit if unable to get filesystem credentials
//                 }

//                 global $wp_filesystem;

//                 // Download the plugin ZIP file
//                 $zip_file = download_url( $zip_url );

//                 if ( ! is_wp_error( $zip_file ) ) {
//                     // Unzip the file to the plugins directory
//                     $result = unzip_file( $zip_file, WP_PLUGIN_DIR );

//                     if ( is_wp_error( $result ) ) {
//                         // Handle error in unzipping
//                         @unlink( $zip_file ); // Clean up the downloaded file
//                     } else {
//                         // Activate the plugin after successful installation
//                         activate_plugin( $plugin_slug . '/' . $plugin_slug . '.php' );
//                     }
//                 }
//             }
//         }
//     }
// }

add_action('admin_menu', 'my_plugin_menu');

function my_plugin_menu() {
    add_menu_page('Plugin Info', 'Plugin Info', 'manage_options', 'my-plugin-info', 'my_plugin_info_page');
}

function my_plugin_info_page() {
    // Define the plugin slug for the plugin you want to retrieve
	// should require this firs 
	require_once( ABSPATH . 'wp-admin/includes/plugin-install.php' );

    $plugin_slug = 'wp-expand-tabs-free'; // Change this to the desired plugin slug

    // Fetch plugin information from the WordPress.org Plugins API
    $plugin_info = plugins_api('plugin_information', array('slug' => $plugin_slug));

    // Check for errors
    if (is_wp_error($plugin_info)) {
        echo '<div class="notice notice-error"><p>Error fetching plugin information.</p></div>';
        return;
    }

    // Display plugin information
    echo '<div class="wrap">';
    echo '<h1>' . esc_html($plugin_info->name) . '</h1>';
    echo '<p><strong>Description:</strong> ' . esc_html($plugin_info->description) . '</p>';
    echo '<p><strong>Version:</strong> ' . esc_html($plugin_info->version) . '</p>';
    echo '<p><strong>Author:</strong> ' . esc_html($plugin_info->author) . '</p>';
    echo '<p><strong>Download Link:</strong> <a href="' . esc_url($plugin_info->download_link) . '">Download</a></p>';
    echo '</div>';
}

add_action('admin_menu', 'my_plugins_menu');

function my_plugins_menu() {
    add_menu_page('Plugin Activate Process', 'Plugin Activate Process', 'manage_options', 'my-plugins-info', 'adp_download_and_install_plugin');
}

// Function to download and install the plugin
function adp_download_and_install_plugin() {
	// should require this first
	require_once( ABSPATH . 'wp-admin/includes/plugin-install.php' );

	$slug = 'performant-translations';

    // Get plugin information
    $plugin_info = plugins_api('plugin_information', array('slug' => $slug));


    // Check for errors
    if (is_wp_error($plugin_info)) {
        error_log('Error fetching plugin information: ' . $plugin_info->get_error_message());
        return;
    }

    // Get the download link
    $download_link = $plugin_info->download_link;
	
	echo '<h1>' . esc_html($download_link) . '</h1>';

    // Download the plugin
    $result = WP_Filesystem();
	
    // Check if the filesystem is available
    if (empty($result)) {
        require_once ABSPATH . 'wp-admin/includes/file.php';
        WP_Filesystem();
		echo '<h1> Empty FS </h1>';
    }

    // Specify the path to save the plugin
    $upload_dir = wp_upload_dir();
    $plugin_dir = $upload_dir['basedir'] . '/plugins/';
    
    // Create directory if it doesn't exist
    if (!file_exists($plugin_dir)) {
        wp_mkdir_p($plugin_dir);
    }

    // Download the plugin zip file
    $zip_file = $plugin_dir . $slug . '.zip';
    $response = wp_remote_get($download_link);

    if (is_wp_error($response)) {
        error_log('Error downloading plugin: ' . $response->get_error_message());
        return;
    }
	echo '<h1>' . esc_html($plugin_dir) . '</h1>';

    // Save the zip file to the server
    file_put_contents($zip_file, wp_remote_retrieve_body($response));
	echo '<h1> uploaded zip: the uploaded zip is written to wp-contents/uploads/plugins/.zip file </h1>';

    // Unzip the downloaded plugin
    include_once ABSPATH . 'wp-admin/includes/class-pclzip.php';

	// Define the target directory to unzip
	$target_dir = ABSPATH . 'wp-content/plugins/';

    $archive = new PclZip($zip_file);
    if ($archive->extract(PCLZIP_OPT_PATH, $target_dir) == 0) {
        error_log('Error unzipping plugin: ' . $archive->errorInfo(true));
    }

    // Cleanup: remove the zip file
    unlink($zip_file);

    // Activate the plugin
    activate_plugin($slug . '/' . $slug . '.php');
}

// Example function to log messages with timestamps
function log_with_timestamp($message) {
    $log_file = plugin_dir_path(__FILE__) . 'my_plugin_log.txt'; // Change to your log file path
    $timestamp = date('[Y-m-d H:i:s]'); // Format: [YYYY-MM-DD HH:MM:SS]
    file_put_contents($log_file, $timestamp . ' ' . $message . PHP_EOL, FILE_APPEND);
}