<?php

declare(strict_types=1);

namespace Kishlin\Migrations\Core;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230310193038 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add the seasons table.';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE seasons (id VARCHAR(36) NOT NULL, championship VARCHAR(255) NOT NULL, year INT NOT NULL, ref VARCHAR(36) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX championship_season_idx ON seasons (championship, year)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE seasons');
    }
}
