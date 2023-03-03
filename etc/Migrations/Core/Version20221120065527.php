<?php

declare(strict_types=1);

namespace Kishlin\Migrations\Core;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20221120065527 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Change ResultPositions to allow for dnf or dns values.';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE results ALTER "position" TYPE VARCHAR(255)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE results ALTER position TYPE INT');
    }
}
