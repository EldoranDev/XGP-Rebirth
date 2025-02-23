<?php

declare(strict_types=1);

namespace App\Twig\Extension;

use App\Entity\Planet;
use App\GameModel\Building;
use App\Service\BuildingService;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

final class GameExtension extends AbstractExtension
{
    public function __construct(
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
