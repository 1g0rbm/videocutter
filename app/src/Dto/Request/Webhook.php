<?php

declare(strict_types=1);

namespace App\Dto\Request;

use App\Entity\Message\Data;
use App\Exception\Dto\Request\WebhookRequestException;
use App\Exception\TgAppExceptionInterface;
use Exception;
use InvalidArgumentException;
use Symfony\Component\HttpFoundation\Request;
use function json_decode;

class Webhook implements RequestDtoInterface
{
    private Data $data;

    /**
     * @param Request $request
     *
     * @throws Exception|TgAppExceptionInterface
     */
    public function __construct(Request $request)
    {
        try {
            $this->data = Data::createFromArray(json_decode($request->getContent(), true));
        } catch (InvalidArgumentException $e) {
            throw WebhookRequestException::wrongContent($e->getMessage());
        }
    }

    public function getMessageData(): Data
    {
        return $this->data;
    }
}
