<?php

namespace FHM\Helpers;

use FHM\Configs\Config;

class Settings
{

    protected static $instance = null;

    public $options_key = 'fhm_settings';
    public $options = null;

    public static function instance()
    {
        if (self::$instance === null) {
            self::$instance = new Settings;
        }
        return self::$instance;
    }

    private function __construct()
    {
        $this->options = get_option($this->options_key, []);
    }

    public function get_api_url()
    {
        return $this->options['external_api_url'] ?? Config::$default_api_url;
    }

    public function ui_update_interval() {
        return $options['ui_update_interval'] ?? Config::get_settings_defaults()['ui_update_interval'];
    }

}
