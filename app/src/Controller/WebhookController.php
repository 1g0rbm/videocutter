<?php

declare(strict_types=1);

namespace App\Controller;

use App\Dto\Request\Webhook;
use App\Exception\TgAppExceptionInterface;
use App\Service\CommandParserService;
use App\Service\Registry\BotActionRegistry;
use App\Service\TgBot\Api;
use GuzzleHttp\Exception\GuzzleException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/webhook")
 */
class WebhookController extends AbstractController
{
    /**
     * @param Webhook              $requestDto
     * @param CommandParserService $commandParser
     * @param BotActionRegistry    $actionRegistry
     * @param Api                  $tgApi
     *
     * @return JsonResponse
     *
     * @throws TgAppExceptionInterface
     * @throws GuzzleException
     *
     * @Route("/token/{token}", name="webhook_token", methods={"POST"})
     */
    public function token(
        Webhook $requestDto,
        CommandParserService $commandParser,
        BotActionRegistry $actionRegistry,
        Api $tgApi
    ): JsonResponse {
        $data = $requestDto->getMessageData();

        $botCommand = $commandParser->parse($data->getMessage());

        $response = $actionRegistry
            ->get($botCommand->getCommand())
            ->run($data);

        $tgApi->sendMessage($response);

        return new JsonResponse(['ok' => true]);
    }
}
