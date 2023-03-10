<?php

declare(strict_types=1);

namespace Kishlin\Migrations\Core\Legacy2;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version0 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Migration 0.';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE championship_presentations (id VARCHAR(36) NOT NULL, championship VARCHAR(36) NOT NULL, icon VARCHAR(255) NOT NULL, color VARCHAR(255) NOT NULL, created_on TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX championship_created_on_idx ON championship_presentations (championship, created_on)');
        $this->addSql('COMMENT ON COLUMN championship_presentations.created_on IS \'(DC2Type:championship_presentation_created_on)\'');
        $this->addSql('CREATE TABLE championships (id VARCHAR(36) NOT NULL, name VARCHAR(255) NOT NULL, slug VARCHAR(36) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_B682EA935E237E06 ON championships (name)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_B682EA93989D9B62 ON championships (slug)');
        $this->addSql('CREATE TABLE countries (id VARCHAR(36) NOT NULL, code VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_5D66EBAD77153098 ON countries (code)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_5D66EBAD5E237E06 ON countries (name)');
        $this->addSql('CREATE TABLE drivers (id VARCHAR(36) NOT NULL, name VARCHAR(255) NOT NULL, country VARCHAR(36) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_E410C3075E237E06 ON drivers (name)');
        $this->addSql('CREATE TABLE event_steps (id VARCHAR(36) NOT NULL, event VARCHAR(36) NOT NULL, type VARCHAR(36) NOT NULL, date_time TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX event_step_event_type_idx ON event_steps (event, type)');
        $this->addSql('CREATE TABLE events (id VARCHAR(36) NOT NULL, season VARCHAR(36) NOT NULL, venue VARCHAR(36) NOT NULL, index INT NOT NULL, slug VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, short_name VARCHAR(255) DEFAULT NULL, start_date TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, end_date TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_5387574A989D9B62 ON events (slug)');
        $this->addSql('CREATE UNIQUE INDEX event_season_name_idx ON events (season, name)');
        $this->addSql('CREATE UNIQUE INDEX event_season_index_idx ON events (season, index)');
        $this->addSql('COMMENT ON COLUMN events.start_date IS \'(DC2Type:nullable_date_time_value_object)\'');
        $this->addSql('COMMENT ON COLUMN events.end_date IS \'(DC2Type:nullable_date_time_value_object)\'');
        $this->addSql('CREATE TABLE seasons (id VARCHAR(36) NOT NULL, championship VARCHAR(255) NOT NULL, year INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX championship_season_idx ON seasons (championship, year)');
        $this->addSql('CREATE TABLE step_types (id VARCHAR(36) NOT NULL, label VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX step_type_label_idx ON step_types (label)');
        $this->addSql('CREATE TABLE team_presentations (id VARCHAR(36) NOT NULL, team VARCHAR(36) NOT NULL, name VARCHAR(255) NOT NULL, image VARCHAR(255) NOT NULL, created_on TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX team_presentation_team_created_on_idx ON team_presentations (team, created_on)');
        $this->addSql('COMMENT ON COLUMN team_presentations.created_on IS \'(DC2Type:team_presentation_created_on)\'');
        $this->addSql('CREATE TABLE teams (id VARCHAR(36) NOT NULL, name VARCHAR(255) NOT NULL, image VARCHAR(255) NOT NULL, country VARCHAR(36) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX team_name_idx ON teams (name)');
        $this->addSql('CREATE TABLE venues (id VARCHAR(36) NOT NULL, name VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, country VARCHAR(36) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_652E22AD5E237E06 ON venues (name)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_652E22AD989D9B62 ON venues (slug)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE championship_presentations');
        $this->addSql('DROP TABLE championships');
        $this->addSql('DROP TABLE countries');
        $this->addSql('DROP TABLE drivers');
        $this->addSql('DROP TABLE event_steps');
        $this->addSql('DROP TABLE events');
        $this->addSql('DROP TABLE seasons');
        $this->addSql('DROP TABLE step_types');
        $this->addSql('DROP TABLE team_presentations');
        $this->addSql('DROP TABLE teams');
        $this->addSql('DROP TABLE venues');
    }
}
