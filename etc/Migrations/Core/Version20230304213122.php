<?php

declare(strict_types=1);

namespace Kishlin\Migrations\Core;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230304213122 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add some data to the event table.';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE events DROP CONSTRAINT fk_events_season');
        $this->addSql('ALTER TABLE events DROP CONSTRAINT fk_events_venue');
        $this->addSql('DROP INDEX event_season_label_idx');
        $this->addSql('ALTER TABLE events ADD slug VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE events ADD name VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE events ADD short_name VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE events ADD start_date TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL');
        $this->addSql('ALTER TABLE events ADD end_date TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL');
        $this->addSql('ALTER TABLE events DROP label');
        $this->addSql('COMMENT ON COLUMN events.start_date IS \'(DC2Type:nullable_date_time_value_object)\'');
        $this->addSql('COMMENT ON COLUMN events.end_date IS \'(DC2Type:nullable_date_time_value_object)\'');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_5387574A989D9B62 ON events (slug)');
        $this->addSql('CREATE UNIQUE INDEX event_season_name_idx ON events (season, name)');
        $this->addSql('CREATE UNIQUE INDEX event_season_index_idx ON events (season, index)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP INDEX UNIQ_5387574A989D9B62');
        $this->addSql('DROP INDEX event_season_name_idx');
        $this->addSql('DROP INDEX event_season_index_idx');
        $this->addSql('ALTER TABLE events ADD label VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE events DROP slug');
        $this->addSql('ALTER TABLE events DROP name');
        $this->addSql('ALTER TABLE events DROP short_name');
        $this->addSql('ALTER TABLE events DROP start_date');
        $this->addSql('ALTER TABLE events DROP end_date');
        $this->addSql('ALTER TABLE events ADD CONSTRAINT fk_events_season FOREIGN KEY (season) REFERENCES seasons (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE events ADD CONSTRAINT fk_events_venue FOREIGN KEY (venue) REFERENCES venues (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX event_season_label_idx ON events (season, label)');
        $this->addSql('CREATE INDEX IDX_5387574AF0E45BA9 ON events (season)');
        $this->addSql('CREATE INDEX IDX_5387574A91911B0D ON events (venue)');
    }
}
