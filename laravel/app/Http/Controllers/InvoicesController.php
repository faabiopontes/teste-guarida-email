<?php

namespace App\Http\Controllers;

use Webklex\IMAP\Facades\Client;
use App\Http\Services\InvoicesService;
use App\Http\Services\ApiService;


class InvoicesController extends Controller
{
    function __construct(InvoicesService $service, ApiService $api_service)
    {
        $this->service = $service;
        $this->api_service = $api_service;
    }
    public function checkNew()
    {
        $invoices = $this->service->getArrayNewInvoices();
        $this->api_service->sendArrayInvoices($invoices);
    }
}
