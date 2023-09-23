<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportETL\Series\Application\ScrapSeriesList;

use Kishlin\Backend\MotorsportETL\Shared\Application\Loader\Loader;
use Kishlin\Backend\Shared\Domain\Bus\Command\CommandHandler;
use Kishlin\Backend\Shared\Domain\Result\OkResult;
use Kishlin\Backend\Shared\Domain\Result\Result;

final readonly class ScrapSeriesListCommandHandler implements CommandHandler
{
    public function __construct(
        private SeriesCacheInvalidator $seriesCacheInvalidator,
        private SeriesListTransformer $seriesTransformer,
        private SeriesListExtractor $seriesExtractor,
        private Loader $loader,
    ) {
    }

    public function __invoke(ScrapSeriesListCommand $command): Result
    {
        if ($command->cacheMustBeInvalidated()) {
            $this->seriesCacheInvalidator->invalidate();
        }

        $response = $this->seriesExtractor->extract();

        $seriesList = $this->seriesTransformer->transform($response);

        foreach ($seriesList as $series) {
            $this->loader->load($series);
        }

        return OkResult::create();
    }
}
