<?php

namespace App\Service\Planet;

use App\Entity\Coordinates;
use App\Entity\Planet;
use App\Entity\PlanetSize;
use App\Entity\PlanetTemperature;
use App\Entity\PlanetType;
use App\Entity\User;
use App\Service\OptionsService;
use App\Service\ResourceService;
use Doctrine\ORM\EntityManagerInterface;

final readonly class CreationService
{
    private const int MAIN_PLANET_DIAMETER = 12800;

    public function __construct(
        private OptionsService $optionsService,
		private ResourceService $resourceService,
        private EntityManagerInterface $entityManager,
    ) {}

    public function createPlanet(
        Coordinates $coordinates,
        User $user,
        bool $main = false,
        ?string $name = null,
    ): Planet {
        $size = $this->getPlanetSize($coordinates->getPosition(), $main);
        $temp = $this->getPlanetTemperatur($coordinates->getPosition());

        // TODO: Add translations for Planet names
        $name = match ($main) {
            true => 'Homeworld',
            false => $name ?? 'Colony',
        };

		$resources = [];

		foreach ($this->resourceService->getResourceIds() as $resourceId) {
			$resources[$resourceId] = 0;
		}

        $planet = new Planet(
            name: $name,
            userId: $user->getId(),
            image: 'planet_01.webp',
            type: PlanetType::Planet,
            coordinates: $coordinates,
            size: $size,
            temperature: $temp,
			resources: $resources,
        )
        ;

        $this->entityManager->persist($planet);
        $this->entityManager->flush();

        return $planet;
    }

    protected function getPlanetSize(int $position, bool $main = false): PlanetSize
    {
        if ($main) {
            return new PlanetSize(
                self::MAIN_PLANET_DIAMETER,
                $this->optionsService->getOption('initial_fields', 163),
            );
        }
        $min = [
            9747, 9849, 9899, 11091, 12166,
            12166, 11874, 12921, 12689, 12410,
            12083, 11662, 10392, 9000, 8062,
        ];

        $max = [
            10392, 10488, 11747, 14491, 14900,
            15748, 15588, 15905, 15588, 15000,
            14318, 13416, 11000, 9644, 8602,
        ];

        $diameter = mt_rand($min[$position - 1], $max[$position - 1]);
        // TODO: replace with parameter
        $diameter *= 1;

        return new PlanetSize(
            $diameter,
            $this->calculatePlanetFields($diameter),
        );
    }

    protected function getPlanetTemperatur(int $position): PlanetTemperature
    {
        // Based on original game values
        $availableTemps = [
            1 => [220, 260],
            2 => [170, 210],
            3 => [120, 160],
            4 => [70, 110],
            5 => [60, 100],
            6 => [50, 90],
            7 => [40, 80],
            8 => [30, 70],
            9 => [20, 60],
            10 => [10, 50],
            11 => [0, 40],
            12 => [-10, 30],
            13 => [-50, -10],
            14 => [-90, -50],
            15 => [-130, -90],
        ];

        $temperature = mt_rand($availableTemps[$position][0], $availableTemps[$position][1]);

        return new PlanetTemperature(
            $temperature - 40,
            $temperature,
        );
    }

    protected function calculatePlanetFields(int $diameter): int
    {
        return (int) pow(($diameter / 1000), 2);
    }
}
