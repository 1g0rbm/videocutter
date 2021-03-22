<?php

declare(strict_types=1);

namespace App\Controller;

use App\Dto\Request\Webhook;
use App\Event\ResponseMessageCreated;
use App\Exception\TgAppExceptionInterface;
use App\Service\CommandParserService;
use App\Service\Registry\BotActionRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

/**
 * @Route("/webhook")
 */
class WebhookController extends AbstractController
{
    /**
     * @param Webhook                  $requestDto
     * @param CommandParserService     $commandParser
     * @param BotActionRegistry        $actionRegistry
     * @param EventDispatcherInterface $eventDispatcher
     *
     * @return JsonResponse
     *
     * @throws TgAppExceptionInterface
     * @Route("/token/{token}", name="webhook_token", methods={"POST"})
     */
    public function token(
        Webhook $requestDto,
        CommandParserService $commandParser,
        BotActionRegistry $actionRegistry,
        EventDispatcherInterface $eventDispatcher
    ): JsonResponse {
        $data = $requestDto->getMessageData();

        $botCommand = $commandParser->parse($data->getMessage());
        $action     = $actionRegistry->get($botCommand->getCommand());

        $eventDispatcher->dispatch(
            new ResponseMessageCreated($action->run($data)),
            ResponseMessageCreated::NAME
        );

        return new JsonResponse(['ok' => true]);
    }
}
