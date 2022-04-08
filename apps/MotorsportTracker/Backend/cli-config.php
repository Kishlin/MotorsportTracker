<?php

declare(strict_types=1);

use Doctrine\Migrations\Configuration\EntityManager\ExistingEntityManager;
use Doctrine\Migrations\Configuration\Migration\PhpFile;
use Doctrine\Migrations\DependencyFactory;
use Kishlin\Backend\MotorsportTracker\Shared\Infrastructure\Persistence\Doctrine\EntityManagerFactory\MotorsportTrackerEntityManagerFactory;
use Symfony\Component\Dotenv\Dotenv;

require __DIR__ . '/../../../vendor/autoload.php';

(new Dotenv())->bootEnv(__DIR__. '/../../../.env.dev');

$config = new PhpFile('/app/etc/Migrations/Config/config.php');

$entityManager = MotorsportTrackerEntityManagerFactory::create(
    parameters: [
        'url' => $_ENV['DATABASE_URL'],
    ],
    environment: 'dev',
);

return DependencyFactory::fromEntityManager($config, new ExistingEntityManager($entityManager));
