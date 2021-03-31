<?php

declare(strict_types=1);

namespace App\Tests\Service\Video;

use App\Exception\TgAppExceptionInterface;
use App\Exception\Video\DownloadException;
use App\Service\Video\YoutubeDownloader;
use PHPUnit\Framework\TestCase;
use SplFileInfo;
use YoutubeDl\Entity\Video;
use YoutubeDl\Entity\VideoCollection;
use YoutubeDl\Options;
use YoutubeDl\YoutubeDl;

class YoutubeDownloaderTest extends TestCase
{
    /**
     * @throws TgAppExceptionInterface
     */
    public function testDownloaderSuccess(): void
    {
        $path = '/test/path';
        $link = 'http://test';

        $video = self::createMock(Video::class);
        $video->expects(self::once())
            ->method('getError')
            ->willReturn(null);
        $video->expects(self::once())
            ->method('getFile')
            ->willReturn(new SplFileInfo($path));

        $collection = new VideoCollection([$video]);

        $dl = self::createMock(YoutubeDl::class);
        $dl->expects($this->once())
            ->method('download')
            ->with(
                Options::create()
                    ->downloadPath($path)
                    ->url($link)
            )->willReturn($collection);

        $service = new YoutubeDownloader($dl, $path);

        self::assertSame($path, $service->download($link));
    }

    /**
     * @throws TgAppExceptionInterface
     */
    public function testThrowDownloadExceptionIfThereAreNoVideo(): void
    {
        $path = '/test/path';
        $link = 'http://test';

        $collection = new VideoCollection();

        $dl = self::createMock(YoutubeDl::class);
        $dl->expects($this->once())
            ->method('download')
            ->with(
                Options::create()
                    ->downloadPath($path)
                    ->url($link)
            )->willReturn($collection);

        $service = new YoutubeDownloader($dl, $path);

        $this->expectException(DownloadException::class);
        $this->expectExceptionMessage('[DOWNLOAD-VIDEO] Expect 1 video, 0 found');

        $service->download($link);
    }

    /**
     * @throws TgAppExceptionInterface
     */
    public function testThrowDownloadExceptionIfThereIsVideoWithError(): void
    {
        $path = '/test/path';
        $link = 'http://test';

        $video = self::createMock(Video::class);
        $video->expects(self::any())
            ->method('getError')
            ->willReturn('error');

        $collection = new VideoCollection();

        $dl = self::createMock(YoutubeDl::class);
        $dl->expects($this->once())
            ->method('download')
            ->with(
                Options::create()
                    ->downloadPath($path)
                    ->url($link)
            )->willReturn($collection);

        $service = new YoutubeDownloader($dl, $path);

        $this->expectException(DownloadException::class);
        $this->expectExceptionMessage('[DOWNLOAD-VIDEO] Expect 1 video, 0 found');

        $service->download($link);
    }
}
