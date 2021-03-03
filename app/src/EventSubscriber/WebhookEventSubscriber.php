<?php

declare(strict_types=1);

namespace App\EventSubscriber;

use App\Dto\Request\Webhook;
use App\Entity\Message\Data;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ControllerArgumentsEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Validator\Constraints as Assert;
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

        $errors = $this->validator->validate($data, [self::getConstraints()]);
        if ($errors->count() > 0) {
            throw new ValidationFailedException($data, $errors);
        }

        $this->logger->info('[TG-WEBHOOK] request data: ', ['data' => $data]);

        $event->setArguments(
            [
                ...$event->getArguments(),
                new Webhook(Data::createFromArray($data['data'])),
            ]
        );
    }

    private static function getConstraints(): Assert\Collection
    {
        return new Assert\Collection(
            [
                'data' => new Assert\Collection(
                    [
                        'update_id' => new Assert\Type('integer'),
                        'message' => new Assert\Collection(
                            [
                                'message_id' => new Assert\Type('integer'),
                                'from' => new Assert\Collection(
                                    [
                                        'id' => new Assert\Type('int'),
                                        'is_bot' => new Assert\Type('boolean'),
                                        'first_name' => new Assert\Type('string'),
                                        'last_name' => new Assert\Type('string'),
                                        'username' => new Assert\Type('string'),
                                        'language_code' => new Assert\Type('string'),
                                    ]
                                ),
                                'chat' => new Assert\Collection(
                                    [
                                        'id' => new Assert\Type('int'),
                                        'first_name' => new Assert\Type('string'),
                                        'last_name' => new Assert\Type('string'),
                                        'username' => new Assert\Type('string'),
                                        'type' => new Assert\Type('string'),
                                    ]
                                ),
                                'date' => new Assert\Type('int'),
                                'text' => new Assert\Type('string'),
                                'entities' => new Assert\Collection(
                                    [
                                        new Assert\Collection(
                                            [
                                                'offset' => new Assert\Type('int'),
                                                'length' => new Assert\Type('int'),
                                                'type' => new Assert\Type('string'),
                                            ]
                                        ),
                                    ]
                                ),
                            ]
                        ),
                    ]
                ),
            ]
        );
    }
}
