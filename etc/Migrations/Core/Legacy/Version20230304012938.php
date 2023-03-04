<?php

declare(strict_types=1);

namespace Kishlin\Migrations\Core\Legacy;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230304012938 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add a slug column to venues.';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE venues DROP CONSTRAINT fk_venue_country');
        $this->addSql('ALTER TABLE venues ADD slug VARCHAR(255) NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_652E22AD989D9B62 ON venues (slug)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP INDEX UNIQ_652E22AD989D9B62');
        $this->addSql('ALTER TABLE venues DROP slug');
        $this->addSql('ALTER TABLE venues ADD CONSTRAINT fk_venue_country FOREIGN KEY (country) REFERENCES countries (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }
}
