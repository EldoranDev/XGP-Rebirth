<?php

declare(strict_types=1);

namespace App\Tests\Consistency;

use PHPUnit\Framework\Attributes\DataProvider;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class CostsTest extends KernelTestCase
{
    #[DataProvider('metalMineDataProvider')]
    public function testMetalMineCosts(
        int $level,
        array $expected,
    ): void {
        self::bootKernel();

        $solarPlant = static::getContainer()->get('game.building.metal_mine');

        self::assertEquals(
            $expected,
            $solarPlant->getCosts($level),
        );
    }

    public static function metalMineDataProvider(): iterable
    {
        yield 'level 1' => [1, ['metal' => 60, 'crystal' => 15]];
        yield 'level 2' => [2, ['metal' => 90, 'crystal' => 22]];
        yield 'level 3' => [3, ['metal' => 135, 'crystal' => 33]];
        yield 'level 10' => [10, ['metal' => 2_306, 'crystal' => 576]];
        yield 'level 20' => [20, ['metal' => 133_010, 'crystal' => 33_252]];
        yield 'level 30' => [30, ['metal' => 7_670_042, 'crystal' => 1_917_510]];
    }

    public static function solarPlantDataProvider(): iterable
    {
        yield 'level 1' => [1, ['metal' => 75, 'crystal' => 30]];
        yield 'level 2' => [2, ['metal' => 112, 'crystal' => 45]];
        yield 'level 3' => [3, ['metal' => 168, 'crystal' => 67]];
        yield 'level 10' => [10, ['metal' => 2_883, 'crystal' => 1_153]];
        yield 'level 20' => [20, ['metal' => 166_262, 'crystal' => 66_505]];
        yield 'level 30' => [30, ['metal' => 9_587_553, 'crystal' => 3_835_021]];
    }
}
