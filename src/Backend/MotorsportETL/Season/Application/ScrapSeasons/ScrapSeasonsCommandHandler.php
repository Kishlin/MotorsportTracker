<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportETL\Season\Application\ScrapSeasons;

use Kishlin\Backend\MotorsportETL\Shared\Application\Loader\Loader;
use Kishlin\Backend\Shared\Domain\Bus\Command\CommandHandler;
use Kishlin\Backend\Shared\Domain\Result\FailResult;
use Kishlin\Backend\Shared\Domain\Result\OkResult;
use Kishlin\Backend\Shared\Domain\Result\Result;

final readonly class ScrapSeasonsCommandHandler implements CommandHandler
{
    public function __construct(
        private SeasonsCacheInvalidator $seasonsCacheInvalidator,
        private SeasonsTransformer $seriesTransformer,
        private SeasonsExtractor $seriesExtractor,
        private SeriesGateway $seriesGateway,
        private Loader $loader,
    ) {
    }

    public function __invoke(ScrapSeasonsCommand $command): Result
    {
        $seriesDTO = $this->seriesGateway->find($command->seriesName());

        if (null === $seriesDTO) {
            return FailResult::withCode(ScrapSeasonsFailures::SERIES_NOT_FOUND);
        }

        if ($command->cacheMustBeInvalidated()) {
            $this->seasonsCacheInvalidator->invalidate($seriesDTO);
        }

        $response = $this->seriesExtractor->extract($seriesDTO);

        $seriesList = $this->seriesTransformer->transform($response, $seriesDTO);

        foreach ($seriesList as $series) {
            $this->loader->load($series);
        }

        return OkResult::create();
    }
}
