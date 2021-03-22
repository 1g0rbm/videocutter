<?php

declare(strict_types=1);

namespace App\EventSubscriber;

use App\Event\ResponseMessageCreated;
use App\Service\TgBot\Api;
use GuzzleHttp\Exception\GuzzleException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class TgMessageCreatedSubscriber implements EventSubscriberInterface
{
    private Api $api;

    public function __construct(Api $api)
    {
        $this->api = $api;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            'tg.response.created' => 'onTgResponseCreated',
        ];
    }

    /**
     * @param ResponseMessageCreated $event
     *
     * @throws GuzzleException
     */
    public function onTgResponseCreated(ResponseMessageCreated $event): void
    {
        $this->api->sendMessage($event->getResponse());
    }
}
