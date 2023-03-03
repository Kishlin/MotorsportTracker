<?php

declare(strict_types=1);

namespace Kishlin\Migrations\Core;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20221225180107 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create the Championship Presentation table.';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE championship_presentations (id VARCHAR(36) NOT NULL, championship VARCHAR(36) NOT NULL, icon VARCHAR(255) NOT NULL, color VARCHAR(255) NOT NULL, created_on TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX championship_created_on_idx ON championship_presentations (championship, created_on)');
        $this->addSql('COMMENT ON COLUMN championship_presentations.created_on IS \'(DC2Type:championship_presentation_created_on)\'');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE championship_presentations');
    }
}
