<?php

declare(strict_types=1);

namespace Migrations\Core;

use Kishlin\Backend\Persistence\Migration\Migration;

final class Version202323090424 implements Migration
{
    public function up(): string
    {
        return <<<'SQL'
CREATE UNIQUE INDEX uniq_analytics_teams_season_team_position ON public.analytics_teams USING btree (season, team, position);

DROP INDEX uniq_analytics_teams_season_team;
SQL;
    }

    public function down(): string
    {
        return <<<'SQL'
CREATE UNIQUE INDEX uniq_analytics_teams_season_team ON public.analytics_teams USING btree (season, team);

DROP INDEX uniq_analytics_teams_season_team_position;
SQL;
    }
}
