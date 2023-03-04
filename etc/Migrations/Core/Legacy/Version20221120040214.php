<?php

declare(strict_types=1);

namespace Kishlin\Migrations\Core\Legacy;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20221120040214 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Remove the column Results::fastestLapTime';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE results DROP fastest_lap_time');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE results ADD fastest_lap_time VARCHAR(36) NOT NULL');
    }
}
