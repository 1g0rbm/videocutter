<?php

declare(strict_types=1);

namespace App\Controller;

use App\Dto\Request\Webhook;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/webhook")
 */
class WebhookController extends AbstractController
{
    /**
     * @Route("/token/{token}", name="webhook_token", methods={"POST"})
     * @param Webhook $requestDto
     *
     * @return JsonResponse
     */
    public function token(Webhook $requestDto): JsonResponse
    {
        $data = $requestDto->getMessageData();


        return new JsonResponse(['ok' => true]);
    }
}
