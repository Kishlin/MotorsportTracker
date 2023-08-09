<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\Analytics\Application\UpdateTeamAnalyticsCache;

use Kishlin\Backend\MotorsportCache\Analytics\Domain\Entity\SeasonAnalytics;
use Kishlin\Backend\MotorsportCache\Analytics\Domain\Enum\AnalyticsType;
use Kishlin\Backend\MotorsportCache\Analytics\Domain\ValueObject\AnalyticsView;
use Kishlin\Backend\Shared\Domain\Bus\Command\CommandHandler;
use Kishlin\Backend\Shared\Domain\Cache\CachePersister;
use Kishlin\Backend\Tools\Helpers\StringHelper;

final readonly class UpdateTeamAnalyticsCacheCommandHandler implements CommandHandler
{
    public function __construct(
        private TeamAnalyticsForSeasonGateway $gateway,
        private CachePersister $cachePersister,
    ) {
    }

    public function __invoke(UpdateTeamAnalyticsCacheCommand $command): void
    {
        $analytics = $this->gateway->find($command->championship(), $command->year())->analytics();

        $seasonTeamAnalytics = SeasonAnalytics::create(
            AnalyticsView::with($analytics),
        );

        $keyData = [
            'type'         => AnalyticsType::TEAMS->toString(),
            'championship' => StringHelper::slugify($command->championship()),
            'year'         => $command->year(),
        ];

        $this->cachePersister->save($seasonTeamAnalytics, $keyData);
    }
}
