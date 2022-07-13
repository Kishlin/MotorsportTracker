<?php

declare(strict_types=1);

namespace Kishlin\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20220713181313 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create the Car table.';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE cars (id VARCHAR(36) NOT NULL, number INT NOT NULL, team VARCHAR(36) NOT NULL, season VARCHAR(36) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX car_number_season_team_idx ON cars (number, season, team)');
        $this->addSql('ALTER TABLE cars ADD CONSTRAINT fk_car_season FOREIGN KEY(season) REFERENCES seasons(id)');
        $this->addSql('ALTER TABLE cars ADD CONSTRAINT fk_car_team FOREIGN KEY(team) REFERENCES teams(id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE cars');
    }
}
