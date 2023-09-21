<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportETL\Championship\Application\ScrapSeriesList;

use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

#[AutoconfigureTag('kishlin.motorsport_etl.series.transformer_step')]
interface TransformerStep
{
    /**
     * @param array{
     *      name: string,
     *      uuid: string,
     *      shortName: ?string,
     *      shortCode: string,
     *      category: ?string,
     *  } $series
     *
     * @return array{
     *      name: string,
     *      uuid: string,
     *      shortName: ?string,
     *      shortCode: string,
     *      category: ?string,
     *  }
     */
    public function transform(array $series): array;
}
