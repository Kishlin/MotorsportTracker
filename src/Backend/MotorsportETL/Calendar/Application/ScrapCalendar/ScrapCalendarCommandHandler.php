<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportETL\Calendar\Application\ScrapCalendar;

use Kishlin\Backend\MotorsportETL\Shared\Application\Loader;
use Kishlin\Backend\MotorsportETL\Shared\Domain\CacheInvalidatorGateway;
use Kishlin\Backend\MotorsportETL\Shared\Domain\Context;
use Kishlin\Backend\MotorsportETL\Shared\Domain\SeasonGateway;
use Kishlin\Backend\Shared\Domain\Bus\Command\CommandHandler;
use Kishlin\Backend\Shared\Domain\Result\FailResult;
use Kishlin\Backend\Shared\Domain\Result\OkResult;
use Kishlin\Backend\Shared\Domain\Result\Result;

final readonly class ScrapCalendarCommandHandler implements CommandHandler
{
    public function __construct(
        private CacheInvalidatorGateway $cacheInvalidatorGateway,
        private CalendarTransformer $transformer,
        private CalendarExtractor $extractor,
        private SeasonGateway $seasonGateway,
        private Loader $loader,
    ) {
    }

    public function __invoke(ScrapCalendarCommand $command): Result
    {
        $season = $this->seasonGateway->find($command->seasonFilter());

        if (null === $season) {
            return FailResult::withCode(ScrapCalendarFailures::SEASON_NOT_FOUND);
        }

        if ($command->cacheMustBeInvalidated()) {
            $this->cacheInvalidatorGateway->invalidate(Context::CALENDAR->value, $season->ref());
        }

        $rawCalendar = $this->extractor->extract($season);
        $transformed = $this->transformer->transform($season, $rawCalendar);

        foreach ($transformed as $entity) {
            $this->loader->load($entity);
        }

        return OkResult::create();
    }
}
