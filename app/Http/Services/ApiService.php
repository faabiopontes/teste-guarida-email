<?php

namespace App\Http\Services;

use GuzzleHttp\Client;

class ApiService
{
    public $base_api;

    function __construct()
    {
        $this->client = new Client();
    }

    public function parseInvoiceData($invoiceData)
    {
        $parsedInvoiceData = [];
        return $parsedInvoiceData;
    }

    public function sendInvoice($invoiceData)
    {
        $parsedInvoiceData = $this->parseInvoiceData($invoiceData);
        $this->client->request('POST', env('API_ENDPOINT'), [
            'multipart' => $parsedInvoiceData
        ]);
    }
}
