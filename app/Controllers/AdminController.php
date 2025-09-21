<?php

namespace FHM\Controllers;

use FHM\Configs\Config;
use FHM\Services\ViewService;

class AdminController
{

    public function __construct()
    {
        add_action('admin_menu', [$this, 'registerMenu']);
        add_action('admin_post_fhm_save_settings', [$this, 'saveSettings']);
        add_action('admin_post_fhm_test_endpoint', [$this, 'testEndpoint']);
    }

    public function registerMenu()
    {
        add_menu_page(
            'Forex Heatmap Settings',
            __('Forex Heatmap', 'FHM'),
            'manage_options',
            'fhm-settings',
            [$this, 'renderPage'],
            'dashicons-editor-table',
            30
        );

        add_submenu_page(
            'fhm-settings',
            'subsettings',
            'subsettings',
            'manage_options',
            'fhm-submenu-settings',
            [$this, 'renderSubmenuPage'],
        );
    }

    public function renderSubmenuPage()
    {
        echo ViewService::render("admin.settings", [
            'title' => "Title",
        ]);
    }

    public function renderPage()
    {
        // echo "ok";
        $endpoint_url = Config::get_endpoint_url();

        $nonce_field = wp_nonce_field('fhm_settings_save_action', 'fhm_settings_nonce', true, false);

        $options = get_option('fhm_settings', []);

        echo ViewService::render("admin.settings", [
            'options'           => $options,
            'endpoint_url'      => $endpoint_url,
            'nonce_field'       => $nonce_field,
        ]);
    }

    public function saveSettings()
    {
        if (! current_user_can('manage_options')) {
            wp_die('Unauthorized');
        }

        check_admin_referer('fhm_settings_save_action', 'fhm_settings_nonce');

        $defaults = Config::get_settings_defaults();

        $options = [
            'ui_update_interval'    => intval($_POST['ui_update_interval'] ?? $defaults['ui_update_interval']),
            'external_api_url'      => esc_url($_POST['external_api_url']),
        ];

        update_option('fhm_settings', $options);

        wp_redirect(admin_url('admin.php?page=fhm-settings&saved=1'));
        exit;
    }

    public function testEndpoint()
    {
        $endpoint_url = Config::get_endpoint_url();
        $response = wp_remote_get($endpoint_url);
        $success = is_wp_error($response) ? false : true;

        if (strlen($response['body']) > 500) {
            wp_send_json_success($response['body']);
        }

        wp_send_json_error();
    }
}
