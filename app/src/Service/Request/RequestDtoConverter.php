<?php

declare(strict_types=1);

namespace App\Service\Request;

use App\Dto\Request\RequestDtoInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class RequestDtoConverter
{
    private LoggerInterface $logger;

    private ValidatorInterface $validator;

    public function __construct(LoggerInterface $logger, ValidatorInterface $validator)
    {
        $this->logger    = $logger;
        $this->validator = $validator;
    }

    public function convert(Request $request, string $dtoClassName): RequestDtoInterface
    {
        $dto = new $dtoClassName($request);

        $this->logger->info(
            '[REQUEST-CONTENT]',
            ['content' => json_decode($request->getContent(), true)]
        );

        $errors = $this->validator->validate($dto);
        if ($errors->count() > 0) {
            throw new BadRequestHttpException((string)$errors, null, 400);
        }

        return $dto;
    }
}
