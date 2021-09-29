<?php
/*
Author: Vidsoe
Author URI: https://vidsoe.com
Description: Improvements and fixes for server-sent events.
Domain Path:
License: GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Network: true
Plugin Name: IFSSE
Plugin URI: https://github.com/vidsoe/ifsse
Requires at least: 5.6
Requires PHP: 5.6
Text Domain: ifsse
Version: 0.9.29
*/

defined('ABSPATH') or die('Hi there! I\'m just a plugin, not much I can do when called directly.');
require_once(plugin_dir_path(__FILE__) . 'classes/loader.php');
IFSSE\Loader::load(__FILE__);
