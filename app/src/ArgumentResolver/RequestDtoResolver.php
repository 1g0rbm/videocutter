<?php

declare(strict_types=1);

namespace App\ArgumentResolver;

use App\Dto\Request\RequestDtoInterface;
use Psr\Log\LoggerInterface;
use ReflectionClass;
use ReflectionException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use function json_encode;

class RequestDtoResolver implements ArgumentValueResolverInterface
{
    private LoggerInterface $logger;

    private ValidatorInterface $validator;

    public function __construct(LoggerInterface $logger, ValidatorInterface $validator)
    {
        $this->logger    = $logger;
        $this->validator = $validator;
    }

    /**
     * @param Request          $request
     * @param ArgumentMetadata $argument
     *
     * @return bool
     * @throws ReflectionException
     */
    public function supports(Request $request, ArgumentMetadata $argument): bool
    {
        $reflection = new ReflectionClass($argument->getType());
        if ($reflection->implementsInterface(RequestDtoInterface::class)) {
            return true;
        }

        return false;
    }

    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        $class = $argument->getType();
        $dto   = new $class($request);

        $this->logger->info(
            '[REQUEST-CONTENT]',
            ['content' => json_decode($request->getContent(), true)]
        );

        $errors = $this->validator->validate($dto);
        if ($errors->count() > 0) {
            throw new BadRequestHttpException((string)$errors, null, 400);
        }

        yield $dto;
    }
}
