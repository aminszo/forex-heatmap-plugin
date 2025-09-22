<?php

/**
 * Plugin Name: Forex Heat Map
 * Plugin URI:  https://iranrebate/wp-plugins/forex-heat-map
 * Description: A plugin to display a Forex heat map using shortcode.
 * Version:     1.0.0
 * Author:      IranRebate
 * Author URI:  https://iranrebate.com
 * License:     GPL v2.0
 * License URI: https://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain: FHM
 * Domain Path: /languages
 */

// Exit if accessed directly
if (! defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('FHM_VERSION', '1.0.0');
define('FHM_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('FHM_PLUGIN_URL', plugin_dir_url(__FILE__));
define('FHM_PLUGIN_FILEPATH', __FILE__);
define('FHM_PLUGIN_FILENAME', plugin_basename(__FILE__));

// Bootstrap the plugin
require_once "bootstrap/app.php";
