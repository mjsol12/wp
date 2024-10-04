<?php
/**
 * @package McpPlugin
 */

class McpPluginDeactivate
{
    public static function deactivate() {
		// Unregister the post type, so the rules are no longer in memory.
		// unregister_post_type( 'mcp' );
		// Clear the permalinks to remove our post type's rules from the database.
		flush_rewrite_rules();
	}
}