<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\Services\MotorsportETL;

use Kishlin\Backend\MotorsportETL\Calendar\Application\ScrapCalendar\CalendarExtractor;
use Kishlin\Backend\MotorsportETL\Calendar\Application\ScrapCalendar\CalendarTransformer;
use Kishlin\Backend\MotorsportETL\Calendar\Application\ScrapCalendar\ScrapCalendarCommandHandler;
use Kishlin\Backend\MotorsportETL\Calendar\Infrastructure\CalendarExtractorUsingConnector;
use Kishlin\Backend\MotorsportETL\Shared\Application\Connector;
use Kishlin\Backend\MotorsportETL\Shared\Application\Loader\Loader;
use Kishlin\Backend\MotorsportETL\Shared\Application\Transformer\JsonableStringTransformer;
use Kishlin\Backend\MotorsportETL\Shared\Domain\CacheInvalidatorGateway;
use Kishlin\Backend\MotorsportETL\Shared\Domain\SeasonGateway;
use Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportETL\Shared\EntityStoreSpy;
use Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportETL\Shared\SeasonGatewayStub;

trait CalendarServicesTrait
{
    private ?SeasonGateway $seasonGateway = null;

    private ?CalendarExtractor $calendarExtractor = null;

    private ?ScrapCalendarCommandHandler $scrapCalendarCommandHandler = null;

    abstract public function loader(): Loader;

    abstract public function connector(): Connector;

    abstract public function entityStoreSpy(): EntityStoreSpy;

    abstract public function cacheInvalidatorGateway(): CacheInvalidatorGateway;

    abstract public function jsonableStringTransformer(): JsonableStringTransformer;

    public function seasonGateway(): SeasonGateway
    {
        if (null === $this->seasonGateway) {
            $this->seasonGateway = new SeasonGatewayStub(
                $this->entityStoreSpy(),
            );
        }

        return $this->seasonGateway;
    }

    public function calendarExtractor(): CalendarExtractor
    {
        if (null === $this->calendarExtractor) {
            $this->calendarExtractor = new CalendarExtractorUsingConnector(
                $this->connector(),
            );
        }

        return $this->calendarExtractor;
    }

    public function scrapCalendarCommandHandler(): ScrapCalendarCommandHandler
    {
        if (null === $this->scrapCalendarCommandHandler) {
            $transformer = new CalendarTransformer(
                $this->jsonableStringTransformer(),
            );

            $this->scrapCalendarCommandHandler = new ScrapCalendarCommandHandler(
                $this->cacheInvalidatorGateway(),
                $transformer,
                $this->calendarExtractor(),
                $this->seasonGateway(),
                $this->loader(),
            );
        }

        return $this->scrapCalendarCommandHandler;
    }
}
