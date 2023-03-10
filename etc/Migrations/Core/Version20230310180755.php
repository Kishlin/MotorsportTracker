<?php

declare(strict_types=1);

namespace Kishlin\Migrations\Core;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230310180755 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create the countries table.';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE countries (id VARCHAR(36) NOT NULL, code VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_5D66EBAD77153098 ON countries (code)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_5D66EBAD5E237E06 ON countries (name)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE countries');
    }
}
