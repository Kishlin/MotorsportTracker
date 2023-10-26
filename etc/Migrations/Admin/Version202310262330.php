<?php

declare(strict_types=1);

namespace Migrations\Admin;

use Kishlin\Backend\Persistence\Migration\Migration;

final class Version202310262330 implements Migration
{
    public function up(): string
    {
        return <<<'SQL'
CREATE TABLE public.job (
    id character varying(36) NOT NULL,
    type character varying(255) NOT NULL,
    status character varying(255) NOT NULL,
    params jsonb NOT NULL,
    started_on timestamp(0) without time zone NOT NULL,
    finished_on timestamp(0) without time zone DEFAULT NULL::timestamp without time zone
)
SQL;
    }

    public function down(): string
    {
        return <<<'SQL'
DROP TABLE public.job;
SQL;
    }
}
