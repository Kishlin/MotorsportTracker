<?php

declare(strict_types=1);

namespace Kishlin\Migrations\Core;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230310191126 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create the championships table.';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE championships (id VARCHAR(36) NOT NULL, name VARCHAR(255) NOT NULL, short_name VARCHAR(255) DEFAULT NULL, short_code VARCHAR(255) NOT NULL, ref VARCHAR(36) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_B682EA935E237E06 ON championships (name)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_B682EA9317D2FE0D ON championships (short_code)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE championships');
    }
}
