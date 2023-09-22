<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportETL\Series\Application\ScrapSeriesList\TransformerSteps;

use Kishlin\Backend\MotorsportETL\Series\Application\ScrapSeriesList\TransformerStep;

final class MessedUpSeriesFixer implements TransformerStep
{
    private const ID_F1_ESPORTS_VIRTUAL_GRAND_PRIX = 'd7412896-fb6d-4a91-a928-b2ec0d54352a';

    public function transform(array $series): array
    {
        if (self::ID_F1_ESPORTS_VIRTUAL_GRAND_PRIX === $series['uuid']) {
            return [
                ...$series,
                'shortName' => 'eF1',
            ];
        }

        return $series;
    }
}
