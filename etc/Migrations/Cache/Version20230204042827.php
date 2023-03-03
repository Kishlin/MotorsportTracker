<?php

declare(strict_types=1);

namespace Kishlin\Migrations\Cache;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230204042827 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add the CalendarEventStepView table.';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE calendar_event_step_views (id VARCHAR(36) NOT NULL, championship_slug VARCHAR(255) NOT NULL, color VARCHAR(255) NOT NULL, icon VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, venue_label VARCHAR(255) NOT NULL, date_time TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX calendar_event_step_championship_datetime_idx ON calendar_event_step_views (championship_slug, date_time)');
        $this->addSql('COMMENT ON COLUMN calendar_event_step_views.date_time IS \'(DC2Type:calendar_event_step_view_date_time)\'');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE calendar_event_step_views');
    }
}
