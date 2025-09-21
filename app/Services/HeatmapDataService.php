<?php

namespace FHM\Services;

use FHM\Configs\Config;

class HeatmapDataService
{

    private function fetchFromApi(array $symbols = [])
    {
        $allSymbols = "9,8,47,10,1234,11,103,12,46,1245,6,13,14,15,17,18,7,2114,19,20,21,22,1246,23,1,1233,107,24,25,4,2872,137,48,1236,1247,2012,2,1863,3240,26,49,27,28,2090,131,5,29,5779,31,34,3,36,37,38,2076,40,41,42,43,45,3005,3473,50,2115,2119,1815,2521,51,5435,5079,1893";
        $symbolsStr = $symbols ? implode(',', $symbols) : $allSymbols; // default
        $payload = ['symbols' => $symbolsStr];

        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => Config::$apiUrl,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => http_build_query($payload),
            CURLOPT_HTTPHEADER => ["Content-Type: application/x-www-form-urlencoded"],
            CURLOPT_CONNECTTIMEOUT => 10, // wait max 10s to connect
            CURLOPT_TIMEOUT => 30,        // max 15s total
        ]);

        $response = curl_exec($ch);
        curl_close($ch);

        if (!$response) {
            return false;
        }

        $data = json_decode($response, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            return false;
        }

        return $data['data'] ?? null;
    }

    public function fetchAndCache()
    {
        $data = $this->fetchFromApi();
        if (!is_null($data)) {
            set_transient(Config::$transient_name, $data, 60);
            return true;
        }

        return false;
    }

    public function getLatest()
    {
        $cached = get_transient(Config::$transient_name);
        if ($cached) return $cached;
        $this->fetchAndCache();
        return get_transient(Config::$transient_name);
    }
}
