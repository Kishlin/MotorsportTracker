<?php

declare(strict_types=1);

namespace Kishlin\Migrations\Core;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230305015621 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create the event sessions table.';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE event_sessions (id VARCHAR(36) NOT NULL, event VARCHAR(36) NOT NULL, type VARCHAR(36) NOT NULL, slug VARCHAR(255) NOT NULL, has_result BOOLEAN NOT NULL, start_date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, end_date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_DC8C74C3989D9B62 ON event_sessions (slug)');
        $this->addSql('CREATE UNIQUE INDEX event_session_event_type_idx ON event_sessions (event, type)');
        $this->addSql('COMMENT ON COLUMN event_sessions.start_date IS \'(DC2Type:nullable_date_time_value_object)\'');
        $this->addSql('COMMENT ON COLUMN event_sessions.end_date IS \'(DC2Type:nullable_date_time_value_object)\'');
        $this->addSql('DROP TABLE event_steps');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE TABLE event_steps (id VARCHAR(36) NOT NULL, event VARCHAR(36) NOT NULL, type VARCHAR(36) NOT NULL, date_time TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX event_step_event_type_idx ON event_steps (event, type)');
        $this->addSql('DROP TABLE event_sessions');
    }
}
