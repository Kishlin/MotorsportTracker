<?php

declare(strict_types=1);

namespace Kishlin\Migrations\Core;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230304002928 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Unify the firstname and lastname columns in Drivers';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE drivers DROP firstname');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_E410C3075E237E06 ON drivers (name)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP INDEX UNIQ_E410C3075E237E06');
        $this->addSql('ALTER TABLE drivers ADD firstname VARCHAR(255) NOT NULL');
    }
}
