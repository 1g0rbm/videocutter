<?php

declare(strict_types=1);

namespace App\BotAction\Action;

use App\BotAction\BotActionInterface;
use App\Dto\Bot\Action\CutDto;
use App\Entity\Message\Data;
use App\Entity\Message\Response;
use App\Exception\TgAppExceptionInterface;
use App\Service\ArgumentParserService;
use App\Service\Video\YoutubeDownloader;
use ReflectionException;

class CommandCut implements BotActionInterface
{
    public const NAME = '/cut';

    private ArgumentParserService $argumentParser;

    private YoutubeDownloader $downloader;

    public function __construct(ArgumentParserService $argumentParser, YoutubeDownloader $downloader)
    {
        $this->argumentParser = $argumentParser;
        $this->downloader     = $downloader;
    }

    public function getCommand(): string
    {
        return self::NAME;
    }

    /**
     * @param Data $data
     *
     * @return Response
     *
     * @throws TgAppExceptionInterface
     * @throws ReflectionException
     */
    public function run(Data $data): Response
    {
        /** @var CutDto $cutDto */
        $cutDto = $this->argumentParser->parse($data->getMessage(), get_class($this));

        $this->downloader->download($cutDto->getUrl());

        return new Response(
            $data->getMessage()->getChat()->getId(),
            'cut command'
        );
    }
}
