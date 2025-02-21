<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Planet;
use App\GameModel\Resource;

final readonly class ResourceService
{
    /**
     * @var array<string, Resource>
     */
    private array $resources;

    public function __construct(
        iterable $resources,
    ) {
        $this->resources = iterator_to_array($resources);
    }

    public function getResourceIds(): array
    {
        return array_keys($this->resources);
    }

    /**
     * @param array<string, int> $requirements
     */
    public function hasResources(Planet $planet, array $requirements): bool
    {
        foreach ($requirements as $resource => $requirement) {
            if ($planet->getResource($resource) < $requirement) {
                return false;
            }
        }

        return true;
    }
}
