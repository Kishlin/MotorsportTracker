<?php

declare(strict_types=1);

namespace Kishlin\Migrations\Core\Legacy2;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230310002647 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add the slug column to drivers.';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE drivers ADD slug VARCHAR(255) NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_E410C307989D9B62 ON drivers (slug)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP INDEX UNIQ_E410C307989D9B62');
        $this->addSql('ALTER TABLE drivers DROP slug');
    }
}
