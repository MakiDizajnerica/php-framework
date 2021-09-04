<?php

namespace Core\Http;

class Response
{

    /**
     * @param string $response
     * @param integer $code [optional]
     * @return void
     */
    public function render($response, $code = 200)
    {
        http_response_code($code);
        echo $response;
    }
}
?>