<?php

namespace ForexHeatmap\Services;

use eftec\bladeone\BladeOne;

class ViewService
{
    protected static $instance = null;

    public static function getInstance(): BladeOne
    {
        if (self::$instance === null) {
            $views = FHM_PLUGIN_DIR . 'resources/views';
            $cache = FHM_PLUGIN_DIR . 'storage/cache';
            self::$instance = new BladeOne($views, $cache, BladeOne::MODE_AUTO);
        }
        return self::$instance;
    }

    public static function render(string $view, array $data = [])
    {
        $blade = self::getInstance();
        return $blade->run($view, $data);
    }
}
