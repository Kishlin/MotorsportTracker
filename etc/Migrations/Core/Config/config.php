<?php

declare(strict_types=1);

return [
    'migrations_paths' => [
        'Kishlin\Migrations\Core' => '/app/etc/Migrations/Core',
    ],
    'all_or_nothing'          => true,
    'transactional'           => true,
    'check_database_platform' => true,
    'organize_migrations'     => 'none',
];
