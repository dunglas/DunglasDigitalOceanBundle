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

use Dunglas\DigitalOceanBundle\DependencyInjection\Configuration;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Config\Definition\Processor;

/**
 * @author Kévin Dunglas <kevin@dunglas.fr>
 */
class ConfigurationTest extends TestCase
{
    /**
     * @dataProvider configsProvider
     */
    public function testConfig(array $configs): void
    {
        $config = (new Processor())->processConfiguration(new Configuration(), $configs);
        $this->assertSame('foo', $config['connections']['default']['token']);
    }

    public function configsProvider(): iterable
    {
        yield [
            [
                'dunglas_digital_ocean' => [
                    'connections' => [
                        'default' => ['token' => 'foo'],
                    ],
                ],
            ],
        ];

        yield [
            [
                'dunglas_digital_ocean' => [
                    'connections' => [
                        'default' => 'foo',
                    ],
                ],
            ],
        ];

        yield [['dunglas_digital_ocean' => 'foo']];
    }
}
