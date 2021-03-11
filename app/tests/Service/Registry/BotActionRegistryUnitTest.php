<?php

declare(strict_types=1);

namespace App\Tests\Service\Registry;

use App\Exception\BotAction\ActionNotFoundException;
use App\Exception\BotAction\BotActionRegistryException;
use App\Exception\TgAppExceptionInterface;
use App\Service\Registry\BotActionRegistry;
use PHPUnit\Framework\TestCase;
use ReflectionObject;

class BotActionRegistryUnitTest extends TestCase
{
    /**
     * @throws TgAppExceptionInterface
     */
    public function testAddActionSuccess(): void
    {
        $registry = new BotActionRegistry();

        self::assertEmpty(self::getPrivatePropertyOfRegistry($registry));

        $action = new TestAction();
        $registry->addAction($action);

        self::assertContainsEquals($action, self::getPrivatePropertyOfRegistry($registry));
    }

    /**
     * @throws TgAppExceptionInterface
     */
    public function testAddActionThrowAlreadyExistException(): void
    {
        $registry = new BotActionRegistry();

        $action = new TestAction();
        $registry->addAction($action);

        self::expectException(BotActionRegistryException::class);
        self::expectExceptionCode(500);
        self::expectExceptionMessage('[ACTION_REGISTRY] Registry already contain action "/test"');

        $registry->addAction($action);
    }

    /**
     * @throws TgAppExceptionInterface
     */
    public function testGetSuccess(): void
    {
        $registry = new BotActionRegistry();

        $action = new TestAction();
        $registry->addAction($action);

        self::assertEquals($action, $registry->get($action->getCommand()));
    }

    /**
     * @throws TgAppExceptionInterface
     */
    public function testGetDefaultActionSuccess(): void
    {
        $registry = new BotActionRegistry();

        $defaultAction = new TestDefaultAction();
        $registry->addAction($defaultAction);

        $action = new TestAction();
        $registry->addAction($action);

        self::assertEquals($defaultAction, $registry->get('/non-existent'));
    }

    /**
     * @throws TgAppExceptionInterface
     */
    public function testGetThrowNotFoundException(): void
    {
        $registry = new BotActionRegistry();

        self::expectException(ActionNotFoundException::class);
        self::expectExceptionCode(500);
        self::expectExceptionMessage('[ACTION-REGISTRY] Registry can not find default action');

        $registry->get('/undefined_command');
    }

    private static function getPrivatePropertyOfRegistry(BotActionRegistry $registry): array
    {
        $reflection = new ReflectionObject($registry);
        $property   = $reflection->getProperty('actions');
        $property->setAccessible(true);

        return $property->getValue($registry);
    }
}
