<?php

namespace FHM\Controllers;

use FHM\Configs\Config;
use FHM\Services\ViewService;

class AdminController
{

    public function __construct()
    {
        add_action('admin_menu', [$this, 'registerMenu']);
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
        $endpoint_url = Config::get_endpoint_url();
        $response = wp_remote_get($endpoint_url);
        $endpoint_status = (is_wp_error($response)) ? 'Down' : 'OK';

        echo ViewService::render("admin.settings", [
            'endpoint_url'    => $endpoint_url,
            'endpoint_status' => $endpoint_status,
        ]);
    }
}
