<?php

declare(strict_types=1);

namespace App\Entity\Message;

class Chat
{
    private int $id;

    private string $firstName;

    private string $lastName;

    private string $username;

    private string $type;

    public static function createFromArray(array $chat): self
    {
        $obj = new self();

        $obj->id        = $chat['id'];
        $obj->firstName = $chat['first_name'];
        $obj->lastName  = $chat['last_name'];
        $obj->username  = $chat['username'];
        $obj->type      = $chat['type'];

        return $obj;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): void
    {
        $this->firstName = $firstName;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): void
    {
        $this->lastName = $lastName;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function setUsername(string $username): void
    {
        $this->username = $username;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): void
    {
        $this->type = $type;
    }
}
