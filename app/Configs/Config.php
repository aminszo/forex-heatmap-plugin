<?php
namespace FHM\Configs;

class Config {

    public static $endpoint_route_namespace = 'fhm/v1';
    public static $endpoint_route = 'data';

    public static function get_endpoint_route() {
        return self::$endpoint_route_namespace . '/' . self::$endpoint_route;
    }

    public static function get_endpoint_url() {
        return rest_url(self::get_endpoint_route());
    }

    public static function defaults() {
        return [
            'symbols'       => '',    // empty = use default full list
            'update_interval' => 60,  // seconds
            'cache_lifetime' => 60,   // seconds
        ];
    }
}
