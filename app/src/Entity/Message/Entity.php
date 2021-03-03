<?php

declare(strict_types=1);

namespace App\Entity\Message;

class Entity
{
    private int $offset;

    private int $length;

    private string $type;

    public static function createFromArray(array $arr): self
    {
        $obj = new self();

        $obj->offset = $arr['offset'];
        $obj->length = $arr['length'];
        $obj->type   = $arr['type'];

        return $obj;
    }

    public function getOffset(): int
    {
        return $this->offset;
    }

    public function setOffset(int $offset): void
    {
        $this->offset = $offset;
    }

    public function getLength(): int
    {
        return $this->length;
    }

    public function setLength(int $length): void
    {
        $this->length = $length;
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