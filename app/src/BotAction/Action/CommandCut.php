<?php

declare(strict_types=1);

namespace App\BotAction\Action;

use App\BotAction\BotActionInterface;
use App\Dto\Bot\Action\CutDto;
use App\Entity\Message\Data;
use App\Entity\Message\Response;
use App\Exception\TgAppExceptionInterface;
use App\Service\ArgumentParserService;

class CommandCut implements BotActionInterface
{
    public const NAME = '/cut';

    private ArgumentParserService $argumentParser;

    public function __construct(ArgumentParserService $argumentParser)
    {
        $this->argumentParser = $argumentParser;
    }

    public function getCommand(): string
    {
        return self::NAME;
    }

    /**
     * @param Data $data
     *
     * @return Response
     * @throws TgAppExceptionInterface
     */
    public function run(Data $data): Response
    {
        /** @var CutDto $arguments */
        $arguments = $this->argumentParser->parse($data->getMessage(), get_class($this));

        return new Response(
            $data->getMessage()->getChat()->getId(),
            'cut command'
        );
    }
}
