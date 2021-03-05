<?php

declare(strict_types=1);

namespace App\Entity\Message;

use Webmozart\Assert\Assert;

class From
{
    private int $id;

    private bool $isBot = false;

    private string $firstName;

    private string $lastName;

    private string $username;

    private string $languageCode;

    public static function createFromArray(array $from): self
    {
        Assert::keyExists($from, 'id');
        Assert::keyExists($from, 'is_bot');
        Assert::keyExists($from, 'first_name');
        Assert::keyExists($from, 'last_name');
        Assert::keyExists($from, 'username');
        Assert::keyExists($from, 'language_code');

        $obj = new self();

        $obj->id           = $from['id'];
        $obj->isBot        = (bool)$from['is_bot'];
        $obj->firstName    = $from['first_name'];
        $obj->lastName     = $from['last_name'];
        $obj->username     = $from['username'];
        $obj->languageCode = $from['language_code'];

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

    public function isBot(): bool
    {
        return $this->isBot;
    }

    public function setIsBot(bool $isBot): void
    {
        $this->isBot = $isBot;
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

    public function getLanguageCode(): string
    {
        return $this->languageCode;
    }

    public function setLanguageCode(string $languageCode): void
    {
        $this->languageCode = $languageCode;
    }
}
