<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportETL\Standing\Infrastructure\Extractor;

use Kishlin\Backend\MotorsportETL\Shared\Application\Connector;
use Kishlin\Backend\MotorsportETL\Shared\Domain\ValueObject\SeasonIdentity;
use Kishlin\Backend\MotorsportETL\Standing\Application\ScrapStandings\PossibleStandingsExtractor;

final readonly class PossibleStandingsExtractorUsingConnector implements PossibleStandingsExtractor
{
    private const URL = 'https://api.motorsportstats.com/widgets/1.0.0/seasons/%s/standings';

    public function __construct(
        private Connector $connector,
    ) {}

    public function extract(SeasonIdentity $season): string
    {
        return $this->connector->fetch(
            self::URL,
            [$season->ref()],
        );
    }
}
