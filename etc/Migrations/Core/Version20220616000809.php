<?php /** @noinspection PhpUnused */

declare(strict_types=1);

namespace Kishlin\Migrations\Core;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20220616000809 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create the tables for the Event aggregate root.';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE events (id VARCHAR(36) NOT NULL, season VARCHAR(36) NOT NULL, venue VARCHAR(36) NOT NULL, index INT NOT NULL, label VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX event_season_label_idx ON events (season, label)');
        $this->addSql('ALTER TABLE events ADD CONSTRAINT fk_events_venue FOREIGN KEY(venue) REFERENCES venues(id)');
        $this->addSql('ALTER TABLE events ADD CONSTRAINT fk_events_season FOREIGN KEY(season) REFERENCES seasons(id)');

        $this->addSql('CREATE TABLE step_types (id VARCHAR(36) NOT NULL, label VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX step_type_label_idx ON step_types (label)');

        $this->addSql('CREATE TABLE event_steps (id VARCHAR(36) NOT NULL, event VARCHAR(36) NOT NULL, type VARCHAR(36) NOT NULL, date_time TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX event_step_event_type_idx ON event_steps (event, type)');
        $this->addSql('ALTER TABLE event_steps ADD CONSTRAINT fk_event_steps_event FOREIGN KEY(event) REFERENCES events(id)');
        $this->addSql('ALTER TABLE event_steps ADD CONSTRAINT fk_event_steps_type FOREIGN KEY(type) REFERENCES step_types(id)');
        $this->addSql('COMMENT ON COLUMN event_steps.date_time IS \'(DC2Type:event_step_date_time)\'');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE event_steps');
        $this->addSql('DROP TABLE events');
        $this->addSql('DROP TABLE step_types');
    }
}
