<?php

declare(strict_types=1);

namespace App\Entity\Message;

use function json_encode;

class Response
{
    private int $chatId;

    private string $text;

    private string $parseMode = 'markdown';

    private array $replyMarkup = [];

    public function __construct(int $chatId, string $text)
    {
        $this->chatId = $chatId;
        $this->text   = $text;
    }

    public function getChatId(): int
    {
        return $this->chatId;
    }

    public function getText(): string
    {
        return $this->text;
    }

    public function toArray(): array
    {
        return [
            'chat_id' => $this->chatId,
            'text' => $this->text,
            'parse_mode' => $this->parseMode,
            'reply_markup' => json_encode($this->replyMarkup),
        ];
    }
}
