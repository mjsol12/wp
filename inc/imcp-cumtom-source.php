<?php
/**
 * @package IMcpPlugin
 */

class ImcpcCustomSource 
{
    public function register () {
        add_action( 'init', 'wpse_register_block_bindings' );
    }


    function wpse_register_block_bindings() {
        register_block_bindings_source( 'wpse/featured-image', array(
            'label'              => esc_html__( 'Featured Image', 'wpse' ),
            'get_value_callback' => 'wpse_featured_image_bindings'
        ) );
    }
    
    function wpse_featured_image_bindings( $args ) {
        if ( ! isset( $args['key'] ) ) {
            return null;
        }
    
        if ( ! has_post_thumbnail() ) {
            return null;
        }
    
        $id  = get_post_thumbnail_id();
        $url = get_the_post_thumbnail_url();
        $alt = get_post_meta( $id, '_wp_attachment_image_alt', true );
    
        switch ( $args['key'] ) {
            case 'url':
                return esc_url( $url );
            case 'alt':
                return  esc_attr( $alt );
            default:
                return null;
        }
    }
}