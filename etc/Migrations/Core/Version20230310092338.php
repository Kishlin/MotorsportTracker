<?php

declare(strict_types=1);

namespace Kishlin\Migrations\Core;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230310092338 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Update the teams table to the new schema.';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('DROP INDEX team_name_idx');
        $this->addSql('ALTER TABLE teams ADD slug VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE teams ADD code VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE teams DROP image');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_96C22258989D9B62 ON teams (slug)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP INDEX UNIQ_96C22258989D9B62');
        $this->addSql('ALTER TABLE teams ADD image VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE teams DROP slug');
        $this->addSql('ALTER TABLE teams DROP code');
        $this->addSql('CREATE UNIQUE INDEX team_name_idx ON teams (name)');
    }
}
