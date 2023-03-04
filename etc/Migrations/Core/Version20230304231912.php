<?php

declare(strict_types=1);

namespace Kishlin\Migrations\Core;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230304231912 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Remove deprecated namespaces';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE results DROP CONSTRAINT fk_result_racer');
        $this->addSql('ALTER TABLE driver_moves DROP CONSTRAINT fk_driver_moves_car');
        $this->addSql('ALTER TABLE racers DROP CONSTRAINT fk_racer_car');
        $this->addSql('DROP TABLE driver_moves');
        $this->addSql('DROP TABLE racers');
        $this->addSql('DROP TABLE results');
        $this->addSql('DROP TABLE cars');

    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE TABLE driver_moves (id VARCHAR(36) NOT NULL, driver VARCHAR(36) NOT NULL, car VARCHAR(36) NOT NULL, date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX driver_move_car_date_idx ON driver_moves (car, date)');
        $this->addSql('CREATE UNIQUE INDEX driver_move_driver_date_idx ON driver_moves (driver, date)');
        $this->addSql('CREATE INDEX IDX_C5BDC51F773DE69D ON driver_moves (car)');
        $this->addSql('CREATE INDEX IDX_C5BDC51F11667CD9 ON driver_moves (driver)');
        $this->addSql('CREATE TABLE racers (id VARCHAR(36) NOT NULL, car VARCHAR(36) NOT NULL, driver VARCHAR(36) NOT NULL, startdate TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, enddate TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_E0F03940773DE69D ON racers (car)');
        $this->addSql('CREATE INDEX IDX_E0F0394011667CD9 ON racers (driver)');
        $this->addSql('CREATE TABLE results (id VARCHAR(36) NOT NULL, racer VARCHAR(36) NOT NULL, event_step VARCHAR(36) NOT NULL, "position" VARCHAR(255) NOT NULL, points DOUBLE PRECISION NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX result_event_step_racer_idx ON results (event_step, racer)');
        $this->addSql('CREATE UNIQUE INDEX result_event_step_position_idx ON results (event_step, position)');
        $this->addSql('CREATE INDEX IDX_9FA3E4145C72B297 ON results (event_step)');
        $this->addSql('CREATE INDEX IDX_9FA3E4142ABA2E5F ON results (racer)');
        $this->addSql('CREATE TABLE cars (id VARCHAR(36) NOT NULL, team VARCHAR(36) NOT NULL, season VARCHAR(36) NOT NULL, number INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX car_number_season_team_idx ON cars (number, season, team)');
        $this->addSql('CREATE INDEX IDX_95C71D14F0E45BA9 ON cars (season)');
        $this->addSql('CREATE INDEX IDX_95C71D14C4E0A61F ON cars (team)');
        $this->addSql('ALTER TABLE driver_moves ADD CONSTRAINT fk_driver_moves_car FOREIGN KEY (car) REFERENCES cars (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE driver_moves ADD CONSTRAINT fk_driver_moves_driver FOREIGN KEY (driver) REFERENCES drivers (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE racers ADD CONSTRAINT fk_racer_car FOREIGN KEY (car) REFERENCES cars (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE racers ADD CONSTRAINT fk_racer_driver FOREIGN KEY (driver) REFERENCES drivers (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE results ADD CONSTRAINT fk_result_event_step FOREIGN KEY (event_step) REFERENCES event_steps (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE results ADD CONSTRAINT fk_result_racer FOREIGN KEY (racer) REFERENCES racers (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE cars ADD CONSTRAINT fk_car_season FOREIGN KEY (season) REFERENCES seasons (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE cars ADD CONSTRAINT fk_car_team FOREIGN KEY (team) REFERENCES teams (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }
}
