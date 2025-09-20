<?php

// Composer Autoloader
require FHM_PLUGIN_DIR . '/vendor/autoload.php';

use ForexHeatmap\Controllers\AdminController;
use ForexHeatmap\Controllers\ShortcodeController;
use ForexHeatmap\Controllers\HeatmapEndpointController;


// Register Shortcode
add_action('init', function () {
    ShortcodeController::register();
});


// Register Endpoint
HeatmapEndpointController::register();

if (is_admin()) {
    new AdminController;
}

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
