<?php

declare(strict_types=1);

namespace Kishlin\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20220610215445 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add the drivers table.';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE drivers (id VARCHAR(36) NOT NULL, name VARCHAR(255) NOT NULL, firstname VARCHAR(255) NOT NULL, country VARCHAR(36) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX driver_name_firstname_idx ON drivers (name, firstname)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE drivers');
    }
}
