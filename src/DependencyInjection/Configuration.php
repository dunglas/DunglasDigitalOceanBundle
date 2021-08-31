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

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * @author Kévin Dunglas <kevin@dunglas.fr>
 */
final class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('dunglas_digital_ocean');
        $rootNode = $treeBuilder->getRootNode();
        $rootNode
            ->beforeNormalization()
                ->ifString()
                ->then(static function (string $token) {
                    return ['connections' => ['default' => ['token' => $token]]];
                })
            ->end()
            ->fixXmlConfig('connection')
            ->children()
                ->arrayNode('connections')
                    ->useAttributeAsKey('name')
                    ->normalizeKeys(false)
                    ->arrayPrototype()
                        ->beforeNormalization()
                            ->ifString()
                            ->then(static function (string $token) {
                                return ['token' => $token];
                            })
                        ->end()
                        ->children()
                            ->scalarNode('token')->isRequired()->info('Your DigitalOcean access token')->end()
                        ->end()
                    ->end()
            ->end()
            ->scalarNode('default_connection')->end()
        ;

        return $treeBuilder;
    }
}
