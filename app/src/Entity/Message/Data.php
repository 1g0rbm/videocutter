<?php

declare(strict_types=1);

namespace App\Entity\Message;

class Data
{
    private int $updateId;

    private Message $message;

    public static function createFromArray(array $arr): self
    {
        $obj = new self();

        $obj->updateId = $arr['update_id'];
        $obj->message  = Message::createFromArray($arr['message']);

        return $obj;
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
