<?php

declare(strict_types=1);

namespace App\Event;

use App\Entity\Message\Response;
use Symfony\Contracts\EventDispatcher\Event;

class ResponseMessageCreated extends Event
{
    public const NAME = 'tg.response.created';

    private Response $response;

    public function __construct(Response $response)
    {
        $this->response = $response;
    }

    public function getResponse(): Response
    {
        return $this->response;
    }
}
