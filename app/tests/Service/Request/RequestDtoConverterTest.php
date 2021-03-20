<?php

declare(strict_types=1);

namespace App\Tests\Service\Request;

use App\Dto\Request\Webhook;
use App\Exception\TgAppExceptionInterface;
use App\Service\Request\RequestDtoConverter;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use function file_get_contents;
use function json_decode;

class RequestDtoConverterTest extends TestCase
{
    /**
     * @throws TgAppExceptionInterface
     */
    public function testConvertSuccess(): void
    {
        $request = Request::create(
            '/webhook',
            'GET',
            [],
            [],
            [],
            [],
            file_get_contents(__DIR__ . '/../../Webhook/data/valid_bot_command_with_arguments_message.json')
        );

        $logger = $this->createMock(LoggerInterface::class);
        $logger->expects(self::once())
            ->method('info')
            ->with(
                '[REQUEST-CONTENT]',
                ['content' => json_decode($request->getContent(), true)]
            );

        $validator = $this->createMock(ValidatorInterface::class);
        $validator->expects(self::once())
            ->method('validate')
            ->willReturn(new ConstraintViolationList());

        $service = new RequestDtoConverter($logger, $validator);

        self::assertEquals(new Webhook($request), $service->convert($request, Webhook::class));
    }

    public function testConvertException(): void
    {
        $request = Request::create(
            '/webhook',
            'GET',
            [],
            [],
            [],
            [],
            file_get_contents(__DIR__ . '/../../Webhook/data/valid_bot_command_with_text.json')
        );

        $logger = $this->createMock(LoggerInterface::class);
        $logger->expects(self::once())
            ->method('info')
            ->with(
                '[REQUEST-CONTENT]',
                ['content' => json_decode($request->getContent(), true)]
            );

        $violation = new ConstraintViolation(
            'test',
            null,
            [],
            null,
            null,
            null
        );

        $constraints = new ConstraintViolationList();
        $constraints->add($violation);

        $validator = $this->createMock(ValidatorInterface::class);
        $validator->expects(self::once())
            ->method('validate')
            ->willReturn($constraints);

        $this->expectException(BadRequestHttpException::class);

        (new RequestDtoConverter($logger, $validator))->convert($request, Webhook::class);
    }
}