<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\Analytics\Application\UpdateDriverAnalyticsCache;

use Kishlin\Backend\MotorsportCache\Analytics\Domain\Entity\SeasonDriverAnalytics;
use Kishlin\Backend\MotorsportCache\Analytics\Domain\ValueObject\DriverAnalyticsView;
use Kishlin\Backend\Shared\Domain\Bus\Command\CommandHandler;
use Kishlin\Backend\Shared\Domain\Cache\CachePersister;
use Kishlin\Backend\Tools\Helpers\StringHelper;

final readonly class UpdateDriverAnalyticsCacheCommandHandler implements CommandHandler
{
    public function __construct(
        private DriverAnalyticsForSeasonGateway $gateway,
        private CachePersister $cachePersister,
    ) {
    }

    public function __invoke(UpdateDriverAnalyticsCacheCommand $command): void
    {
        $analytics = $this->gateway->find($command->championship(), $command->year())->analytics();

        $seasonDriverAnalytics = SeasonDriverAnalytics::create(
            DriverAnalyticsView::with($analytics),
        );

        $keyData = [
            'championship' => StringHelper::slugify($command->championship()),
            'year'         => $command->year(),
        ];

        $this->cachePersister->save($seasonDriverAnalytics, $keyData);
    }
}
