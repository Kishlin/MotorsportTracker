<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportETL\Standing\Infrastructure\Extractor;

use Kishlin\Backend\MotorsportETL\Shared\Application\Connector;
use Kishlin\Backend\MotorsportETL\Shared\Domain\ValueObject\SeasonIdentity;
use Kishlin\Backend\MotorsportETL\Standing\Application\ScrapStandings\StandingsExtractor;
use Kishlin\Backend\MotorsportETL\Standing\Domain\DTO\PossibleStandingClass;
use Kishlin\Backend\MotorsportETL\Standing\Domain\StandingType;

final readonly class StandingsExtractorUsingConnector implements StandingsExtractor
{
    private const URL = 'https://api.motorsportstats.com/widgets/1.0.0/seasons/%s/standings/';

    private const CLASS_PARAM = '?seriesClassUuid=%s';

    public function __construct(
        private Connector $connector,
    ) {
    }

    public function extract(
        SeasonIdentity $season,
        StandingType $standingTypes,
        ?PossibleStandingClass $possibleStandingClass = null,
    ): string {
        $url = self::URL . $standingTypes->value;

        if (null !== $possibleStandingClass) {
            $url .= self::CLASS_PARAM;

            return $this->connector->fetch($url, [$season->ref(), $possibleStandingClass->uuid()]);
        }

        return $this->connector->fetch($url, [$season->ref()]);
    }
}
