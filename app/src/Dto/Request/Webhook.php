<?php

declare(strict_types=1);

namespace App\Dto\Request;

use App\Entity\Message\Chat;
use App\Entity\Message\Data;
use App\Entity\Message\From;
use App\Entity\Message\Message;

class Webhook
{
    private Data $data;

    public function __construct(Data $data)
    {
        $this->data = $data;
    }

    public function getData(): Data
    {
        return $this->data;
    }

    public function getMessage(): Message
    {
        return $this->data->getMessage();
    }

    public function getFrom(): From
    {
        return $this->data->getMessage()->getFrom();
    }

    public function getChat(): Chat
    {
        return $this->data->getMessage()->getChat();
    }
}
