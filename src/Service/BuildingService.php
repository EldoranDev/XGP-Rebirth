<?php

namespace App\Service;

use App\GameModel\Building;
use App\GameModel\ProducingBuilding;

class BuildingService
{
    /**
     * @var array<string, array<string, Building>>
     */
    private array $categories;

	/**
	 * @var array<string, Building>)
	 */
	private array $buildings;

	/**
	 * @var array<string, ProducingBuilding>
	 */
	private array $producer;

	private const array COST_FACTORS = ['metal', 'crystal'];

    public function __construct(
		private OptionsService $optionsService,

        iterable $buildings,
		iterable $producer,
    ) {
		$this->producer = iterator_to_array($producer);
		$this->buildings = iterator_to_array($buildings);

		foreach ($this->buildings as $building) {
			$this->categories[$building->category][$building->id] = $building;
		}
    }

	/**
	 * Returns all buildings of a certain category
	 * or all buildings if no category is provided
	 * @param string|null $category
	 * @return array<string, Building>
	 */
	public function getBuildings(?string $category = null): array
	{
		if ($category === null) {
			return $this->buildings;
		}

		return [
			$category => $this->categories[$category] ?? [],
		];
	}

    /**
	 * Returns all buildings registered in the system
	 * that are capable of producing resources
	 *
     * @return Array<ProducingBuilding>
     */
    public function getProducer(): array
    {
        return $this->producer;
    }

	/**
	 * Returns the construction time for a building at a specific level in seconds
	 */
	public function getConstructionTime(Building $building, int $level, int $robotics, int $nanite): int
	{
		$costs = 0;

		foreach ($building->getCosts($level) as $ressource => $amount) {
			if (in_array($ressource, self::COST_FACTORS)) {
				$costs += $amount;
			}
		}

		$reduction = max(4 - ($level + 1) / 2, 1);
		$robotics = 1 + $robotics;
		$nanite = pow(2, $nanite);

		$universeSpeed = $this->optionsService->getOption('universe_speed') / 2500;

		// TODO: implement exception for buildings without speed reduction

		return $costs / (2500 * $reduction * $robotics * $nanite * $universeSpeed) * 3600;
	}
}
