<?php

declare(strict_types=1);

namespace Kishlin\Migrations\Core;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20221120071036 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Remove too-restrictive constraint on racers.';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('DROP INDEX racer_car_driver_idx');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE UNIQUE INDEX racer_car_driver_idx ON racers (car, driver)');
    }
}
