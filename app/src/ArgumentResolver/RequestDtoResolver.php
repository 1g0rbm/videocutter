<?php

declare(strict_types=1);

namespace App\ArgumentResolver;

use App\Dto\Request\RequestDtoInterface;
use App\Service\Request\RequestDtoConverter;
use Psr\Log\LoggerInterface;
use ReflectionClass;
use ReflectionException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class RequestDtoResolver implements ArgumentValueResolverInterface
{
    private RequestDtoConverter $converter;

    public function __construct(RequestDtoConverter $converter)
    {
        $this->converter = $converter;
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
        yield $this->converter->convert($request, $argument->getType());;
    }
}
