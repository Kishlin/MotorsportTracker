<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportETL\Championship\Application\ScrapSeriesList;

use Generator;
use Kishlin\Backend\MotorsportETL\Championship\Domain\Series;
use Kishlin\Backend\MotorsportETL\Shared\Application\Transformer\JsonableStringTransformer;
use Kishlin\Backend\Shared\Domain\Randomness\UuidGenerator;
use Kishlin\Backend\Shared\Domain\ValueObject\NullableStringValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\NullableUuidValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\StringValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;
use Symfony\Component\DependencyInjection\Attribute\TaggedIterator;

final readonly class SeriesListTransformer
{
    /**
     * @var TransformerStep[]
     */
    private iterable $steps;

    /**
     * @param TransformerStep[] $steps
     */
    public function __construct(
        #[TaggedIterator('kishlin.motorsport_etl.series.transformer_step')] iterable $steps,
        private JsonableStringTransformer $jsonableStringParser,
        private UuidGenerator $uuidGenerator,
    ) {
        $this->steps = $steps;
    }

    /**
     * @return Generator<Series>
     */
    public function transform(mixed $extractorResponse): Generator
    {
        /** @var array<array{
         *      name: string,
         *      uuid: string,
         *      shortName: ?string,
         *      shortCode: string,
         *      category: ?string,
         *  }> $seriesList
         */
        $seriesList = $this->jsonableStringParser->transform($extractorResponse);

        foreach ($seriesList as $series) {
            foreach ($this->steps as $step) {
                $series = $step->transform($series);
            }

            yield Series::create(
                new UuidValueObject($this->uuidGenerator->uuid4()),
                new StringValueObject($series['name']),
                new NullableStringValueObject($series['shortName']),
                new StringValueObject($series['shortCode']),
                new NullableUuidValueObject($series['uuid']),
            );
        }
    }
}