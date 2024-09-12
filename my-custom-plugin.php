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

//defining the url,path and slug for the plugin
define( 'MCP_URL', plugin_dir_url(__FILE__) );
define( 'MCP_PATH', plugin_dir_path(__FILE__) );

include_once(MCP_PATH.'includes/init.php');
require_once(MCP_PATH.'block/init.php');