<?php

/*
 * This file is part of the DunglasDigitalOceanBundle project.
 *
 * (c) Kévin Dunglas <kevin@dunglas.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Dunglas\DigitalOceanBundle\Tests\DependencyInjection;

use DigitalOceanV2\Client;
use Dunglas\DigitalOceanBundle\DependencyInjection\DunglasDigitalOceanExtension;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class DunglasDigitalOceanExtensionTest extends TestCase
{
    public function testExtension(): void
    {
        $container = new ContainerBuilder();

        (new DunglasDigitalOceanExtension())->load([
            'dunglas_digital_ocean' => [
                'connections' => [
                    'primary' => 'foo',
                    'secondary' => 'bar',
                ],
            ],
        ], $container);

        $this->assertTrue($container->has('dunglas.digital_ocean.primary'));
        $this->assertTrue($container->has('dunglas.digital_ocean.secondary'));
        $this->assertTrue($container->hasAlias(Client::class));
        $this->assertTrue($container->hasAlias(Client::class.' $primaryClient'));
        $this->assertTrue($container->hasAlias(Client::class.' $secondaryClient'));
        $this->assertInstanceOf(Client::class, $container->get('dunglas.digital_ocean.primary'));
        $this->assertInstanceOf(Client::class, $container->get('dunglas.digital_ocean.secondary'));
    }
}
