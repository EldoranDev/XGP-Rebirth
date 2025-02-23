<?php

declare(strict_types=1);

namespace App\GameModel;

class Building
{
    private float $costsFactor;
    /**
     * @var array<string, int>
     */
    private array $costsResources;

    public function __construct(
        public readonly string $id,
        public readonly string $name,
        public readonly string $category,
        public readonly string $image,
        array $costs,
    ) {
        $this->costsFactor = $costs['factor'];
        $this->costsResources = $costs['resources'];
    }

    /**
     * @return array<string, int>
     */
    public function getCosts(int $level): array
    {
        return array_map(
            fn(int $cost) => (int) ($cost * pow($this->costsFactor, ($level - 1))),
            $this->costsResources,
        );
    }
}
