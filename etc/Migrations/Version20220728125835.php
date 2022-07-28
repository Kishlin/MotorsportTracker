<?php

declare(strict_types=1);

namespace Kishlin\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20220728125835 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create the Racer table.';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE racers (id VARCHAR(36) NOT NULL, car VARCHAR(36) NOT NULL, driver VARCHAR(36) NOT NULL, startDate TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, endDate TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX racer_car_driver_idx ON racers (car, driver)');
        $this->addSql('COMMENT ON COLUMN racers.startDate IS \'(DC2Type:racer_start_date)\'');
        $this->addSql('COMMENT ON COLUMN racers.endDate IS \'(DC2Type:racer_end_date)\'');
        $this->addSql('ALTER TABLE racers ADD CONSTRAINT fk_racer_car FOREIGN KEY(car) REFERENCES cars(id)');
        $this->addSql('ALTER TABLE racers ADD CONSTRAINT fk_racer_driver FOREIGN KEY(driver) REFERENCES drivers(id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE racers');
    }
}
