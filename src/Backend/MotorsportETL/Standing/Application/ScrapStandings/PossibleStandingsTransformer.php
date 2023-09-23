<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportETL\Standing\Application\ScrapStandings;

use Kishlin\Backend\MotorsportETL\Shared\Application\Transformer\JsonableStringTransformer;
use Kishlin\Backend\MotorsportETL\Standing\Domain\DTO\PossibleStandingList;

final readonly class PossibleStandingsTransformer
{
    public function __construct(
        private JsonableStringTransformer $jsonableStringParser,
    ) {
    }

    public function transform(mixed $extractorResponse): PossibleStandingList
    {
        /**
         * @var array{
         *     driverStandings: array<int, null|array{name: string, uuid: string}>,
         *     teamStandings: array<int, null|array{name: string, uuid: string}>,
         *     constructorStandings: array<int, null|array{name: string, uuid: string}>,
         * } $content
         */
        $content = $this->jsonableStringParser->transform($extractorResponse);

        return PossibleStandingList::forStandingsList(
            $content['constructorStandings'],
            $content['driverStandings'],
            $content['teamStandings'],
        );
    }
}
