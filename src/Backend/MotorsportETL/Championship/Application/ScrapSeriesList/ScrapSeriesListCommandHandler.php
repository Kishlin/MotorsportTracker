<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportETL\Championship\Application\ScrapSeriesList;

use Kishlin\Backend\MotorsportETL\Shared\Application\Loader;
use Kishlin\Backend\Shared\Domain\Bus\Command\CommandHandler;

final readonly class ScrapSeriesListCommandHandler implements CommandHandler
{
    public function __construct(
        private SeriesListExtractor $seriesExtractor,
        private SeriesListTransformer $seriesTransformer,
        private Loader $loader,
    ) {
    }

    public function __invoke(ScrapSeriesListCommand $command): void
    {
        $response = $this->seriesExtractor->extract();

        $seriesList = $this->seriesTransformer->transform($response);

        foreach ($seriesList as $series) {
            $this->loader->load($series);
        }
    }
}
