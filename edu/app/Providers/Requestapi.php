<?php

namespace App\Providers;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\DB;
use Config;

class Requestapi
{
    protected $url;
    protected $http;
    protected $headers;

    public function __construct($url, $header = [])
    {
        $this->url = config('app.SERVER_IP').$url;
        //dd($this->url);
        $this->http = new Client(['http_errors' => false]);
        $this->headers = [
            'cache-control' => 'no-cache',
            'content-type' => 'application/json',
        ];
        $this->headers = array_merge($this->headers,$header);
    }

    public function getResponse(array $param = null)
    {
        $full_path = $this->url;

        $request = $this->http->post($full_path, [
            'headers'         => $this->headers,
            'timeout'         => 60,
            'connect_timeout' => true,
            'body'            => json_encode($param),
            'http_errors' => false
        ]);

        $response = $request ? $request->getBody()->getContents() : null;
        $status = $request ? $request->getStatusCode() : 500;

        if ($response && $status === 200 && $response !== 'null') {
            return $response;
        }

        return \json_encode(['error_code' => $status]);
    }

    public function methodGet(){
        $full_path = $this->url;
        $request = $this->http->get($full_path,[
            'headers'         => $this->headers,
            'timeout'         => 60,
            'connect_timeout' => true,
            'http_errors' => false
            
        ]);

        $response = $request ? $request->getBody()->getContents() : null;
        $status = $request ? $request->getStatusCode() : 500;

        if ($response && $status === 200 && $response !== 'null') {
            return $response;
        }

        return \json_encode(['error_code' => $status]);
    } 

}