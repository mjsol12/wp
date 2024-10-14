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
	

	// custom user profile
	private $custom_user_fields = array("phone", "address", "bio");
	private $custom_fields = array("location","address","place");

	function __construct () {
		$this->create_post_type();
	}

	function register() {

		add_action('admin_enqueue_scripts', array($this, 'enqueue'));

		add_action('admin_menu', array($this, 'add_admin_pages'));

		
        // Hooks for adding custom fields to user profiles
        add_action('show_user_profile', array($this, 'add_custom_user_profile_fields'));
        add_action('edit_user_profile', array($this, 'add_custom_user_profile_fields'));

        // Hooks for saving custom fields
        add_action('personal_options_update', array($this, 'save_custom_user_profile_fields'));
        add_action('edit_user_profile_update', array($this, 'save_custom_user_profile_fields'));
	}

	function add_admin_pages(){
		add_menu_page( 'MCP Title', 'MCP', 'manage_options', 'mcp_plugin', array( $this, 'admin_index'), 'dashicons-sticky', 5 );
	}

	function admin_index(){
		//require template

	}

	function custom_meta_box(){
		//require template
		add_meta_box(
			'my_custom_meta_box_id', // Unique ID
			'MCP Meta Box', // Box title
			array( $this, 'meta_box_callback'), // Callback function
			'mcp' // Post type
		);
	}

	function meta_box_callback($post) {
		// Add a nonce field for security
		wp_nonce_field('mcp_meta_box_nonce_action', 'mcp_meta_box_nonce'); 
	
		foreach ($this->custom_fields as $field) {
			// Retrieve existing value from the database
			$value = get_post_meta($post->ID, '_my_custom_meta_key_' . $field, true);
			
			// Output the field
			echo '<div style="margin: 25px 25px">';
			echo '<label for="my_custom_field_' . esc_attr($field) . '">' . esc_html($field) . ': </label>';
			echo '<input type="text" id="my_custom_field_' . esc_attr($field) . '" name="my_custom_field[' . esc_attr($field) . ']" value="' . esc_attr($value) . '" />';
			echo '</div>';
		}
	}
	function meta_box_save($post_id) {
		// Check if our nonce is set and verify it
		if (!isset($_POST['mcp_meta_box_nonce']) || !wp_verify_nonce($_POST['mcp_meta_box_nonce'], 'mcp_meta_box_nonce_action')) {
			return;
		}
	
		// Check if the user has permission to save the data
		if (!current_user_can('edit_post', $post_id)) {
			return;
		}
	
		// Save or update the metadata
		// Loop through each custom field and save the value
        foreach ($this->custom_fields as $field) {
            // Check if the custom field value exists in the POST request
            if (isset($_POST['my_custom_field'][$field])) {
				// print_r(isset($_POST['my_custom_field'][$field]));
                // Sanitize the input before saving
                $sanitized_value = sanitize_text_field($_POST['my_custom_field'][$field]);
                // Update or add the meta value in the database
                update_post_meta($post_id, '_my_custom_meta_key_' . $field, $sanitized_value);
            }
        }
	}

    // Method to display custom fields in user profile
    public function add_custom_user_profile_fields($user) {
        // Output fields for each custom field
        echo '<h3>' . esc_html__('Additional Information', 'textdomain') . '</h3>';
        echo '<table class="form-table">';

        foreach ($this->custom_user_fields as $field) {
            $value = get_user_meta($user->ID, '_my_custom_user_meta_' . $field, true);
            echo '<tr>';
            echo '<th><label for="my_custom_field_' . esc_attr($field) . '">' . esc_html(ucfirst($field)) . '</label></th>';
            echo '<td>';
            echo '<input type="text" id="my_custom_field_' . esc_attr($field) . '" name="my_custom_field[' . esc_attr($field) . ']" value="' . esc_attr($value) . '" class="regular-text" />';
            echo '</td>';
            echo '</tr>';
        }

        echo '</table>';
    }

    // Method to save custom fields
    public function save_custom_user_profile_fields($user_id) {
        // Check user permissions
        if (!current_user_can('edit_user', $user_id)) {
            return;
        }

        // Loop through each custom field and save the value
        foreach ($this->custom_user_fields as $field) {
            if (isset($_POST['my_custom_field'][$field])) {
                // Sanitize and save the input
                $sanitized_value = sanitize_text_field($_POST['my_custom_field'][$field]);
                update_user_meta($user_id, '_my_custom_user_meta_' . $field, $sanitized_value);
            } else {
                // If the field is not set in the request, delete the meta key
                delete_user_meta($user_id, '_my_custom_user_meta_' . $field);
            }
        }
    }


	protected function create_post_type() {
		add_action( 'init', array( $this, 'custom_post_type' ) );
		add_action('add_meta_boxes', array( $this, 'custom_meta_box' )  );
		add_action('save_post', array( $this, 'meta_box_save' )  );
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

require_once MCP_PATH . 'inc/mcp-plugin-activate.php';
register_activation_hook( __FILE__, array('McpPluginActivate', 'activate'));

require_once MCP_PATH . 'inc/mcp-plugin-deactivate.php';
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