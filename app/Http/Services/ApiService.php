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

    public function parseInvoice($invoice_data)
    {
        $parsed_invoice = [];
        foreach ($invoice_data as $invoice_data_attribute) {
            $attribute_array = array('name' => $invoice_data_attribute['key']);
            if (array_key_exists('filename', $invoice_data_attribute)) {
                $attribute_array['contents'] = $invoice_data_attribute['contents'];
                $attribute_array['filename'] = $invoice_data_attribute['filename'];
            } else {
                $attribute_array['contents'] = $invoice_data_attribute['value'];
            }
            $parsed_invoice[] = $attribute_array;
        }
        return $parsed_invoice;
    }

    public function sendArrayInvoices($array_invoices)
    {
        foreach ($array_invoices as $invoice) {
            $parsed_invoice = $this->parseInvoice($invoice);
            dd($parsed_invoice);
            $this->sendInvoice($parsed_invoice);
        }
    }

    public function sendInvoice($parsed_invoice)
    {
        $this->client->request('POST', env('API_ENDPOINT'), [
            'multipart' => $parsed_invoice
        ]);
    }
}
