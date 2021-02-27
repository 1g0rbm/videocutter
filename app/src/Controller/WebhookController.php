<?php

declare(strict_types=1);

namespace App\Controller;

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
    private LoggerInterface $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @Route("/token", name="webhook_token", methods={"GET", "POST"})
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function token(Request $request): JsonResponse
    {
        $this->logger->info('[TG-WEBHOOK]: ', ['request' => $request->getContent()]);

        return new JsonResponse(['ok' => true]);
    }
}
