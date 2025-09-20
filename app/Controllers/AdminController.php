<?php

namespace FHM\Controllers;

use FHM\Services\ViewService;

class AdminController
{

    public function __construct()
    {
        add_action('admin_menu', [$this, 'registerMenu']);
    }

    public function registerMenu()
    {
        add_options_page(
            'Forex Heatmap Settings',
            __('Forex Heatmap', 'FHM'),
            'manage_options',
            'fhm-settings',
            [$this, 'renderPage']
        );
    }

    public function renderPage()
    {
        $endpoint_url = rest_url('fhm/v1/data');

        echo ViewService::render("admin.settings", [
            'endpoint_url'    => $endpoint_url,
        ]);
    }
}
