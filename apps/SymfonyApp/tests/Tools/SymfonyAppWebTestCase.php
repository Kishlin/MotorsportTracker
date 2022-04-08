<?php

declare(strict_types=1);

namespace Kishlin\Tests\Apps\SymfonyApp\Tools;

use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManagerInterface;
use Kishlin\Backend\RPGIdleGame\Shared\Infrastructure\Persistence\Doctrine\EntityManagerFactory\RPGIdleGameEntityManagerFactory;
use Kishlin\Backend\Shared\Domain\Aggregate\AggregateRoot;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Throwable;

abstract class SymfonyAppWebTestCase extends WebTestCase
{
    use KernelTestCaseTrait;

    private static ?EntityManagerInterface $entityManager = null;

    /**
     * @throws Exception
     */
    protected function tearDown(): void
    {
        parent::tearDown();

        if (null !== self::$entityManager) {
            self::$entityManager->close();
            self::$entityManager = null;
        }
    }

    protected static function entityManager(): EntityManagerInterface
    {
        if (null === self::$entityManager) {
            self::$entityManager = self::createEntityManager();
        }

        return self::$entityManager;
    }

    protected static function loadFixtures(AggregateRoot ...$aggregates): void
    {
        foreach ($aggregates as $aggregateRoot) {
            self::entityManager()->persist($aggregateRoot);
        }

        self::entityManager()->flush();
    }

    private static function createEntityManager(): EntityManagerInterface
    {
        try {
//            TODO return an entity manager
//            return EntityManagerFactory::create(
//                ['url' => $_ENV['DATABASE_URL']],
//                'test'
//            );
        } catch (Throwable $e) {
            self::fail('Failed to create an entity manager: ' . $e->getMessage());
        }
    }
}
