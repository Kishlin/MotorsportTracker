<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportETL\Calendar\Infrastructure;

use Kishlin\Backend\MotorsportETL\Calendar\Application\ScrapCalendar\CalendarExtractor;
use Kishlin\Backend\MotorsportETL\Shared\Application\Connector;
use Kishlin\Backend\MotorsportETL\Shared\Domain\ValueObject\SeasonIdentity;

final readonly class CalendarExtractorUsingConnector implements CalendarExtractor
{
    private const URL = 'https://api.motorsportstats.com/widgets/1.0.0/seasons/%s/calendar';

    public function __construct(
        private Connector $connector,
    ) {
    }

    public function extract(SeasonIdentity $season): string
    {
        return $this->connector->fetch(
            self::URL,
            [$season->ref()],
        );
    }
}
