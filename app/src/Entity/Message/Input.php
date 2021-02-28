<?php

declare(strict_types=1);

namespace App\Entity\Message;

class Input
{
    private int $updateId;

    private Message $message;

    public function __construct(int $updateId, Message $message)
    {
        $this->updateId = $updateId;
        $this->message  = $message;
    }

    public function getMessage(): Message
    {
        return $this->message;
    }

    public function getUpdateId(): int
    {
        return $this->updateId;
    }
}
