<?php

namespace FHM\Controllers;

use FHM\Configs\Config;
use FHM\Services\ViewService as View;

class ShortcodeController
{
    public static function register()
    {
        add_action('wp_enqueue_scripts', [self::class, 'registerAssets']);
        add_shortcode('forex_heatmap', [self::class, 'render']);
    }

    public static function registerAssets()
    {
        wp_register_script(
            'fh-heatmap',
            FHM_PLUGIN_URL . 'resources/js/heatmap.js',
            ['jquery'],
            FHM_VERSION,
            true
        );

        wp_register_style(
            'fh-heatmap-css',
            FHM_PLUGIN_URL . 'resources/css/heatmap.css',
            [],
            FHM_VERSION
        );

        wp_register_script(
            'data-tables',
            FHM_PLUGIN_URL . 'resources/js/dataTables.min.js',
            ['jquery'],
            FHM_VERSION,
            true
        );

        wp_register_style(
            'data-tables-css',
            FHM_PLUGIN_URL . 'resources/css/dataTables.dataTables.min.css',
            [],
            FHM_VERSION
        );

        $options = get_option('fhm_settings', []);
        $defaults = Config::get_settings_defaults();

        wp_localize_script('fh-heatmap', 'FH_CONFIG', [
            'restUrl' => esc_url_raw(Config::get_endpoint_url()),
            'nonce'   => wp_create_nonce('wp_rest'),
            'updateInterval' => $options['ui_update_interval'] ?? $defaults['ui_update_interval']
        ]);
    }

    public static function render($atts = [])
    {
        $atts = shortcode_atts([
            'pairs' => '',   // comma separated, optional
            'view'  => 'percent',
        ], $atts, 'forex_heatmap');

        wp_enqueue_script('fh-heatmap');
        wp_enqueue_style('fh-heatmap-css');

        wp_enqueue_script('data-tables');
        wp_enqueue_style('data-tables-css');

        // Render the view (resources/views/shortcode.php)
        return View::render('shortcode', ['fhm_atts' => $atts]);
    }
}
