<?php
/**
 * @package IMcpPlugin
 */

class ImcpPluginRegister
{
    
    public function register() {
		add_action( 'init', array( $this, 'custom_post_type' ) );
        $this->custom_meta();
    }

    function custom_meta(){
        $metaFields = array("type", "credit", "rights", "people", "place");

		foreach ($metaFields as $field) {
			register_meta( 
                "post",
                 "imcp_" . $field, 
                 array(
                    'object_subtype'    => 'imcp' , 
                    'show_in_rest' 	    => true,
                    'single'            => true,
                    'type'              => 'string',
                    'sanitize_callback' => 'wp_strip_all_tags'
                )
            );
		}

		register_meta( 
			"post",
			 "imcp_accession", 
			 array(
				'object_subtype'    => 'imcp' , 
				'show_in_rest' 	    => true,
				'single'            => true,
				'type'              => 'number',
				'sanitize_callback' => 'wp_strip_all_tags'
			)
		);
		
		register_meta( 
			"post",
			 "imcp_created_craft", 
			 array(
				'object_subtype'    => 'imcp' , 
				'show_in_rest' 	    => true,
				'single'            => true,
				'type'              => 'string',
				'sanitize_callback' => 'wp_strip_all_tags'
			)
		);
		
		register_meta( 
			"post",
			 "imcp_property_type", 
			 array(
				'object_subtype'    => 'imcp' , 
				'show_in_rest' 	    => true,
				'single'            => true,
				'type'              => 'string',
				'sanitize_callback' => 'wp_strip_all_tags'
			)
		);
		
		register_meta( 
			"post",
			 "imcp_thumbnail", 
			 array(
				'object_subtype'    => 'imcp' , 
				'show_in_rest' 	    => true,
				'single'            => true,
				'type'              => 'string',
				'sanitize_callback' => 'wp_strip_all_tags'
			)
		);
		
		register_meta( 
			"post",
			 "imcp_featured_image_url", 
			 array(
				'object_subtype'    => 'imcp' , 
				'show_in_rest' 	    => true,
				'single'            => true,
				'type'              => 'string',
				'sanitize_callback' => 'esc_url_raw'
			)
		);
		
		register_meta( 
			"post",
			 "imcp_featured_image_alt", 
			 array(
				'object_subtype'    => 'imcp' , 
				'show_in_rest' 	    => true,
				'single'            => true,
				'type'              => 'string',
				'sanitize_callback' => 'wp_strip_all_tags'
			)
		);
	}

    function custom_post_type(){	
        // create a custom post type 
		$labels = array(
			'name'               => _x('My Custom Plugin', 'post type general name'),
			'singular_name'      => _x('IMCP Binding', 'post type singular name'),
			'menu_name'          => __('IMCP Binding'),
			'name_admin_bar'     => __('IMCP Binding'),
			'add_new'            => __('Add New'),
			'add_new_item'       => __('Add New IMCP'),
			'edit_item'          => __('Edit IMCP'),
			'new_item'           => __('New IMCP'),
			'view_item'          => __('View IMCP'),
			'all_items'          => __('All IMCP'),
			'search_items'       => __('Search IMCP'),
			'not_found'          => __('No IMCPs found'),
			'not_found_in_trash' => __('No IMCPs found in Trash')
		);
		$args = array(
			'labels'             => $labels,
			'supports'           => array('title', 'editor', 'custom-fields'), // Enable the default WordPress editor features
			'public'             => true,
			'has_archive'        => true,
			'show_ui'            => true, // ensures that the post type is displayed in the WordPress admin dashboard.
			'show_in_menu'       => true, // ensures that the custom post type will appear in the admin menu.
			'show_in_admin_bar'  => true, // Makes the custom post type available in the top admin bar when adding new items.
			'menu_position'      => 5, // Position of the MCP tab in the menu
			'menu_icon'          => 'dashicons-sticky', 
			'show_in_rest'       => false,  // Enable REST API for Gutenberg editor
		);

		register_post_type('imcp', $args );
    }

}