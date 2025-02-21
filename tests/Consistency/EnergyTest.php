<?php

declare(strict_types=1);

namespace App\Tests\Consistency;

use App\ExpressionLanguage\GameExpressionLanguage;
use App\GameModel\ProducingBuilding;
use PHPUnit\Framework\Attributes\DataProvider;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class EnergyTest extends KernelTestCase
{
    #[DataProvider('solarPlantDataProvider')]
    public function testSolarPlantEnergyProduction(
        int $level,
        int $expected,
    ): void {
        self::bootKernel();

        $expressionLanguage = GameExpressionLanguage::getInstance();

        $solarPlant = static::getContainer()->get('game.building.solar_plant');

        assert($solarPlant instanceof ProducingBuilding);

        $this->assertSame(
            $expected,
            (int) $expressionLanguage->evaluate($solarPlant->energy, [
                'level' => $level,
                'efficiency' => 100,
            ]),
        );
    }

    #[DataProvider('metalMineDataProvider')]
    public function testMetalMineEnergyProduction(
        int $level,
        int $expected,
    ): void {
        self::bootKernel();

        $expressionLanguage = GameExpressionLanguage::getInstance();

        $metalMine = static::getContainer()->get('game.building.metal_mine');

        assert($metalMine instanceof ProducingBuilding);

        $this->assertSame(
            $expected,
            (int) $expressionLanguage->evaluate($metalMine->energy, [
                'level' => $level,
                'efficiency' => 100,
            ]),
        );
    }


    public static function solarPlantDataProvider(): iterable
    {
        yield 'level 1' => [1, 22];
        yield 'level 2' => [2, 48];
        yield 'level 3' => [3, 79];
        yield 'level 10' => [10, 518];
        yield 'level 20' => [20, 2690];
        yield 'level 30' => [30, 10469];
    }

    public static function metalMineDataProvider(): iterable
    {
        yield 'level 1' => [1, -11];
        yield 'level 2' => [2, -25];
        yield 'level 3' => [3, -40];
        yield 'level 10' => [10, -260];
        yield 'level 20' => [20, -1346];
        yield 'level 30' => [30, -5235];
    }
}
