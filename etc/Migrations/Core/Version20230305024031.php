<?php

declare(strict_types=1);

namespace Kishlin\Migrations\Core;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230305024031 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Rename step types to session types.';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE session_types (id VARCHAR(36) NOT NULL, label VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX session_type_label_idx ON session_types (label)');
        $this->addSql('DROP TABLE step_types');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE TABLE step_types (id VARCHAR(36) NOT NULL, label VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX step_type_label_idx ON step_types (label)');
        $this->addSql('DROP TABLE session_types');
    }
}
