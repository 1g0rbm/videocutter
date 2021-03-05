<?php

declare(strict_types=1);

namespace App\Entity\Message;

use Exception;
use Webmozart\Assert\Assert;

class Data
{
    private int $updateId;

    private Message $message;

    /**
     * @param array $arr
     *
     * @return static
     * @throws Exception
     */
    public static function createFromArray(array $arr): self
    {
        Assert::keyExists($arr, 'update_id');
        Assert::keyExists($arr, 'message');

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
