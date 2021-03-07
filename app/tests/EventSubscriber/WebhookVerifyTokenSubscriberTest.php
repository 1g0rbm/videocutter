<?php

declare(strict_types=1);

namespace App\Tests\EventSubscriber;

use App\EventSubscriber\WebhookVerifyTokenSubscriber;
use App\Exception\TgAppExceptionInterface;
use App\Exception\TgWebhookUnauthorizedAbstractException;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\Routing\RouterInterface;

class WebhookVerifyTokenSubscriberTest extends TestCase
{
    /**
     * @throws TgAppExceptionInterface
     */
    public function testOnKernelRequestSuccess(): void
    {
        $event = $this->createEvent();

        $router = $this->createMock(RouterInterface::class);
        $router->expects(self::once())
            ->method('match')
            ->with($event->getRequest()->getPathInfo())
            ->willReturn(
                [
                    '_route' => 'webhook_token',
                    '_controller' => 'App\Controller\WebhookController::token',
                    'token' => getenv('TG_WEBHOOK_TOKEN'),
                ]
            );


        (new WebhookVerifyTokenSubscriber($router))->onKernelRequest($event);
    }

    /**
     * @throws TgAppExceptionInterface
     */
    public function testOnKernelRequestThrowWrongTokenException(): void
    {
        $event = $this->createEvent();

        $router = $this->createMock(RouterInterface::class);
        $router->expects(self::once())
            ->method('match')
            ->with($event->getRequest()->getPathInfo())
            ->willReturn(
                [
                    '_route' => 'webhook_token',
                    '_controller' => 'App\Controller\WebhookController::token',
                    'token' => 'wrong_token',
                ]
            );

        self::expectException(TgWebhookUnauthorizedAbstractException::class);
        self::expectExceptionCode(401);
        self::expectExceptionMessage('[AUTH] Send wrong token wrong_token');

        (new WebhookVerifyTokenSubscriber($router))->onKernelRequest($event);
    }

    /**
     * @throws TgAppExceptionInterface
     */
    public function testOnKernelRequestThrowTokenNotFoundException(): void
    {
        $event = $this->createEvent();

        $router = $this->createMock(RouterInterface::class);
        $router->expects(self::once())
            ->method('match')
            ->with($event->getRequest()->getPathInfo())
            ->willReturn(
                [
                    '_route' => 'webhook_token',
                    '_controller' => 'App\Controller\WebhookController::token',
                ]
            );

        self::expectException(TgWebhookUnauthorizedAbstractException::class);
        self::expectExceptionCode(401);
        self::expectExceptionMessage('[AUTH] Token not found in webhook');

        (new WebhookVerifyTokenSubscriber($router))->onKernelRequest($event);
    }

    private function createEvent(): RequestEvent
    {
        $kernel  = self::createMock(HttpKernelInterface::class);
        $request = Request::create(
            '/webhook/token',
            'POST',
            ['token' => getenv('TG_WEBHOOK_TOKEN')]
        );

        return new RequestEvent($kernel, $request, 1);
    }
}
