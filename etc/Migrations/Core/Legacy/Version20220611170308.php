<?php

declare(strict_types=1);

namespace Kishlin\Migrations\Core\Legacy;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20220611170308 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add the venues table.';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE venues (id VARCHAR(36) NOT NULL, name VARCHAR(255) NOT NULL, country VARCHAR(36) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE venues ADD CONSTRAINT fk_venue_country FOREIGN KEY(country) REFERENCES countries(id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_652E22AD5E237E06 ON venues (name)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE venues');
    }
}
