<?php

namespace App\Http\Services;

use Webklex\IMAP\Facades\Client;

class InvoicesService
{
    function __construct()
    {
        try {
            $this->client = Client::account('default');
            $this->client->connect();
            $this->client->createFolder('Notas Fiscais Enviadas');
        } catch (\Exception $e) {
            return [
                'error' => true,
                'exception' => $e
            ];
        }
    }

    public function getMessagesSinceYesterday()
    {
        $folder = $this->client->getFolder('INBOX');
        $messages = $folder
            ->messages()
            ->since(now()->subDays(2))
            ->unseen()
            ->leaveUnread()
            ->get();
        return $messages;
    }

    public function getDefaultInvoiceAttributes()
    {
        return [
            [
                'key' => 'name',
                'pattern' => 'Nome',
                'value' => null,
            ],
            [
                'key' => 'address',
                'pattern' => 'Endereço',
                'value' => null,
            ],
            [
                'key' => 'amount',
                'pattern' => 'Valor',
                'value' => null,
            ],
            [
                'key' => 'competence',
                'pattern' => 'Vencimento',
                'value' => null,
            ]
        ];
    }

    public function isValidMessage($message)
    {
        $attachments = $message->getAttachments();
        $hasAttachment = $attachments->count() > 0;
        $hasTextBody = $message->hasTextBody();
        return $hasAttachment && $hasTextBody;
    }

    public function getArrayNewInvoices()
    {
        try {
            $messages = $this->getMessagesSinceYesterday();
            $array_messages = [];

            foreach ($messages as $message) {
                if (!$this->isValidMessage($message)) continue;

                $array_message_attributes = $this->getDefaultInvoiceAttributes();
                $array_messag_attributes['message'] = $message;

                $text_body = $message->getTextBody();
                $lines_body = explode("\r\n", $text_body);
                foreach ($lines_body as $line) {
                    foreach ($array_message_attributes as &$index_message) {
                        // A verificação de valor nulo é para pegar a primeira referencia a pattern apenas
                        if (strpos($line, $index_message['pattern'] . ':') !== false && is_null($index_message['value'])) {
                            $pattern_length = strlen($index_message['pattern']) + 1; // 1 for the :
                            $index_message['value'] = trim(substr($line, $pattern_length));
                        }
                    }
                }

                $attachments = $message->getAttachments();
                foreach ($attachments as $attachment) {
                    $array_message_attributes[] = [
                        'key' => 'attachment',
                        'filename' => $attachment->getName(),
                        'contents' => $attachment->getContent(),
                    ];
                }

                $array_messages[] = $array_message_attributes;
            }

            return $array_messages;
        } catch (\Exception $e) {
            return [
                'error' => true,
                'message' => 'Não foi possível conectar em sua conta de e-mail. As variaveis de ambiente estão atualizadas?',
                'exception' => $e
            ];
        }
    }
}
