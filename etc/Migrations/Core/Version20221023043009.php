<?php

declare(strict_types=1);

namespace Kishlin\Migrations\Core;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20221023043009 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create the Result table.';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE results (id VARCHAR(36) NOT NULL, racer VARCHAR(36) NOT NULL, event_step VARCHAR(36) NOT NULL, fastest_lap_time VARCHAR(36) NOT NULL, position INT NOT NULL, points DOUBLE PRECISION NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX result_event_step_position_idx ON results (event_step, position)');
        $this->addSql('CREATE UNIQUE INDEX result_event_step_racer_idx ON results (event_step, racer)');
        $this->addSql('ALTER TABLE results ADD CONSTRAINT fk_result_racer FOREIGN KEY(racer) REFERENCES racers(id)');
        $this->addSql('ALTER TABLE results ADD CONSTRAINT fk_result_event_step FOREIGN KEY(event_step) REFERENCES event_steps(id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE results');
    }
}
