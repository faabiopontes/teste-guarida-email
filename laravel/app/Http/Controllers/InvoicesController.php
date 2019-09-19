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
        if (array_key_exists('error', $invoices)) {
            return $this->responseError($invoices);
        }

        $response = $this->api_service->sendArrayInvoices($invoices);
        if (array_key_exists('error', $response)) {
            return $this->responseError($response);
        }

        return $this->responseSuccess($response);
    }

    public function responseSuccess($data)
    {
        $total = $data['success'] + $data['failed'];
        $message['total'] = $total;
        $data['message'] = 'Notas Fiscais Verificadas! Total: ' . $total . ', Sucesso: ' . $data['success'] . ', Falha: ' . $data['failed'];
        if ($data['success'] == 0 && $data['failed'] > 0) {
            $data['message'] .= '. Você já verificou a variável de ambiente correspondente ao endpoint da API?';
        }
        return response()->json($data);
    }

    public function responseError($data)
    {
        if (array_key_exists('exception', $data)) {
            \Log::error($data['exception']->getMessage());
            unset($data['exception']);
        }
        if (!array_key_exists('message', $data)) {
            $data['message'] = 'Ocorreu um erro ao realizar a operação desejada!';
        }

        return response()->json(
            $data,
            500
        );
    }
}
