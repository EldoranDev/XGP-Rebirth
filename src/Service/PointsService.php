<?php

declare(strict_types=1);

namespace App\Service;

use App\GameModel\Building;

final readonly class PointsService
{
    public function getBuildingPoints(Building $building, int $level): int
    {
        $costs = $building->getCosts($level);

        $sum = 0;
        foreach ($costs as $cost) {
            $sum += $cost;
        }

        return (int) round($sum / 1000);
    }
}
