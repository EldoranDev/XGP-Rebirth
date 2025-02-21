<?php

declare(strict_types=1);

namespace App\Twig\Extension;

use App\Entity\Planet;
use App\GameModel\Building;
use App\Service\BuildingService;
use App\Service\OptionsService;
use App\Service\ResourceService;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

final class GameExtension extends AbstractExtension
{
    public function __construct(
        private readonly ResourceService $optionsService,
        private readonly BuildingService $buildingService,
    ) {}

    public function getFilters(): array
    {
        return [
            new TwigFilter('constructionTime', [$this, 'getConstructionTime']),
        ];
    }

    public function getConstructionTime(Building $building, Planet $planet, int $level): string
    {
        $time = $this->buildingService->getConstructionTime(
            $building,
            $level,
            $planet->getBuilding('robotics_factory'),
            $planet->getBuilding('nanite_factory'),
        );

        return gmdate('H:i:s', $time);
    }
}
