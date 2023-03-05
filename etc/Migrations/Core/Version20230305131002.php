<?php

declare(strict_types=1);

namespace Kishlin\Migrations\Core;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230305131002 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Make the dateTime nullables.';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE event_sessions ALTER start_date DROP NOT NULL');
        $this->addSql('ALTER TABLE event_sessions ALTER end_date DROP NOT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE event_sessions ALTER start_date SET NOT NULL');
        $this->addSql('ALTER TABLE event_sessions ALTER end_date SET NOT NULL');
    }
}
