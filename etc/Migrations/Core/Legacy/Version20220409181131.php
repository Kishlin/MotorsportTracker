<?php

declare(strict_types=1);

namespace Kishlin\Migrations\Core\Legacy;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20220409181131 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create the tables for the Championship and Season entities.';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE championships (id VARCHAR(36) NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_B682EA935E237E06 ON championships (name)');
        $this->addSql('CREATE TABLE seasons (id VARCHAR(36) NOT NULL, championship VARCHAR(36) NOT NULL, year INT NOT NULL, PRIMARY KEY(id), CONSTRAINT fk_championship FOREIGN KEY(championship) REFERENCES championships(id))');
        $this->addSql('CREATE UNIQUE INDEX championship_season_idx ON seasons (championship, year)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE championships');
        $this->addSql('DROP TABLE seasons');
    }
}
