<?php

declare(strict_types=1);

namespace App\Tests\EventSubscriber;

use App\EventSubscriber\ExceptionSubscriber;
use App\Exception\Dto\Request\WebhookRequestException;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;

class ExceptionSubscriberTest extends TestCase
{
    public function testOnKernelTgProblemExceptionException(): void
    {
        $event = $this->creatWebhookRequestExceptionEvent();

        self::assertNull($event->getResponse());

        $logger = $this->createMock(LoggerInterface::class);

        $subscriber = new ExceptionSubscriber($logger);
        $subscriber->onKernelException($event);

        $response = $event->getResponse();

        self::assertEquals(400, $response->getStatusCode());
        self::assertEquals('"[WEBHOOK-TOKEN] test"', $response->getContent());
    }

    public function testOnKernelThrowable(): void
    {
        $event = $this->creatInvalidArgumentExceptionEvent();

        self::assertNull($event->getResponse());

        $logger = $this->createMock(LoggerInterface::class);

        $subscriber = new ExceptionSubscriber($logger);
        $subscriber->onKernelException($event);

        $response = $event->getResponse();

        self::assertEquals(500, $response->getStatusCode());
        self::assertEquals('"test"', $response->getContent());
    }

    private function creatInvalidArgumentExceptionEvent(): ExceptionEvent
    {
        $kernel  = self::createMock(HttpKernelInterface::class);
        $request = new Request();
        $e       = new InvalidArgumentException('test', 400);

        return new ExceptionEvent($kernel, $request, 1, $e);
    }

    private function creatWebhookRequestExceptionEvent(): ExceptionEvent
    {
        $kernel  = self::createMock(HttpKernelInterface::class);
        $request = new Request();
        $e       = WebhookRequestException::wrongContent('test');

        return new ExceptionEvent($kernel, $request, 1, $e);
    }
}
