<?php

namespace App\Http\Services;

use Webklex\IMAP\Facades\Client;

class InvoicesService
{
    function __construct()
    {
        $this->client = Client::account('default');
        $this->client->connect();
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
}
