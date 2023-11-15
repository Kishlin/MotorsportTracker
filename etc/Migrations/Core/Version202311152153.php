<?php

declare(strict_types=1);

namespace Migrations\Core;

use Kishlin\Backend\Persistence\Migration\Migration;

final class Version202311152153 implements Migration
{
    public function up(): string
    {
        return <<<'SQL'
ALTER TABLE public.constructor_team
ADD COLUMN id VARCHAR(36);

-- noinspection SqlWithoutWhere
UPDATE public.constructor_team
SET id = CONCAT(
    LPAD(TO_HEX(FLOOR(RANDOM() * 2147483647)::int), 8, '0'), '-', 
    LPAD(TO_HEX(FLOOR(RANDOM() * 65535)::int), 4, '0'), '-',
    LPAD(TO_HEX(FLOOR(RANDOM() * 65535)::int), 4, '0'), '-',
    LPAD(TO_HEX(FLOOR(RANDOM() * 65535)::int), 4, '0'), '-',
    LPAD(TO_HEX(FLOOR(RANDOM() * 2147483647)::int), 8, '0'), 
    LPAD(TO_HEX(FLOOR(RANDOM() * 65535)::int), 4, '0')
);

ALTER TABLE public.constructor_team
ALTER COLUMN id SET NOT NULL;

ALTER TABLE ONLY public.constructor_team
    DROP CONSTRAINT pkey_constructor_teams;

ALTER TABLE ONLY public.constructor_team
    ADD CONSTRAINT pkey_constructor_teams PRIMARY KEY (id);
SQL;
    }

    public function down(): string
    {

        return <<<'SQL'
ALTER TABLE ONLY public.constructor_team
    DROP CONSTRAINT pkey_constructor_teams;

ALTER TABLE public.constructor_team
DROP COLUMN id;

ALTER TABLE ONLY public.constructor_team
    ADD CONSTRAINT pkey_constructor_teams PRIMARY KEY (constructor, team);
SQL;
    }
}
