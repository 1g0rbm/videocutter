<?php

declare(strict_types=1);

namespace App\Controller;

use App\Dto\Request\Webhook;
use App\Exception\TgAppExceptionInterface;
use App\Service\CommandParserService;
use App\Service\Registry\BotActionRegistry;
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
     *
     * @return JsonResponse
     *
     * @throws TgAppExceptionInterface
     *
     * @Route("/token/{token}", name="webhook_token", methods={"POST"})
     */
    public function token(
        Webhook $requestDto,
        CommandParserService $commandParser,
        BotActionRegistry $actionRegistry
    ): JsonResponse {
        $data = $requestDto->getMessageData();

        $botCommand = $commandParser->parse($data->getMessage());

        $actionRegistry
            ->get($botCommand->getCommand())
            ->run($data);

        return new JsonResponse(['ok' => true]);
    }
}
