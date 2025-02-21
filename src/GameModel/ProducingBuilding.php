<?php

declare(strict_types=1);

namespace App\GameModel;

class ProducingBuilding extends Building
{
    /**
     * @param array<string, string> $production
     */
    public function __construct(
        string $id,
        string $name,
        string $category,
        string $image,
        array $costs,
        public string $energy,
        public readonly array $production,
    ) {
        parent::__construct(
            id: $id,
            name: $name,
            category: $category,
            image: $image,
            costs: $costs,
        );
    }
}
