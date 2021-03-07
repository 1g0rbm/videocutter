<?php

declare(strict_types=1);

namespace App\EventSubscriber;

use App\Exception\TgAppExceptionInterface;
use App\Exception\TgWebhookUnauthorizedAbstractException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\Routing\RouterInterface;

class WebhookVerifyTokenSubscriber implements EventSubscriberInterface
{
    const ROUTE_NAME = 'webhook_token';

    private RouterInterface $router;

    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            RequestEvent::class => 'onKernelRequest',
        ];
    }

    /**
     * @param RequestEvent $event
     *
     * @throws TgAppExceptionInterface
     */
    public function onKernelRequest(RequestEvent $event): void
    {
        $request = $event->getRequest();

        $parameters = $this->router->match($request->getPathInfo());

        $route = $parameters['_route'] ?? null;
        if ($route === null || $route !== self::ROUTE_NAME) {
            return;
        }

        $token = $parameters['token'] ?? null;
        if ($token === null) {
            throw TgWebhookUnauthorizedAbstractException::tokenNotFound();
        }

        $originalToken = getenv('TG_WEBHOOK_TOKEN');
        if ($token !== $originalToken) {
            throw TgWebhookUnauthorizedAbstractException::wrongToken($token);
        }
    }
}
