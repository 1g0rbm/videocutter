<?php

declare(strict_types=1);

namespace App\Tests\EventSubscriber;

use App\EventSubscriber\WebhookEventSubscriber;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ControllerArgumentsEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\Validator\Validation;

class WebhookEventSubscriberUnitTest extends TestCase
{
    public function testOnKernelControllerArgumentsSuccess(): void
    {
        $logger = $this->createMock(LoggerInterface::class);

        $subscriber = new WebhookEventSubscriber(Validation::createValidator(), $logger);

        $event = self::createEvent();

        self::assertCount(0, $event->getArguments());

        $subscriber->onKernelControllerArguments($event);

        self::assertCount(1, $event->getArguments());
    }

    private function createEvent(): ControllerArgumentsEvent
    {
        $kernel  = $this->createMock(HttpKernelInterface::class);
        $request = new Request(
            [],
            [],
            [],
            [],
            [],
            [],
            file_get_contents(__DIR__ . '/data/bot_command_message.json')
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
