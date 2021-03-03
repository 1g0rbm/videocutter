<?php

declare(strict_types=1);

namespace App\Tests\EventSubscriber;

use App\Dto\Request\Webhook;
use App\Entity\Message\Data;
use App\EventSubscriber\WebhookEventSubscriber;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ControllerArgumentsEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\Validator\Exception\ValidationFailedException;
use Symfony\Component\Validator\Validation;
use function file_get_contents;
use function sprintf;

class WebhookEventSubscriberUnitTest extends TestCase
{
    public function testOnKernelControllerArgumentsSuccess(): void
    {
        $logger = $this->createMock(LoggerInterface::class);

        $subscriber = new WebhookEventSubscriber(Validation::createValidator(), $logger);

        $event = self::createEvent('valid');

        self::assertCount(0, $event->getArguments());

        $subscriber->onKernelControllerArguments($event);

        /** @var Webhook $webhook */
        $webhook = $event->getArguments()[0];

        self::assertCount(1, $event->getArguments());
        self::assertInstanceOf(Webhook::class, $webhook);
        self::assertInstanceOf(Data::class, $webhook->getData());
    }

    public function testOnKernelControllerArgumentsInvalidWebhook(): void
    {
        $logger = $this->createMock(LoggerInterface::class);

        $subscriber = new WebhookEventSubscriber(Validation::createValidator(), $logger);

        self::expectException(ValidationFailedException::class);

        $subscriber->onKernelControllerArguments(self::createEvent('invalid'));
    }

    private function createEvent(string $type): ControllerArgumentsEvent
    {
        $kernel  = $this->createMock(HttpKernelInterface::class);
        $request = new Request(
            [],
            [],
            [],
            [],
            [],
            [],
            file_get_contents(sprintf('%s/data/%s_bot_command_message.json', __DIR__, $type))
        );

        return new ControllerArgumentsEvent(
            $kernel,
            function () {
            },
            [],
            $request,
            null
        );
    }
}
