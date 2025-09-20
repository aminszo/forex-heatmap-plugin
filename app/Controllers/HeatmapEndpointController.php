<?php

namespace FHM\Controllers;

use FHM\Services\HeatmapDataService;

class HeatmapEndpointController
{
    public static function register()
    {
        add_action('rest_api_init', function () {
            register_rest_route('fhm/v1', '/data', [
                'methods' => 'GET',
                'callback' => [self::class, 'getData'],
                'permission_callback' => '__return_true',
            ]);
        });
    }

    public static function getData()
    {
        // return ['status' => 'ok'];
        $service = new HeatmapDataService();
        $data = $service->getLatest();
        return rest_ensure_response($data);
    }
}
