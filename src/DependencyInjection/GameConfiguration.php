<?php

namespace App\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class GameConfiguration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('game');

        $treeBuilder->getRootNode()
            ->children()
				->arrayNode('resources')
					->useAttributeAsKey('name')
					->arrayPrototype()
						->children()
							->stringNode('name')->end()
							->stringNode('description')->end()
							->stringNode('image')->end()
						->end()
					->end()
				->end()
                ->arrayNode('buildings')
                    ->useAttributeAsKey('name')
                    ->arrayPrototype()
                        ->children()
                            ->stringNode('name')->end()
                            ->stringNode('image')->end()
                            ->stringNode('description')->end()
							->enumNode('category')
								->values(['resources', 'facilities'])
							->end()
							->arrayNode('costs')
								->isRequired()
								->children()
									->floatNode('factor')->end()
									->arrayNode('resources')
										->children()
											->integerNode('metal')->end()
											->integerNode('crystal')->end()
											->integerNode('deuterium')->end()
										->end()
									->end()
								->end()
							->end()
							->booleanNode('producer')
								->defaultFalse()
							->end()
							// Production Building
							->stringNode('energy')->end()
							->arrayNode('production')
                                ->children()
                                    ->stringNode('metal')->end()
                                    ->stringNode('crystal')->end()
                                    ->stringNode('deuterium')->end()
                                ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
