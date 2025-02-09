<?php

declare(strict_types=1);

namespace App\Entity;

use App\Doctrine\Type\BuildingQueueType;
use App\Dto\BuildingQueueItem;
use App\Repository\PlanetRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PlanetRepository::class)]
#[ORM\UniqueConstraint('UNIQ_PLANET', ['galaxy', 'system', 'position'])]
#[ORM\Table(name: '`planet`')]
class Planet
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $planetId = null;

    #[ORM\Column]
    private \DateTimeImmutable $lastUpdate;

    #[ORM\Column]
    private bool $destroyed = false;

    #[ORM\Column]
    private int $fieldsCurrent = 0;

    #[ORM\Embedded(class: PlanetEnergy::class, columnPrefix: 'energy_')]
    private PlanetEnergy $energy;

    /**
     * @var array<string, int>
     */
    #[ORM\Column(type: 'json')]
    private array $buildings = [];

    /**
     * @var array<BuildingQueueItem>
     */
    #[ORM\Column(type: BuildingQueueType::TYPE_NAME)]
    private array $buildQueue = [];

    /**
     * @param array<string, int> $resources
     * @param array<string, int> $resourcesPerHour
     */
    public function __construct(
        #[ORM\Column(length: 255)]
        private string $name,
        #[ORM\Column]
        private int $userId,
        #[ORM\Column]
        private string $image,
        #[ORM\Column(type: 'integer', enumType: PlanetType::class)]
        private PlanetType $type,
        #[ORM\Embedded(class: Coordinates::class, columnPrefix: false)]
        private Coordinates $coordinates,
        #[ORM\Embedded(class: PlanetSize::class, columnPrefix: false)]
        private PlanetSize $size,
        #[ORM\Embedded(class: PlanetTemperature::class, columnPrefix: 'temperature_')]
        private PlanetTemperature $temperature,
        #[ORM\Column(type: 'json')]
        private array $resources = [],
        #[ORM\Column(type: 'json')]
        private array $resourcesPerHour = [],
    ) {
        $this->lastUpdate = new \DateTimeImmutable();
        $this->energy = new PlanetEnergy();
    }

    public function getId(): int
    {
        return $this->planetId;
    }

    public function getCoordinates(): Coordinates
    {
        return $this->coordinates;
    }

    public function setCoordinates(Coordinates $coordinates): self
    {
        $this->coordinates = $coordinates;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return array<string, float>
     */
    public function getResources(): array
    {
        return $this->resources;
    }

    public function getResource(string $resource): int
    {
        return (int) ($this->resources[$resource] ?? 0);
    }

    public function setResource(string $resource, float $amount): self
    {
        $this->resources[$resource] = $amount;

        return $this;
    }


    public function setResourcePerHour(string $resource, int $amount): self
    {
        $this->resourcesPerHour[$resource] = $amount;

        return $this;
    }

    public function getResourcePerHour(string $resource): int
    {
        return $this->resourcesPerHour[$resource] ?? 0;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function getType(): PlanetType
    {
        return $this->type;
    }

    public function getImage(): string
    {
        return $this->image;
    }

    public function getSize(): PlanetSize
    {
        return $this->size;
    }

    public function getEnergy(): PlanetEnergy
    {
        return $this->energy;
    }

    public function getCurrentFields(): int
    {
        return $this->fieldsCurrent;
    }

    public function getTemperature(): PlanetTemperature
    {
        return $this->temperature;
    }

    public function getBuildings(): array
    {
        return $this->buildings;
    }

    public function getBuilding(string $building): int
    {
        return $this->buildings[$building] ?? 0;
    }

    public function setBuilding(string $building, int $level): self
    {
        $this->buildings[$building] = $level;

        return $this;
    }

    public function getMaxResource(string $resource): int
    {
        $storageLevel = $this->getBuilding($resource . '_storage');

        return (int) (2.5 * pow(M_E, (20 * ($storageLevel) / 33))) * 5000;
    }

    public function setLastUpdate(\DateTimeImmutable $lastUpdate): self
    {
        $this->lastUpdate = $lastUpdate;

        return $this;
    }

    public function getLastUpdate(): \DateTimeImmutable
    {
        return $this->lastUpdate;
    }

    public function getBuildQueue(): array
    {
        return $this->buildQueue;
    }

    public function addBuildingToQueue(BuildingQueueItem $item): void
    {
        $this->buildQueue[] = $item;
    }
}
