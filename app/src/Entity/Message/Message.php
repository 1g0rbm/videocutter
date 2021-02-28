<?php

declare(strict_types=1);

namespace App\Entity\Message;

use DateTimeImmutable;

class Message
{
    private int $messageId;

    private DateTimeImmutable $date;

    private string $text;
}
