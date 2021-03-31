<?php

declare(strict_types=1);

namespace App\Dto\Bot\Action;

class CutDto implements BotArgumentDtoInterface
{
    private string $url;

    private ?string $timeCodeStart;

    private ?string $timeCodeStop;

    public function __construct(string $url, ?string $timeCodeStart, ?string $timeCodeStop)
    {
        $this->url           = $url;
        $this->timeCodeStart = $timeCodeStart;
        $this->timeCodeStop  = $timeCodeStop;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function getTimeCodeStart(): string
    {
        return $this->timeCodeStart;
    }

    public function getTimeCodeStop(): string
    {
        return $this->timeCodeStop;
    }
}
