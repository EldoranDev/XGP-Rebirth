<?php
declare(strict_types=1);

namespace App\Service;

use App\GameModel\Building;

final readonly class PointsService
{
	public function getBuildingPoints(Building $building, int $level): int
	{
		return 0;
	}
}