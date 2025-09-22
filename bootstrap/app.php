<?php

// Composer Autoloader
require FHM_PLUGIN_DIR . '/vendor/autoload.php';

use FHM\Controllers\AdminController;
use FHM\Controllers\ShortcodeController;
use FHM\Controllers\HeatmapEndpointController;


// Register Shortcode
add_action('init', function () {
    ShortcodeController::register();

    if (is_admin()) {
        new AdminController;
    }
});

// Register Internal Endpoint
HeatmapEndpointController::register();

// Register Admin Menu


// Add every_minute time interval to wp-cron
add_filter('cron_schedules', function ($schedules) {
    $schedules['every_minute'] = ['interval' => 60, 'display' => 'Every Minute'];
    return $schedules;
});

// Register schedule hook
register_activation_hook(FHM_PLUGIN_FILEPATH, function () {
    if (! wp_next_scheduled('forex_heatmap_fetch_data')) {
        wp_schedule_event(time(), 'every_minute', 'forex_heatmap_fetch_data');
    }
    flush_rewrite_rules();
});

add_action('forex_heatmap_fetch_data', function () {
    (new \FHM\Services\HeatmapDataService())->fetchAndCache();
});

// Deregister schedule hook
register_deactivation_hook(FHM_PLUGIN_FILEPATH, function () {
    wp_clear_scheduled_hook('forex_heatmap_fetch_data');
});
