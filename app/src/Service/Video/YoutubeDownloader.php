<?php

declare(strict_types=1);

namespace App\Service\Video;

use App\Exception\TgAppExceptionInterface;
use App\Exception\Video\DownloadException;
use YoutubeDl\Options;
use YoutubeDl\YoutubeDl;

class YoutubeDownloader
{
    private YoutubeDl $youtubeDl;

    private string $path;

    public function __construct(YoutubeDl $youtubeDl, string $path)
    {
        $this->youtubeDl = $youtubeDl;
        $this->path      = $path;
    }

    /**
     * @param string $link
     *
     * @return string
     * @throws TgAppExceptionInterface
     */
    public function download(string $link): string
    {
        $collection = $this->youtubeDl->download(
            Options::create()
                ->downloadPath($this->path)
                ->url($link)
        );

        if ($collection->count() !== 1) {
            throw DownloadException::invalidVideoNumber($collection->count());
        }

        [$video] = $collection->getVideos();

        if ($video->getError() !== null) {
            throw DownloadException::byYtError($video->getError());
        }

        return $video->getFile()->getPathname();
    }
}
