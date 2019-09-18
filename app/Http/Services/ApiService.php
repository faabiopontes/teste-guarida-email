<?php

namespace App\Http\Services;

use GuzzleHttp\Client;

class ApiService
{
    public $base_api;

    function __construct()
    {
        $this->client = Client();
        $this->base_api = env('API_ENDPOINT');
    }
}
