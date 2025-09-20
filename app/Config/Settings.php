<?php
namespace FHM\Config;

class Settings {
    public static function defaults() {
        return [
            'symbols'       => '',    // empty = use default full list
            'update_interval' => 60,  // seconds
            'cache_lifetime' => 60,   // seconds
        ];
    }
}
