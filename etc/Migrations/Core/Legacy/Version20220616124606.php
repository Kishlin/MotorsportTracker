<?php

declare(strict_types=1);

namespace Kishlin\Migrations\Core\Legacy;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20220616124606 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create the Team table.';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE teams (id VARCHAR(36) NOT NULL, name VARCHAR(255) NOT NULL, image VARCHAR(255) NOT NULL, country VARCHAR(36) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX team_name_idx ON teams (name)');
        $this->addSql('ALTER TABLE teams ADD CONSTRAINT fk_team_country FOREIGN KEY(country) REFERENCES countries(id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE teams');
    }
}
