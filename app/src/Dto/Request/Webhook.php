<?php

declare(strict_types=1);

namespace App\Dto\Request;

use App\Entity\Message\Data;
use Exception;
use Symfony\Component\HttpFoundation\Request;
use function json_decode;

class Webhook implements RequestDtoInterface
{
    private Data $data;

    /**
     * @param Request $request
     *
     * @throws Exception
     */
    public function __construct(Request $request)
    {
        $this->data = Data::createFromArray(json_decode($request->getContent(), true));
    }
}
