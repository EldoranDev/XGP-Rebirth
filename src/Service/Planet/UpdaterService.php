<?php

declare(strict_types=1);

namespace App\Service\Planet;

use App\Entity\Planet;
use App\Entity\User;
use App\ExpressionLanguage\GameExpressionLanguage;
use App\Service\OptionsService;
use App\Service\ProductionService;
use App\Service\BuildingService;
use App\Service\ResourceService;
use Doctrine\ORM\EntityManagerInterface;

final readonly class UpdaterService
{
    private const string OPTION_RESOURCE_MULTIPLIER = 'resource_multiplier';
    private const string OPTION_METAL_BASIC_INCOME = 'metal_basic_income';
    private const string OPTION_CRYSTAL_BASIC_INCOME = 'crystal_basic_income';
    private const string OPTION_DEUTERIUM_BASIC_INCOME = 'deuterium_basic_income';

    public function __construct(
        private OptionsService $optionsService,
        private ProductionService $productionService,
        private BuildingService $structureService,
		private ResourceService $resourceService,
        private EntityManagerInterface $entityManager,
    ) {}

    public function updateResources(User $user, Planet $planet, \DateTimeImmutable $updateTime): void
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

        foreach ($this->structureService->getProducer() as $building) {
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

        $productionTime = $updateTime->getTimestamp() - $planet->getLastUpdate()->getTimestamp();

        $planet->setLastUpdate($updateTime);

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

        $this->entityManager->persist($planet);
        $this->entityManager->flush();
    }
}
