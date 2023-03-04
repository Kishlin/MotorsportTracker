<?php

declare(strict_types=1);

namespace Kishlin\Migrations\Core\Legacy;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20221105182411 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create the DriverStanding and TeamStanding table.';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE driver_standings (id VARCHAR(36) NOT NULL, event VARCHAR(36) NOT NULL, driver VARCHAR(36) NOT NULL, points DOUBLE PRECISION NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX driver_standing_event_driver_idx ON driver_standings (event, driver)');
        $this->addSql('CREATE TABLE team_standings (id VARCHAR(36) NOT NULL, event VARCHAR(36) NOT NULL, team VARCHAR(36) NOT NULL, points DOUBLE PRECISION NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX team_standing_event_team_idx ON team_standings (event, team)');
        $this->addSql('ALTER TABLE driver_standings ADD CONSTRAINT fk_driver_standings_event FOREIGN KEY(event) REFERENCES events(id)');
        $this->addSql('ALTER TABLE driver_standings ADD CONSTRAINT fk_driver_standings_driver FOREIGN KEY(driver) REFERENCES drivers(id)');
        $this->addSql('ALTER TABLE team_standings ADD CONSTRAINT fk_team_standings_event FOREIGN KEY(event) REFERENCES events(id)');
        $this->addSql('ALTER TABLE team_standings ADD CONSTRAINT fk_team_standings_driver FOREIGN KEY(team) REFERENCES teams(id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE driver_standings');
        $this->addSql('DROP TABLE team_standings');
    }
}
