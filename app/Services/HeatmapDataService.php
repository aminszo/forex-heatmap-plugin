<?php

namespace FHM\Services;

use FHM\Configs\Config;

class HeatmapDataService
{

    public function fetchFromApi(array $symbols = [])
    {

        $options = get_option('fhm_settings', []);
        $url = $options['external_api_url'] ?? Config::$apiUrl;

        $allSymbols = "9,8,47,10,1234,11,103,12,46,1245,6,13,14,15,17,18,7,2114,19,20,21,22,1246,23,1,1233,107,24,25,4,2872,137,48,1236,1247,2012,2,1863,3240,26,49,27,28,2090,131,5,29,5779,31,34,3,36,37,38,2076,40,41,42,43,45,3005,3473,50,2115,2119,1815,2521,51,5435,5079,1893";
        $symbolsStr = $symbols ? implode(',', $symbols) : $allSymbols; // default
        $payload = ['symbols' => $symbolsStr];

        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => http_build_query($payload),
            CURLOPT_HTTPHEADER => ["Content-Type: application/x-www-form-urlencoded"],
            CURLOPT_CONNECTTIMEOUT => 10, // wait max 10s to connect
            CURLOPT_TIMEOUT => 30,        // max 15s total
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curl_errorno = curl_errno($ch);
        $curl_error = curl_error($ch);

        curl_close($ch);

        return $this->validateResponse($curl_errorno, $curl_error, $response, $httpCode);
    }

    private function validateResponse($curl_errorno, $curl_error, $response, $httpCode)
    {

        if ($curl_errorno) {
            return [false, "cURL error: $curl_error"];
        }
        if ($httpCode !== 200) {
            return [false, "HTTP error: $httpCode \nResponse: $response"];
        }

        if (!$response) {
            return [false, 'Empty response'];
        }

        $decoded = json_decode($response, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            return [false, 'Invalid JSON: ' . json_last_error_msg()];
        }

        if (!isset($decoded['data']) || !is_array($decoded['data'])) {
            return [false, 'Missing or invalid "data" key'];
        }

        // Check structure of one symbol with open/close
        foreach ($decoded['data'] as $symbol => $timeframes) {
            if (!is_array($timeframes)) {
                return [false, "Invalid structure for $symbol"];
            }

            foreach ($timeframes as $tf => $values) {
                if (!isset($values['open'], $values['close'])) {
                    return [false, "Missing open/close for $symbol timeframe $tf"];
                }
                if (!is_numeric($values['open']) || !is_numeric($values['close'])) {
                    return [false, "Non-numeric values for $symbol timeframe $tf"];
                }
            }

            // Check only the first symbol for schema validation
            break;
        }

        return [true, $decoded['data']];
    }

    public function fetchAndCache()
    {
        [$success, $result] = $this->fetchFromApi();

        if (true === $success) {
            set_transient(Config::$transient_name, $result, 60);
            return true;
        }

        error_log("Heatmap fetch failed: " . $result);
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
