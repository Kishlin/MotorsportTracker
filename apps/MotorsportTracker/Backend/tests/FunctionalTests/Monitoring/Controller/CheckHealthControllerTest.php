<?php

declare(strict_types=1);

namespace Kishlin\Tests\Apps\MotorsportTracker\Backend\FunctionalTests\Monitoring\Controller;

use Kishlin\Tests\Apps\MotorsportTracker\Backend\Tools\KernelTestCaseTrait;
use Kishlin\Tests\Backend\Apps\AbstractFunctionalTests\Controller\Monitoring\CheckHealthControllerTestCase;

/**
 * Functional Test to verify the application has an up-and-running check-health API endpoint.
 *
 * @see CheckHealthControllerTestCase
 *
 * @internal
 * @covers \Kishlin\Apps\MotorsportTracker\Backend\Monitoring\Controller\CheckHealthController
 */
final class CheckHealthControllerTest extends CheckHealthControllerTestCase
{
    use KernelTestCaseTrait;

    public function testTheAPIShowsStatusForAllServices(): void
    {
        $client      = self::createClient();
        $endpointUri = '/monitoring/check-health';

        $expectedServices = [
            'MotorsportTracker',
            'Database Cache',
            'Environment',
        ];

        self::assertTheAPIShowsStatusForAllServices($client, $endpointUri, $expectedServices);
    }
}
