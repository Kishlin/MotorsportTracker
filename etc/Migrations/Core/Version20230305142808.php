<?php

declare(strict_types=1);

namespace Kishlin\Migrations\Core;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230305142808 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Update the comment on championship_presentations.created_on';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('COMMENT ON COLUMN championship_presentations.created_on IS \'(DC2Type:date_time_value_object)\'');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('COMMENT ON COLUMN championship_presentations.created_on IS NULL');
    }
}
