<?php

namespace Support\Utility;

use Exception;

class Client
{
    public function ipwhois($ip)
    {
        $host = sprintf('http://ipwhois.app/json/%s?lang=en', $ip);
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => $host,
            CURLOPT_HTTPGET => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                'Accept: application/json'
            ],
        ]);   
        $response = curl_exec($curl);
        curl_close($curl);
        if ($response) {
            $response = json_decode($response, true);
            if ($response['success']) {
                return $response;
            }
        }
        return [];
    }

    public function geoplugin($ip)
    {
        $host = sprintf('http://www.geoplugin.net/json.gp?ip=%s&lang=en&base_currency=RSD', $ip);
        try {
            $response = file_get_contents(
                $host,
                false,
                stream_context_create(['ssl' => [
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                ]])
            );
            if ($response) {
                $response = json_decode($response, true);
                if ($response['geoplugin_status'] == '200') {
                    return $response;
                }
            }
        }
        catch (Exception $e) {
            //
        }
        return [];
    }
}
?>