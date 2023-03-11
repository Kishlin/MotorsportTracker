<?php

declare(strict_types=1);

namespace Kishlin\Migrations\Core;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230311160030 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE championship_presentations (id VARCHAR(36) NOT NULL, championship VARCHAR(36) NOT NULL, icon VARCHAR(255) NOT NULL, color VARCHAR(255) NOT NULL, created_on TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX championship_created_on_idx ON championship_presentations (championship, created_on)');
        $this->addSql('COMMENT ON COLUMN championship_presentations.created_on IS \'(DC2Type:date_time_value_object)\'');
        $this->addSql('CREATE TABLE drivers (id VARCHAR(36) NOT NULL, name VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, country VARCHAR(36) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_E410C3075E237E06 ON drivers (name)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_E410C307989D9B62 ON drivers (slug)');
        $this->addSql('CREATE TABLE entries (id VARCHAR(36) NOT NULL, event VARCHAR(36) NOT NULL, driver VARCHAR(36) NOT NULL, team VARCHAR(36) DEFAULT NULL, chassis VARCHAR(255) NOT NULL, engine VARCHAR(255) NOT NULL, series_name VARCHAR(255) DEFAULT NULL, series_slug VARCHAR(255) DEFAULT NULL, car_number VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX entry_event_driver_car_idx ON entries (event, driver, car_number)');
        $this->addSql('CREATE TABLE event_sessions (id VARCHAR(36) NOT NULL, event VARCHAR(36) NOT NULL, type VARCHAR(36) NOT NULL, slug VARCHAR(255) NOT NULL, has_result BOOLEAN NOT NULL, start_date TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, end_date TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_DC8C74C3989D9B62 ON event_sessions (slug)');
        $this->addSql('COMMENT ON COLUMN event_sessions.start_date IS \'(DC2Type:nullable_date_time_value_object)\'');
        $this->addSql('COMMENT ON COLUMN event_sessions.end_date IS \'(DC2Type:nullable_date_time_value_object)\'');
        $this->addSql('CREATE TABLE events (id VARCHAR(36) NOT NULL, season VARCHAR(36) NOT NULL, venue VARCHAR(36) NOT NULL, index INT NOT NULL, slug VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, short_name VARCHAR(255) DEFAULT NULL, start_date TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, end_date TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_5387574A989D9B62 ON events (slug)');
        $this->addSql('CREATE UNIQUE INDEX event_season_slug_idx ON events (season, slug)');
        $this->addSql('CREATE UNIQUE INDEX event_season_index_idx ON events (season, index)');
        $this->addSql('COMMENT ON COLUMN events.start_date IS \'(DC2Type:nullable_date_time_value_object)\'');
        $this->addSql('COMMENT ON COLUMN events.end_date IS \'(DC2Type:nullable_date_time_value_object)\'');
        $this->addSql('CREATE TABLE session_types (id VARCHAR(36) NOT NULL, label VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX session_type_label_idx ON session_types (label)');
        $this->addSql('CREATE TABLE team_presentations (id VARCHAR(36) NOT NULL, team VARCHAR(36) NOT NULL, name VARCHAR(255) NOT NULL, image VARCHAR(255) NOT NULL, created_on TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX team_presentation_team_created_on_idx ON team_presentations (team, created_on)');
        $this->addSql('COMMENT ON COLUMN team_presentations.created_on IS \'(DC2Type:team_presentation_created_on)\'');
        $this->addSql('CREATE TABLE teams (id VARCHAR(36) NOT NULL, country VARCHAR(36) NOT NULL, slug VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, code VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_96C22258989D9B62 ON teams (slug)');
        $this->addSql('CREATE TABLE venues (id VARCHAR(36) NOT NULL, name VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, country VARCHAR(36) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_652E22AD5E237E06 ON venues (name)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_652E22AD989D9B62 ON venues (slug)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE championship_presentations');
        $this->addSql('DROP TABLE drivers');
        $this->addSql('DROP TABLE entries');
        $this->addSql('DROP TABLE event_sessions');
        $this->addSql('DROP TABLE events');
        $this->addSql('DROP TABLE session_types');
        $this->addSql('DROP TABLE team_presentations');
        $this->addSql('DROP TABLE teams');
        $this->addSql('DROP TABLE venues');
    }
}
