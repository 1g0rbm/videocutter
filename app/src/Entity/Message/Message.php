<?php

declare(strict_types=1);

namespace App\Entity\Message;

use DateTimeImmutable;
use Exception;

class Message
{
    private int $messageId;

    private From $from;

    private Chat $chat;

    private DateTimeImmutable $date;

    private string $text;

    /**
     * @var Entity[]
     */
    private array $entities = [];

    /**
     * @param array $arr
     *
     * @return static
     * @throws Exception
     */
    public static function createFromArray(array $arr): self
    {
        $obj = new self();

        $obj->messageId = $arr['message_id'];

        $obj->from     = From::createFromArray($arr['from']);
        $obj->chat     = Chat::createFromArray($arr['chat']);
        $obj->date     = (new DateTimeImmutable())->setTimestamp($arr['date']);
        $obj->text     = $arr['text'];
        $obj->entities = array_map(
            static fn(array $arr): Entity => Entity::createFromArray($arr),
            $arr['entities']
        );

        return $obj;
    }

    public function getMessageId(): int
    {
        return $this->messageId;
    }

    public function setMessageId(int $messageId): void
    {
        $this->messageId = $messageId;
    }

    public function getFrom(): From
    {
        return $this->from;
    }

    public function setFrom(From $from): void
    {
        $this->from = $from;
    }

    public function getChat(): Chat
    {
        return $this->chat;
    }

    public function setChat(Chat $chat): void
    {
        $this->chat = $chat;
    }

    public function getDate(): DateTimeImmutable
    {
        return $this->date;
    }

    public function setDate(DateTimeImmutable $date): void
    {
        $this->date = $date;
    }

    public function getText(): string
    {
        return $this->text;
    }

    public function setText(string $text): void
    {
        $this->text = $text;
    }

    /**
     * @return Entity[]
     */
    public function getEntities(): array
    {
        return $this->entities;
    }

    /**
     * @param array $entities
     */
    public function setEntities(array $entities): void
    {
        $this->entities = $entities;
    }

    public function addEntity(Entity $entity): void
    {
        $this->entities[] = $entity;
    }
}
