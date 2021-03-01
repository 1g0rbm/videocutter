<?php

declare(strict_types=1);

namespace App\EventSubscriber;

use App\Dto\Request\Webhook;
use Symfony\Component\Validator\Constraints as Assert;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ControllerArgumentsEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Validator\Exception\ValidationFailedException;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use function json_decode;

class WebhookEventSubscriber implements EventSubscriberInterface
{
    private LoggerInterface $logger;

    private ValidatorInterface $validator;

    public function __construct(ValidatorInterface $validator, LoggerInterface $logger)
    {
        $this->logger    = $logger;
        $this->validator = $validator;
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

        $constraint = new Assert\Collection(
            [
                'data' => new Assert\Collection(
                    [
                        'update_id' => new Assert\Type('integer'),
                        'message' => new Assert\Type('array'),
                    ]
                ),
            ]
        );

        $errors = $this->validator->validate($data, [$constraint]);
        if ($errors->count() > 0) {
            throw new ValidationFailedException($data, $errors);
        }

        $this->logger->info('[TG-WEBHOOK] request data', ['data' => $data]);

        $event->setArguments([...$event->getArguments(), new Webhook($data)]);
    }
}
