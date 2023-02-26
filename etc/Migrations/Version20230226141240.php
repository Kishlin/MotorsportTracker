<?php

declare(strict_types=1);

namespace Kishlin\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230226141240 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add the Driver and Team standings views.';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE driver_standings_views (id VARCHAR(36) NOT NULL, championship_slug VARCHAR(255) NOT NULL, year INT NOT NULL, events TEXT NOT NULL, standings JSON NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX driver_standings_views_championship_year_idx ON driver_standings_views (championship_slug, year)');
        $this->addSql('COMMENT ON COLUMN driver_standings_views.events IS \'(DC2Type:standings_view_events)\'');
        $this->addSql('CREATE TABLE team_standings_views (id VARCHAR(36) NOT NULL, championship_slug VARCHAR(255) NOT NULL, year INT NOT NULL, events TEXT NOT NULL, standings JSON NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX team_standings_views_championship_year_idx ON team_standings_views (championship_slug, year)');
        $this->addSql('COMMENT ON COLUMN team_standings_views.events IS \'(DC2Type:standings_view_events)\'');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP TABLE driver_standings_views');
        $this->addSql('DROP TABLE team_standings_views');
    }
}
