<?php

declare(strict_types=1);

namespace Kishlin\Migrations\Core\Legacy;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20220713183949 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create the DriverMove table.';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE driver_moves (id VARCHAR(36) NOT NULL, driver VARCHAR(36) NOT NULL, car VARCHAR(36) NOT NULL, date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX driver_move_driver_date_idx ON driver_moves (driver, date)');
        $this->addSql('CREATE UNIQUE INDEX driver_move_car_date_idx ON driver_moves (car, date)');
        $this->addSql('ALTER TABLE driver_moves ADD CONSTRAINT fk_driver_moves_driver FOREIGN KEY(driver) REFERENCES drivers(id)');
        $this->addSql('ALTER TABLE driver_moves ADD CONSTRAINT fk_driver_moves_car FOREIGN KEY(car) REFERENCES cars(id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE driver_moves');
    }
}
