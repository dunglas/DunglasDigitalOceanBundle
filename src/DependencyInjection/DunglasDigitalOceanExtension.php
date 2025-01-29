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

namespace Dunglas\DigitalOceanBundle\DependencyInjection;

use DigitalOceanV2\Client;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;

/**
 * @author Kévin Dunglas <kevin@dunglas.fr>
 */
final class DunglasDigitalOceanExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container): void
    {
        $config = $this->processConfiguration(new Configuration(), $configs);

        if (!isset($config['default_connection'])) {
            $config['default_connection'] = array_key_first($config['connections']);
        }

        foreach ($config['connections'] as $name => $connection) {
            $id = sprintf('dunglas.digital_ocean.%s', $name);
            $container
                ->register($id, Client::class)
                ->addMethodCall('authenticate', [$connection['token']]);
            $container->registerAliasForArgument($id, Client::class, "{$name}Client");

            if ($name === $config['default_connection']) {
                $container->setAlias(Client::class, $id);
            }
        }
    }
}
