<?php

declare(strict_types=1);

namespace Kishlin\Migrations\Cache;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230218154951 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add the reference column to the CalendarEventStepView table.';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE calendar_event_step_views ADD reference VARCHAR(36) NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX calendar_event_step_reference_idx ON calendar_event_step_views (reference)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP INDEX calendar_event_step_reference_idx');
        $this->addSql('ALTER TABLE calendar_event_step_views DROP reference');
    }
}
