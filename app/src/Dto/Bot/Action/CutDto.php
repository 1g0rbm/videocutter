<?php

declare(strict_types=1);

namespace App\Dto\Bot\Action;

use Webmozart\Assert\Assert;

class CutDto implements BotArgumentDtoInterface
{
    public static function createFromArray(array $arguments): BotArgumentDtoInterface
    {
        Assert::count($arguments, 3);
        Assert::string($url = $arguments[0]);
        Assert::string($timeCodeStart = $arguments[1]);
        Assert::string($timeCodeStop = $arguments[2]);

        $obj = new self();

        $obj->url           = $url;
        $obj->timeCodeStart = $timeCodeStart;
        $obj->timeCodeStop  = $timeCodeStop;

        return $obj;
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
