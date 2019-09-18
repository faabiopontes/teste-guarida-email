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
        try {
            $messages =  $this->service->getMessagesSinceYesterday();

            foreach ($messages as $message) {
                $attachments = $message->getAttachments();
                $hasAttachment = $attachments->count() > 0;
                $hasTextBody = $message->hasTextBody();

                // SE UMA DAS DUAS VERIFICAÇÕES FOR FALSA NÃO ENVIAR A MENSAGEM

                $arrayAttachments = [];
                $arrayMessage = [
                    'name' => null,
                    'address' => null,
                    'value' => null,
                    'due' => null,
                ];

                $textBody = $message->getTextBody();
                $linesBody = explode("\r\n", $textBody);
                foreach ($linesBody as $line) {
                    if (strpos($line, 'Nome:') !== false) {
                        $arrayMessage['name'] = trim(substr($line, 5));
                    }
                    if (strpos($line, 'Endereço:') !== false) {
                        $arrayMessage['address'] = trim(substr($line, 10));
                    }
                    if (strpos($line, 'Valor:') !== false) {
                        $arrayMessage['value'] = trim(substr($line, 6));
                    }
                    if (strpos($line, 'Vencimento:') !== false) {
                        $arrayMessage['due'] = trim(substr($line, 11));
                    }
                }
                dd($arrayMessage, $linesBody);

                foreach ($attachments as $attachment) {
                    $arrayAttachments[] = [
                        'filename' => $attachment->getName(),
                        'content' => $attachment->getContent(),
                        'contentType' => $attachment->getContentType(),
                    ];
                }
            }

            // $message->setFlag('Seen');
            // $message->moveToFolder('Notas Fiscais');
        } catch (\Exception $e) { }
    }
}
