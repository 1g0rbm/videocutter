<?php

declare(strict_types=1);

namespace App\Dto\Bot\Action;

use Webmozart\Assert\Assert;

class CutDto implements BotArgumentDtoInterface
{
    public function __construct(array $arguments)
    {
        Assert::count($arguments, 3);
        Assert::string($url = $arguments[0]);
        Assert::string($timeCodeStart = $arguments[1]);
        Assert::string($timeCodeStop = $arguments[2]);

        $this->url           = $url;
        $this->timeCodeStart = $timeCodeStart;
        $this->timeCodeStop  = $timeCodeStop;
    }

    private string $url;

    private string $timeCodeStart;

    private string $timeCodeStop;

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
