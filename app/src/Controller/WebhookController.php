<?php

declare(strict_types=1);

namespace App\Controller;

use App\Dto\Request\Webhook;
use App\Entity\Message\Data;
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
     * @Route("/token/{token}", name="webhook_token", methods={"POST"})
     * @param Request         $request
     * @param Webhook         $requestDto
     *
     * @return JsonResponse
     */
    public function token(Request $request, Webhook $requestDto): JsonResponse
    {
        return new JsonResponse(['ok' => true]);
    }
}
