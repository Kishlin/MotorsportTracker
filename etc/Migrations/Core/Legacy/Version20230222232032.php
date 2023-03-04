<?php

declare(strict_types=1);

namespace Kishlin\Migrations\Core\Legacy;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230222232032 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add the TeamPresentation table.';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE team_presentations (id VARCHAR(36) NOT NULL, team VARCHAR(36) NOT NULL, name VARCHAR(255) NOT NULL, image VARCHAR(255) NOT NULL, created_on TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX team_presentation_team_created_on_idx ON team_presentations (team, created_on)');
        $this->addSql('COMMENT ON COLUMN team_presentations.created_on IS \'(DC2Type:team_presentation_created_on)\'');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE team_presentations');
    }
}
