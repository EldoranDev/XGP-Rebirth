<?php

declare(strict_types=1);

namespace App\Service;

class ProductionService
{
    public function getMaxProduction(int $maxEnergy, int $energyUsed): int
    {
        $percentage = 100;

        if ($maxEnergy === 0 && $energyUsed > 0) {
            $percentage = 0;
        }

        if ($maxEnergy > 0 && $energyUsed + $maxEnergy < 0) {
            $percentage = floor($maxEnergy / ($energyUsed * -1) * 100);
        }

        return (int) min($percentage, 100);
    }

    public function getProductionAmount(float $production, int $boost, float $mult = 0, bool $isEnergy = false): int
    {
        if ($isEnergy) {
            return (int) floor($production * $boost);
        }

        return (int) floor($production * $mult * $boost);
    }

    public function getCurrentProduction(float $amount, int $factor): int
    {
        return (int) ($amount * 0.01 * $factor);
    }
}
