<?php

declare(strict_types=1);

namespace App\EventSubscriber;

use App\Controller\Dto\WebhookRequestDto;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ControllerArgumentsEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use function json_decode;

class WebhookEventSubscriber implements EventSubscriberInterface
{
    private LoggerInterface $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::CONTROLLER_ARGUMENTS => [
                ['onKernelControllerArguments', 0],
            ],
        ];
    }

    public function onKernelControllerArguments(ControllerArgumentsEvent $event): void
    {
        $data = json_decode($event->getRequest()->getContent(), true);
        $this->logger->info('[TG-WEBHOOK] request data', ['data' => $data]);
        $event->setArguments([...$event->getArguments(), new WebhookRequestDto()]);
    }
}
