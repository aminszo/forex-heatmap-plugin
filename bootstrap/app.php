<?php

// Composer Autoloader
require __DIR__ . '/vendor/autoload.php';

use ForexHeatmap\Controllers\ShortcodeController;
use ForexHeatmap\Controllers\HeatmapEndpointController;


// Define plugin constants
define('FHM_VERSION', '1.0.0');
define('FHM_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('FHM_PLUGIN_URL', plugin_dir_url(__FILE__));
define('FHM_PLUGIN_FILE', __FILE__);


// Register Shortcode
add_action('init', function () {
    ShortcodeController::register();
});


// Register Endpoint
HeatmapEndpointController::register();


// Add every_minute time interval to wp-cron
add_filter('cron_schedules', function ($schedules) {
    $schedules['every_minute'] = ['interval' => 60, 'display' => 'Every Minute'];
    return $schedules;
});

register_activation_hook(FHM_PLUGIN_FILE, function () {
    if (! wp_next_scheduled('forex_heatmap_fetch_data')) {
        wp_schedule_event(time(), 'every_minute', 'forex_heatmap_fetch_data');
    }
    flush_rewrite_rules();
});

add_action('forex_heatmap_fetch_data', function () {
    (new \ForexHeatmap\Services\HeatmapDataService())->fetchAndCache();
});

register_deactivation_hook(FHM_PLUGIN_FILE, function () {
    wp_clear_scheduled_hook('forex_heatmap_fetch_data');
});
