<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\Services\MotorsportETL;

use Kishlin\Backend\MotorsportETL\Shared\Application\Connector;
use Kishlin\Backend\MotorsportETL\Shared\Application\Loader\Loader;
use Kishlin\Backend\MotorsportETL\Shared\Application\Loader\LoaderUsingGateways;
use Kishlin\Backend\MotorsportETL\Shared\Application\Transformer\JsonableStringTransformer;
use Kishlin\Backend\MotorsportETL\Shared\Domain\CacheInvalidatorGateway;
use Kishlin\Backend\MotorsportETL\Shared\Infrastructure\CachedConnector\ContextFinder;
use Kishlin\Backend\Shared\Application\Service\Parser\Json\JsonableStringParser;
use Kishlin\Backend\Shared\Domain\Bus\Event\EventDispatcher;
use Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportETL\Shared\CachableConnectorSpy;
use Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportETL\Shared\EntityStoreSpy;

trait SharedServicesTrait
{
    private ?Loader $loader = null;

    private ?EntityStoreSpy $entityStoreSpy = null;

    private ?CachableConnectorSpy $cachableConnectorSpy = null;

    private ?JsonableStringTransformer $jsonableStringTransformer = null;

    abstract public function eventDispatcher(): EventDispatcher;

    public function loader(): Loader
    {
        if (null === $this->loader) {
            $this->loader = new LoaderUsingGateways(
                $this->entityStoreSpy(),
                $this->entityStoreSpy(),
            );
        }

        return $this->loader;
    }

    public function entityStoreSpy(): EntityStoreSpy
    {
        if (null === $this->entityStoreSpy) {
            $this->entityStoreSpy = new EntityStoreSpy();
        }

        return $this->entityStoreSpy;
    }

    public function cachableConnectorSpy(): CachableConnectorSpy
    {
        if (null === $this->cachableConnectorSpy) {
            $this->cachableConnectorSpy = new CachableConnectorSpy(
                new ContextFinder(),
            );
        }

        return $this->cachableConnectorSpy;
    }

    public function connector(): Connector
    {
        return $this->cachableConnectorSpy();
    }

    public function cacheInvalidatorGateway(): CacheInvalidatorGateway
    {
        return $this->cachableConnectorSpy();
    }

    public function jsonableStringTransformer(): JsonableStringTransformer
    {
        if (null === $this->jsonableStringTransformer) {
            $this->jsonableStringTransformer = new JsonableStringTransformer(
                new JsonableStringParser(),
                $this->eventDispatcher(),
            );
        }

        return $this->jsonableStringTransformer;
    }
}
