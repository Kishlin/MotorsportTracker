<?php

declare(strict_types=1);

namespace Kishlin\Migrations\Core\Legacy;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230303233637 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add the name column to countries.';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE countries ADD name VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE countries DROP name');
    }
}
