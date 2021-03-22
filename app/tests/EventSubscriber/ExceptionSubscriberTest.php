<?php

declare(strict_types=1);

namespace App\Tests\EventSubscriber;

use App\Dto\Request\Webhook;
use App\Entity\Message\Response;
use App\Event\ResponseMessageCreated;
use App\EventSubscriber\ExceptionSubscriber;
use App\Exception\Dto\Request\WebhookRequestException;
use App\Exception\TgAppExceptionInterface;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class ExceptionSubscriberTest extends TestCase
{
    /**
     * @throws TgAppExceptionInterface
     */
    public function testOnKernelTgProblemExceptionException(): void
    {
        $event = $this->creatWebhookRequestExceptionEvent();

        self::assertNull($event->getResponse());

        $webhook = new Webhook($event->getRequest());

        $logger = $this->createMock(LoggerInterface::class);
        $logger->expects(self::once())
            ->method('error')
            ->with(
                $event->getThrowable()->getMessage(),
                ['content' => json_decode($event->getRequest()->getContent(), true)]
            );

        $dispatcher = $this->createMock(EventDispatcherInterface::class);
        $dispatcher->expects(self::once())
            ->method('dispatch')
            ->with(
                new ResponseMessageCreated(
                    new Response(
                        $webhook->getMessageData()->getMessage()->getChat()->getId(),
                        'error'
                    )
                ),
                ResponseMessageCreated::NAME
            );

        $subscriber = new ExceptionSubscriber($logger, $dispatcher);
        $subscriber->onKernelException($event);

        self::assertEquals(200, $event->getResponse()->getStatusCode());
        self::assertEquals('"{}"', $event->getResponse()->getContent());
    }

    public function testOnKernelThrowable(): void
    {
        $event = $this->creatInvalidArgumentExceptionEvent();

        self::assertNull($event->getResponse());

        $logger = $this->createMock(LoggerInterface::class);
        $logger->expects(self::once())
            ->method('error')
            ->with(
                $event->getThrowable()->getMessage(),
                ['content' => json_decode($event->getRequest()->getContent(), true)]
            );
        $logger->expects(self::once())
            ->method('critical')
            ->with('[WEBHOOK-TOKEN] Expected the key "chat" to exist.');

        $dispatcher = $this->createMock(EventDispatcherInterface::class);
        $dispatcher->expects(self::never())
            ->method('dispatch');

        $subscriber = new ExceptionSubscriber($logger, $dispatcher);
        $subscriber->onKernelException($event);

        $response = $event->getResponse();

        self::assertEquals(200, $response->getStatusCode());
        self::assertEquals('"{}"', $response->getContent());
    }

    private function creatInvalidArgumentExceptionEvent(): ExceptionEvent
    {
        $kernel  = self::createMock(HttpKernelInterface::class);
        $request = self::createInvalidRequest();
        $e       = new InvalidArgumentException('test', 400);

        return new ExceptionEvent($kernel, $request, 1, $e);
    }

    private function creatWebhookRequestExceptionEvent(): ExceptionEvent
    {
        $kernel  = self::createMock(HttpKernelInterface::class);
        $request = self::createValidRequest();
        $e       = WebhookRequestException::wrongContent('test');

        return new ExceptionEvent($kernel, $request, 1, $e);
    }

    private static function createInvalidRequest(): Request
    {
        return new Request(
            [],
            [],
            [],
            [],
            [],
            [],
            file_get_contents(__DIR__ . '/../Webhook/data/invalid_bot_command_message.json')
        );
    }

    private static function createValidRequest(): Request
    {
        return new Request(
            [],
            [],
            [],
            [],
            [],
            [],
            file_get_contents(__DIR__ . '/../Webhook/data/valid_bot_command_message.json')
        );
    }
}
