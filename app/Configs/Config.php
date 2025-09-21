<?php

namespace FHM\Configs;

class Config
{

    // Internal Endpoint
    public static $endpoint_route_namespace = 'fhm/v1';
    public static $endpoint_route = 'data';

    // External API
    public static $apiUrl = "https://www.myfxbook.com/getHeatMapData.json";

    // Heatmap Data DB Transient Name
    public static $transient_name = 'fhm_latest_heatmap';

    
    public static function get_endpoint_route()
    {
        return self::$endpoint_route_namespace . '/' . self::$endpoint_route;
    }

    public static function get_endpoint_url()
    {
        return rest_url(self::get_endpoint_route());
    }

    public static function get_settings_defaults()
    {
        return [
            'symbols'           => '',    // empty = use default full list
            'ui_update_interval'   => 60,  // seconds
            'cache_lifetime'    => 60,   // seconds
        ];
    }
}
