<?php

declare(strict_types=1);

namespace App\Exception\Video;

use App\Exception\TgAppExceptionInterface;
use App\Exception\TgProblemAbstractException;

class DownloadException extends TgProblemAbstractException
{
    public static function invalidVideoNumber(int $count): TgAppExceptionInterface
    {
        return self::create('download-video', "Expect 1 video, {$count} found", 200);
    }

    public static function byYtError(string $message): TgAppExceptionInterface
    {
        return self::create('download-video', $message, 200);
    }
}
