<?php

namespace Support\Utility;

use Core\Http\Request;
use Support\Utility\Client;
use Support\Utility\Session;

class Location
{
    private $client,
            $session,
            $ip;

    public function __construct(Client $client, Session $session, $ip)
    {
        $this->client = $client;
        $this->session = $session;
        $this->ip = $ip;
    }

    private function formatData($data)
    {
        return [
            'continent' => $data['continent'] ?? $data['geoplugin_continentName'] ?? null,
            'continent_code' => $data['continent_code'] ?? $data['geoplugin_continentCode'] ?? null,
            'country' => $data['country'] ?? $data['geoplugin_countryName'] ?? null,
            'country_code' => $data['country_code'] ?? $data['geoplugin_countryCode'] ?? null,
            'country_phone' => $data['country_phone'] ?? null,
            'region' => $data['region'] ?? $data['geoplugin_region'] ?? null,
            'city' => $data['city'] ?? $data['geoplugin_city'] ?? null,
            'timezone' => $data['timezone'] ?? $data['geoplugin_timezone'] ?? null,
            'currency_code' => $data['currency_code'] ?? $data['geoplugin_currencyCode'] ?? null,
            'currency_rates' => $data['currency_rates'] ?? $data['geoplugin_currencyConverter'] ?? null,
            'asn' => $data['asn'] ?? null,
            'org' => $data['org'] ?? null,
            'isp' => $data['isp'] ?? null,
        ];
    }

    public function collect()
    {
        if (! $this->session->exists('location')) {
            $data = $this->client->ipwhois($this->ip);
            if (! $data) {
                $data = $this->client->geoplugin($this->ip);
            }
            $this->session->set(
                'location', $this->formatData($data)
            );
        }
    }

    public function get($key, $default = 'unknown')
    {
        return $this->session->get('location')[$key] ?? $default;
    }
}
?>