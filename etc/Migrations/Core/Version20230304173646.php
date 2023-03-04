<?php

declare(strict_types=1);

namespace Kishlin\Migrations\Core;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230304173646 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Remove deprecated standings tables';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('DROP TABLE driver_standings');
        $this->addSql('DROP TABLE team_standings');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE TABLE driver_standings (id VARCHAR(36) NOT NULL, event VARCHAR(36) NOT NULL, driver VARCHAR(36) NOT NULL, points DOUBLE PRECISION NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX driver_standing_event_driver_idx ON driver_standings (event, driver)');
        $this->addSql('CREATE INDEX IDX_BC45047E11667CD9 ON driver_standings (driver)');
        $this->addSql('CREATE INDEX IDX_BC45047E3BAE0AA7 ON driver_standings (event)');
        $this->addSql('CREATE TABLE team_standings (id VARCHAR(36) NOT NULL, event VARCHAR(36) NOT NULL, team VARCHAR(36) NOT NULL, points DOUBLE PRECISION NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX team_standing_event_team_idx ON team_standings (event, team)');
        $this->addSql('CREATE INDEX IDX_24621180C4E0A61F ON team_standings (team)');
        $this->addSql('CREATE INDEX IDX_246211803BAE0AA7 ON team_standings (event)');
        $this->addSql('ALTER TABLE driver_standings ADD CONSTRAINT fk_driver_standings_driver FOREIGN KEY (driver) REFERENCES drivers (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE driver_standings ADD CONSTRAINT fk_driver_standings_event FOREIGN KEY (event) REFERENCES events (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE team_standings ADD CONSTRAINT fk_team_standings_driver FOREIGN KEY (team) REFERENCES teams (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE team_standings ADD CONSTRAINT fk_team_standings_event FOREIGN KEY (event) REFERENCES events (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }
}
