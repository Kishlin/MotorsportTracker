<?php

declare(strict_types=1);

namespace Kishlin\Migrations\Core;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20220611002012 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add a driver-country foreign key.';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE drivers ADD CONSTRAINT fk_driver_country FOREIGN KEY(country) REFERENCES countries(id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE drivers DROP CONSTRAINT fk_driver_country');
    }
}
