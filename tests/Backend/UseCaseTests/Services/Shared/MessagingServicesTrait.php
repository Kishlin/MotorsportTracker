<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\Services\Shared;

use Kishlin\Tests\Backend\UseCaseTests\TestDoubles\Shared\Messaging\TestCommandBus;
use Kishlin\Tests\Backend\UseCaseTests\TestDoubles\Shared\Messaging\TestEventDispatcher;
use Kishlin\Tests\Backend\UseCaseTests\TestDoubles\Shared\Messaging\TestQueryBus;
use Kishlin\Tests\Backend\UseCaseTests\TestServiceContainer;

trait MessagingServicesTrait
{
    private ?TestCommandBus $testCommandBus = null;

    private ?TestEventDispatcher $testEventDispatcher = null;

    private ?TestQueryBus $testQueryBus = null;

    abstract public function serviceContainer(): TestServiceContainer;

    public function commandBus(): TestCommandBus
    {
        if (null === $this->testCommandBus) {
            $this->testCommandBus = new TestCommandBus($this->serviceContainer());
        }

        return $this->testCommandBus;
    }

    public function eventDispatcher(): TestEventDispatcher
    {
        if (null === $this->testEventDispatcher) {
            $this->testEventDispatcher = new TestEventDispatcher();
        }

        return $this->testEventDispatcher;
    }

    public function queryBus(): TestQueryBus
    {
        if (null === $this->testQueryBus) {
            $this->testQueryBus = new TestQueryBus();
        }

        return $this->testQueryBus;
    }
}
