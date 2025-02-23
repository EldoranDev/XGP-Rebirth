<?php

declare(strict_types=1);

namespace App\Service\Planet;

use App\Entity\Coordinates;
use App\Exception\NoFreePlanetException;
use App\Repository\PlanetRepository;
use App\Service\OptionsService;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

final readonly class LocatorService
{
    private const string OPT_LAST_SET_GALAXY_POS = 'last_set_galaxy_pos';
    private const string OPT_LAST_SET_SYSTEM_POS = 'last_set_system_pos';
    private const string OPT_LAST_SET_PLANET_POS = 'last_set_planet_pos';

    public function __construct(
        private PlanetRepository $planetRepository,
        private OptionsService $optionsService,
        #[Autowire(param: 'game.max_system_in_galaxy')]
        private int $maxSystems,
        #[Autowire(param: 'game.max_galaxy_in_world')]
        private int $maxGalaxies,
        #[Autowire(param: 'game.separation.planet')]
        private int $planetSeparationFactor,
        #[Autowire(param: 'game.separation.system')]
        private int $systemSeparationFactor,
        #[Autowire(param: 'game.separation.galaxy')]
        private int $galaxySeparationFactor,
    ) {}

    public function getNextFreeCoordinates(?Coordinates $coordinates = null): Coordinates
    {
        if ($coordinates === null) {
            $coordinates = new Coordinates(
				(int)$this->optionsService->getOption(self::OPT_LAST_SET_GALAXY_POS, 1),
				(int)$this->optionsService->getOption(self::OPT_LAST_SET_SYSTEM_POS, 1),
				(int)$this->optionsService->getOption(self::OPT_LAST_SET_PLANET_POS, 4),
            );
        }

        if (!$this->planetRepository->doesPlanetExists($coordinates)) {
            $this->optionsService->setOption(self::OPT_LAST_SET_GALAXY_POS, $coordinates->getGalaxy());
            $this->optionsService->setOption(self::OPT_LAST_SET_SYSTEM_POS, $coordinates->getSystem());
            $this->optionsService->setOption(self::OPT_LAST_SET_PLANET_POS, $coordinates->getPosition());

            return $coordinates;
        }

        if ($coordinates->getPosition() < 12) {
            return $this->getNextFreeCoordinates($coordinates->setPosition($coordinates->getPosition() + $this->planetSeparationFactor));
        }

        if ($coordinates->getSystem() < $this->maxSystems) {
            return $this->getNextFreeCoordinates(
                $coordinates
                    ->setSystem($coordinates->getSystem() + $this->systemSeparationFactor)
                    ->setPosition(4),
            );
        }

        if ($coordinates->getGalaxy() < $this->maxGalaxies) {
            return $this->getNextFreeCoordinates(
                $coordinates
                    ->setGalaxy($coordinates->getGalaxy() + $this->galaxySeparationFactor)
                    ->setSystem(1)
                    ->setPosition(4),
            );
        }

        throw new NoFreePlanetException();
    }
}
