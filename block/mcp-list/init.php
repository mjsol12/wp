<?php
/*
Plugin Name: My Existing Plugin
Description: A custom plugin with a Gutenberg block.
Version: 1.0
Author: Your Name
*/

// Register the block using metadata from block.json
function my_mcp_index_register_block() {
    register_block_type(__DIR__ . '/build');
}

add_action('init', 'my_mcp_index_register_block');
