<?php

declare(strict_types=1);

namespace Kishlin\Migrations\Core\Legacy2;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230310132931 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create the entries table.';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE entries (id VARCHAR(36) NOT NULL, event VARCHAR(36) NOT NULL, driver VARCHAR(36) NOT NULL, team VARCHAR(36) DEFAULT NULL, chassis VARCHAR(255) NOT NULL, engine VARCHAR(255) NOT NULL, series_name VARCHAR(255) DEFAULT NULL, series_slug VARCHAR(255) DEFAULT NULL, car_number VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX entry_event_driver_car_idx ON entries (event, driver, car_number)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE entries');
    }
}
