<?php

declare(strict_types=1);

use Doctrine\Migrations\Configuration\EntityManager\ExistingEntityManager;
use Doctrine\Migrations\Configuration\Migration\PhpFile;
use Doctrine\Migrations\DependencyFactory;
use Doctrine\ORM\Tools\ToolEvents;
use Kishlin\Backend\MotorsportTracker\Shared\Infrastructure\Persistence\Doctrine\EntityManagerFactory\MotorsportTrackerEntityManagerFactory;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Doctrine\MigrationFix\FixPostgresqlDefaultSchemaListener;
use Symfony\Component\Dotenv\Dotenv;

require __DIR__ . '/../../../vendor/autoload.php';

(new Dotenv())->bootEnv(__DIR__ . '/../../../.env.dev');

$database = 'core';
if ('--conn=cache' === $_SERVER['argv'][2]) {
    $database = 'cache';
}

$config = new PhpFile('/app/etc/Migrations/' . ucfirst($database) . '/Config/config.php');

$entityManager = MotorsportTrackerEntityManagerFactory::create(
    parameters: [
        'url' => $_ENV['DATABASE_' . strtoupper($database) . '_URL'],
    ],
    database: $database,
    environment: 'dev',
);

$em = DependencyFactory::fromEntityManager($config, new ExistingEntityManager($entityManager));

$em->getConnection()->getEventManager()->addEventListener(ToolEvents::postGenerateSchema, new FixPostgresqlDefaultSchemaListener());

return $em;
