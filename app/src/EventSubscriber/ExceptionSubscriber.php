<?php

declare(strict_types=1);

namespace App\EventSubscriber;

use App\Exception\TgAppExceptionInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Throwable;

class ExceptionSubscriber implements EventSubscriberInterface
{
    private LoggerInterface $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::EXCEPTION => 'onKernelException',
        ];
    }

    public function onKernelException(ExceptionEvent $event): void
    {
        $e = $event->getThrowable();

        $this->logger->error(
            $e->getMessage(),
            ['content' => json_decode($event->getRequest()->getContent(), true)]
        );

        $response = self::createResponseFromThrowable($e);
        $response->headers->set('Content-Type', 'application/problem+json');

        $event->setResponse($response);
    }

    private static function createResponseFromThrowable(Throwable $e): JsonResponse
    {

        if ($e instanceof TgAppExceptionInterface) {
            return self::createResponseFromTgProblem($e);
        }

        return new JsonResponse($e->getMessage(), 500);
    }


    private static function createResponseFromTgProblem(TgAppExceptionInterface $e): JsonResponse
    {
        return new JsonResponse(
            $e->getTelegramProblem()->getMessage(),
            $e->getTelegramProblem()->getHttpStatusCode()
        );
    }
}
