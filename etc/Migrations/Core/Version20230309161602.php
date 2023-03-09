<?php

declare(strict_types=1);

namespace Kishlin\Migrations\Core;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230309161602 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Allow events to duplicate names within a season.';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('DROP INDEX event_season_name_idx');
        $this->addSql('CREATE UNIQUE INDEX event_season_slug_idx ON events (season, slug)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP INDEX event_season_slug_idx');
        $this->addSql('CREATE UNIQUE INDEX event_season_name_idx ON events (season, name)');
    }
}
