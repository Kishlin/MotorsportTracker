<?php

declare(strict_types=1);

namespace Kishlin\Migrations\Core;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20220409185635 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add the slug column to championships.';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE championships ADD slug VARCHAR(36) NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_B682EA93989D9B62 ON championships (slug)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE championships DROP slug');
    }
}
