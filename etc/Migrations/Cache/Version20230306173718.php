<?php

declare(strict_types=1);

namespace Kishlin\Migrations\Cache;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230306173718 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add the table for Calendar Events.';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE calendar_events (id VARCHAR(36) NOT NULL, slug VARCHAR(255) NOT NULL, index INT NOT NULL, name VARCHAR(255) NOT NULL, short_name VARCHAR(255) DEFAULT NULL, start_date TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, end_date TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, series TEXT NOT NULL, venue JSON NOT NULL, sessions JSON NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_F9E14F16989D9B62 ON calendar_events (slug)');
        $this->addSql('COMMENT ON COLUMN calendar_events.start_date IS \'(DC2Type:nullable_date_time_value_object)\'');
        $this->addSql('COMMENT ON COLUMN calendar_events.end_date IS \'(DC2Type:nullable_date_time_value_object)\'');
        $this->addSql('COMMENT ON COLUMN calendar_events.series IS \'(DC2Type:calendar_event_series)\'');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE calendar_events');
    }
}
