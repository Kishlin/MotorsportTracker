<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportETL\Series\Application\ScrapSeriesList\TransformerSteps;

use Kishlin\Backend\MotorsportETL\Series\Application\ScrapSeriesList\TransformerStep;

final class SeriesNameEnforcer implements TransformerStep
{
    private const ID_FORMULA_ONE = 'a33f8b4a-2b22-41ce-8e7d-0aea08f0e176';

    private const ID_WEC = '967cd5ab-5562-40dc-a0b0-109738adcd01';

    public function transform(array $series): array
    {
        if (self::ID_FORMULA_ONE === $series['uuid']) {
            return [
                ...$series,
                'name' => 'Formula One',
            ];
        }

        if (self::ID_WEC === $series['uuid']) {
            return [
                ...$series,
                'name' => 'World Endurance Championship',
            ];
        }

        return $series;
    }
}
