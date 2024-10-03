<?php
/**
 * Trigger to uninstall the plugin
 * 
 * @package McpddPlugin
 */

 if(! defined( 'WP_UNINSTALL_PLUGIN' )) {
    exit;
 }


 // clear database stored mcp
$mcps = get_posts( array( 'post_type' => 'mcp', 'numberposts' => -1));

foreach ( $mcps as $mcp ) {
    wp_delete_post( $mcp->ID, true );
}