<?php

declare(strict_types=1);

namespace App\Controller;

use App\Controller\Dto\Webhook;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/webhook")
 */
class WebhookController extends AbstractController
{
    /**
     * @Route("/token", name="webhook_token", methods={"GET", "POST"})
     * @param Request         $request
     * @param LoggerInterface $logger
     * @param Webhook|null    $requestDto
     *
     * @return JsonResponse
     */
    public function token(
        Request $request,
        LoggerInterface $logger,
        Webhook $requestDto
    ): JsonResponse {
        return new JsonResponse(['ok' => true]);
    }
}
