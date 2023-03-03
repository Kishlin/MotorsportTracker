<?php

declare(strict_types=1);

namespace Kishlin\Migrations\Core;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20220408213133 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create the table for Countries.';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE countries (id VARCHAR(36) NOT NULL, code VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_5D66EBAD77153098 ON countries (code)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP TABLE countries');
    }
}
