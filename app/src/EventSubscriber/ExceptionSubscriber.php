<?php

declare(strict_types=1);

namespace App\EventSubscriber;

use App\Dto\Request\Webhook;
use App\Entity\Message\Response;
use App\Event\ResponseMessageCreated;
use App\Exception\Dto\Request\WebhookRequestException;
use App\Exception\TgAppExceptionInterface;
use App\Exception\TgWebhookUnauthorizedException;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;
use Throwable;

class ExceptionSubscriber implements EventSubscriberInterface
{
    private LoggerInterface $logger;

    private EventDispatcherInterface $eventDispatcher;

    public function __construct(
        LoggerInterface $logger,
        EventDispatcherInterface $eventDispatcher
    ) {
        $this->logger          = $logger;
        $this->eventDispatcher = $eventDispatcher;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::EXCEPTION => [
                ['onKernelException', 10],
                ['onUnauthorizedException', 20],
                ['onWebhookRequestException', 30],
            ],
        ];
    }

    public function onWebhookRequestException(ExceptionEvent $event)
    {
        if (!$event->getThrowable() instanceof WebhookRequestException) {
            return;
        }

        $this->logger->critical(
            $event->getThrowable()->getMessage(),
            ['content' => json_decode($event->getRequest()->getContent(), true)]
        );

        $response = new JsonResponse();
        $response->setStatusCode(400);
        $response->setContent('{"a": 1}');

        $event->allowCustomResponseCode();
        $event->setResponse($response);
    }

    public function onUnauthorizedException(ExceptionEvent $event): void
    {
        if (!$event->getThrowable() instanceof TgWebhookUnauthorizedException) {
            return;
        }

        $this->logger->error(
            $event->getThrowable()->getMessage(),
            ['content' => json_decode($event->getRequest()->getContent(), true)]
        );

        $event->setResponse(new JsonResponse('{}', 401));
    }

    public function onKernelException(ExceptionEvent $event): void
    {
        $e = $event->getThrowable();

        $this->logger->error(
            $e->getMessage(),
            ['content' => json_decode($event->getRequest()->getContent(), true)]
        );

        try {
            $dto = self::createWebhookDto($event);

            $response = new Response(
                $dto->getMessageData()->getMessage()->getChat()->getId(),
                'error'
            );

            $this->eventDispatcher->dispatch(
                new ResponseMessageCreated($response),
                ResponseMessageCreated::NAME
            );
        } catch (Throwable $e) {
            $this->logger->critical($e->getMessage());
            $event->setResponse(new JsonResponse('{}', 200));
        } finally {
            $event->setResponse(new JsonResponse('{}', 200));
        }
        $event->allowCustomResponseCode();
    }

    /**
     * @param ExceptionEvent $event
     *
     * @return Webhook
     * @throws TgAppExceptionInterface
     */
    private static function createWebhookDto(ExceptionEvent $event): Webhook
    {
        return new Webhook($event->getRequest());
    }
}
