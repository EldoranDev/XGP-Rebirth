<?php

namespace App\DependencyInjection;

use App\GameModel\Building;
use App\GameModel\ProducingBuilding;
use App\GameModel\Resource;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Extension\Extension;

class GameExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container): void
    {
        $this->processConfiguration(new GameConfiguration(), $configs);
        $config = $configs[0];

		$this->loadResources($config['resources'], $container);
        $this->loadBuildings($config['buildings'], $container);

    }

	private function loadResources(array $resources, ContainerBuilder $container): void
	{
		foreach ($resources as $name => $resource) {
			$definition = new Definition(Resource::class, [
				$name,
				$resource['name'],
				$resource['description'],
				$resource['image'],
			])
				->addTag('game.resources', ['id' => $name]);

			$container->setDefinition("game.resource.{$name}", $definition);
		}
	}

	private function loadBuildings(array $buildings, ContainerBuilder $container): void
	{
		foreach ($buildings as $name => $building) {
			if ($building['producer']) {
				$definition = new Definition(ProducingBuilding::class, [
					$name,
					$building['name'],
					$building['category'],
					$building['image'],
					$building['costs'],
					$building['energy'],
					$building['production'],
				])->addTag('game.buildings.producer');
			} else {
				$definition = new Definition(Building::class, [
					$name,
					$building['name'],
					$building['category'],
					$building['image'],
					$building['costs'],
				]);
			}

			$definition->addTag('game.buildings', ['category' => $building['category']]);

			$container->setDefinition("game.building.{$name}", $definition);
		}
	}
}
