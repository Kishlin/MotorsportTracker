<?php

declare(strict_types=1);

namespace Kishlin\Migrations\Core\Legacy2;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230305133147 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Remove the constraint event-type on sessions.';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('DROP INDEX event_session_event_type_idx');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE UNIQUE INDEX event_session_event_type_idx ON event_sessions (event, type)');
    }
}
