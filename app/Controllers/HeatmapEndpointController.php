<?php

namespace FHM\Controllers;

use FHM\Configs\Config;
use FHM\Services\HeatmapDataService;

class HeatmapEndpointController
{
    public static function register()
    {
        add_action('rest_api_init', function () {
            $route_namespace = Config::$endpoint_route_namespace;
            $route = Config::$endpoint_route;

            register_rest_route($route_namespace, $route, [
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
