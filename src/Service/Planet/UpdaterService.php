<?php

declare(strict_types=1);

namespace App\Service\Planet;

use App\Entity\Planet;
use App\Entity\User;
use App\Enum\BuildQueueMode;
use App\ExpressionLanguage\GameExpressionLanguage;
use App\Service\OptionsService;
use App\Service\PointsService;
use App\Service\ProductionService;
use App\Service\BuildingService;
use App\Service\ResourceService;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Clock\ClockInterface;

final readonly class UpdaterService
{
    private const string OPTION_RESOURCE_MULTIPLIER = 'resource_multiplier';
    private const string OPTION_METAL_BASIC_INCOME = 'metal_basic_income';
    private const string OPTION_CRYSTAL_BASIC_INCOME = 'crystal_basic_income';
    private const string OPTION_DEUTERIUM_BASIC_INCOME = 'deuterium_basic_income';

    public function __construct(
        private OptionsService    $optionsService,
        private ProductionService $productionService,
        private BuildingService   $buildingService,
		private ResourceService   $resourceService,
		private PointsService     $pointsService,
		private ClockInterface    $clock,
		private EntityManagerInterface $entityManager,
    ) {}

    public function updateResources(User $user, Planet $planet): void
    {
        // Move this somewhere else to pre-load all always needed options
        $this->optionsService->loadOptions([
            self::OPTION_RESOURCE_MULTIPLIER,
            self::OPTION_METAL_BASIC_INCOME,
            self::OPTION_CRYSTAL_BASIC_INCOME,
            self::OPTION_DEUTERIUM_BASIC_INCOME,
        ]);

        $multiplier = $this->optionsService->getOption(self::OPTION_RESOURCE_MULTIPLIER);

		/** @var array<string, int> $baseIncome */
		$baseIncome = [];
		/** @var array<string, int> $maxResource */
		$maxResource = [];
		/** @var array<string, int> $perHour */
		$perHour = [];
		/** @var array<string, int> $plasmaBoosts*/
		$plasmaBoosts = [];

		foreach ($this->resourceService->getResourceIds() as $resourceId) {
			$baseIncome[$resourceId] = $this->optionsService->getOption($resourceId . '_basic_income') ?? 0;
			$maxResource[$resourceId] = $planet->getMaxResource($resourceId);
			$perHour[$resourceId] = 0;
		}

        if (false /* $user->getPreferences()->isVacationMode() */) {
            // TODO: set all base incomes to 0 if vacation mode is active
        }

        $maxProductionPercent = $this->productionService->getMaxProduction(
            $planet->getEnergy()->getMax(),
            $planet->getEnergy()->getUsed(),
        );

        // TODO: #2
        // https://github.com/EldoranDev/XGP-Rebirth/issues/2

        // TODO: #3
        // https://github.com/EldoranDev/XGP-Rebirth/issues/3

        $energyUsed = 0;
        $maxEnergy = 0;
		$energyBoost = 1;

        $evaluator = GameExpressionLanguage::getInstance();

        foreach ($this->buildingService->getProducer() as $building) {
            $evalParams = [
                'level' => $planet->getBuilding($building->id),
                'efficiency' => 100,
            ];

			/** @var array<string, int> $prod */
			$prod = [];

			$energyConsumption = $evaluator->evaluate($building->energy, $evalParams);

			foreach ($this->resourceService->getResourceIds() as $resourceId) {
				$prod[$resourceId] = $evaluator->evaluate($building->production[$resourceId] ?? '0', $evalParams);
				$plasmaBoosts[$resourceId] = 1;

				$perHour[$resourceId] += $this->productionService->getCurrentProduction(
					$this->productionService->getProductionAmount(
						$prod[$resourceId],
						$plasmaBoosts[$resourceId],
						$multiplier
					),
					$maxProductionPercent,
				);
			}

            if ($energyConsumption > 0) {
                $maxEnergy += $this->productionService->getProductionAmount(
                    $energyConsumption,
                    $energyBoost,
                    1,
                    true,
                );
            } else {
                $energyUsed += $this->productionService->getProductionAmount(
                    $energyConsumption,
                    1,
                    0,
                    true,
                );
            }
        }

        $planet->getEnergy()
            ->setUsed($energyUsed)
            ->setMax($maxEnergy)
        ;

        $productionTime = $this->clock->now()->getTimestamp() - $planet->getLastUpdate()->getTimestamp();

        $planet->setLastUpdate($this->clock->now());

        // TODO: Issue #1
        // https://github.com/EldoranDev/XGP-Rebirth/issues/1
        $productionLevel = 100;

		foreach ($this->resourceService->getResourceIds() as $resourceId) {
			$planet->setResourcePerHour($resourceId, $perHour[$resourceId] + $baseIncome[$resourceId]);

			if ($planet->getMaxResource($resourceId) <= $maxResource[$resourceId]) {
				$planet->setResource($resourceId, min(
					$planet->getResource($resourceId) + $productionTime * ($planet->getResourcePerHour($resourceId) / 3600) * (0.01 * $productionLevel),
					$maxResource[$resourceId]
				));
			}
		}
    }

	/**
	 * Updates the build queue of a planet and returns if a building was just finished
	 *
	 * @param Planet $planet
	 * @return bool
	 */
	public function updateBuildings(User $user, Planet $planet): bool
	{
		$queue = $planet->getBuildQueue();

		if (count($queue) === 0) {
			return false;
		}

		$queueItem = array_shift($queue);

		if ($queueItem->endTime > $this->clock->now()->getTimestamp()) {
			return false;
		}

		$currentFields = $planet->getCurrentFields();
		$maxFields = $planet->getSize()->getFieldsMax();

		$building = $this->buildingService->getBuilding($queueItem->id);

		$points = $this->pointsService->getBuildingPoints(
			$building,
			$queueItem->level,
		);

		if ($queueItem->mode === BuildQueueMode::Build) {
			$currentFields++;
			// TODO: Add support for moonbase
		} else {
			$currentFields--;
			$points *= -1;
		}

		$planet
			->setBuilding(
				$queueItem->id,
				$queueItem->level,
			)
			->setBuildingQueue($queue)
			->setCurrentFields($currentFields)
			->setBuildingPoints(
				$planet->getBuildingPoints() + $points,
			)
			->getSize()->setFieldsMax($maxFields)
		;

		$stats = $user->getStatistic();

		$stats->setBuildingsPoints(
			$stats->getBuildingsPoints() + $points,
		);

		$this->entityManager->persist($planet);
		$this->entityManager->persist($user->getStatistic());

		return true;
	}
}
