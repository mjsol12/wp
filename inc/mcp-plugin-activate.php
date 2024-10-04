<?php
/**
 * @package McpPlugin
 */

class McpPluginActivate
{
    public static function activate(){
        flush_rewrite_rules();
    }
}