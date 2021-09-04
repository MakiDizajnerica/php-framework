<?php

namespace Core\Http;

class Request
{
    public function getMethod()
    {
        return(strtolower(getenv('REQUEST_METHOD')));
    }

    public function path()
    {
        return strtolower(
            sprintf('http://%s%s', getenv('HTTP_HOST'), getenv('REQUEST_URI'))
        );
    }

    public static function parseURI($parts = 0)
    {
        $uri = strtolower(filter_var(getenv('REQUEST_URI') ?? '/', FILTER_SANITIZE_URL));
        if (($position = strpos($uri, '?')) !== false) {
            $uri = substr($uri, 0, $position);
        }
        if ($parts > 0) {
            $uri_parts = explode('/', $uri);
            $uri_parts_count = count($uri_parts);
            for ($x = $uri_parts_count; $x > $parts; $x--) {
                unset($uri_parts[$x]);
            } 
            return(implode('/', $uri_parts));
        }
        return($uri);
    }

    public function ip($default = 'unknown') {

        return '152.89.160.212';

        $collectFrom = [
            'HTTP_CF_CONNECTING_IP',
            'HTTP_CLIENT_IP',
            'HTTP_X_FORWARDED_FOR',
            'REMOTE_ADDR',            
        ];
        foreach ($collectFrom as $param) {
            if (($ip = getenv($param)) !== false) {
                return $ip;
            }
        }
        return $default;    
    }
}
?>