<?php

namespace App\Service;

use App\Dto\BuildingQueueItem;
use App\Entity\Planet;
use App\Enum\BuildQueueMode;
use App\Exception\BuildQueueFullException;
use App\Exception\NotEnoughResourceException;
use App\GameModel\Building;
use App\GameModel\ProducingBuilding;
use Doctrine\ORM\EntityManagerInterface;

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
		private readonly OptionsService $optionsService,
		private readonly ResourceService $resourceService,
		private readonly EntityManagerInterface $entityManager,

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

	public function getBuilding(string $building): ?Building
	{
		return $this->buildings['game.building.' . $building] ?? null;
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

	public function upgradeBuilding(Building $building, Planet $planet): void
	{
		$maxQueueSize = 1;
		$currentQueueSize = count($planet->getBuildQueue());

		dump($planet);

		$currentLevel = $planet->getBuilding($building->id);
		$resources = $building->getCosts($currentLevel + 1);
		$time = $this->getConstructionTime(
			$building,
			$currentLevel + 1,
			$planet->getBuilding('robotic_factory'),
			$planet->getBuilding('nanite_factory'),
		);

		// TODO: check if planet/moon is allowed to build this

		// TODO: implement premium checks for build queue
		if ($currentQueueSize >= $maxQueueSize) {
			throw new BuildQueueFullException();
		}

		if (!$this->resourceService->hasResources($planet, $resources)) {
			throw new NotEnoughResourceException();
		}

		// TODO: Validate fields

		// TODO: Don't upgrade buildings that are currently in use

		// TODO: Factor in Robot/Nanite Factory

		$planet->addBuildingToQueue(new BuildingQueueItem(
			$building->id,
			$currentLevel + 1,
			$time,
			time() + $time,
			BuildQueueMode::Build,
		));

		$this->entityManager->persist($planet);
		$this->entityManager->flush();
	}
}
