<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportETL\Season\Application\ScrapSeasons;

use Generator;
use Kishlin\Backend\MotorsportETL\Season\Domain\Season;
use Kishlin\Backend\MotorsportETL\Season\Domain\ValueObject\SeriesIdentity;
use Kishlin\Backend\MotorsportETL\Shared\Application\Transformer\JsonableStringTransformer;

final readonly class SeasonsTransformer
{
    public function __construct(
        private JsonableStringTransformer $jsonableStringParser,
    ) {}

    /**
     * @return Generator<Season>
     */
    public function transform(mixed $extractorResponse, SeriesIdentity $series): Generator
    {
        /** @var array<array{
         *      name: string,
         *      uuid: string,
         *      year: int,
         *      endYear: ?int,
         *      status: string
         *  }> $seasons
         */
        $seasons = $this->jsonableStringParser->transform($extractorResponse);

        foreach ($seasons as $season) {
            yield Season::fromData(
                $series,
                $season,
            );
        }
    }
}
