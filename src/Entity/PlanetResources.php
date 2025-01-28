<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Embeddable]
class PlanetResources
{
    public function __construct(
        #[ORM\Column]
        private float $metal = 0,
        #[ORM\Column]
        private int $metalPerHour = 0,
        #[ORM\Column]
        private float $crystal = 0,
        #[ORM\Column]
        private int $crystalPerHour = 0,
        #[ORM\Column]
        private float $deuterium = 0,
        #[ORM\Column]
        private int $deuteriumPerHour = 0,
    ) {}

    public function setMetal(float $metal): self
    {
        $this->metal = $metal;
        return $this;
    }

    public function getMetal(): float
    {
        return $this->metal;
    }

    public function getCrystal(): float
    {
        return $this->crystal;
    }

    public function setCrystal(float $crystal): self
    {
        $this->crystal = $crystal;
        return $this;
    }

    public function getDeuterium(): float
    {
        return $this->deuterium;
    }

    public function setDeuterium(float $deuterium): self
    {
        $this->deuterium = $deuterium;
        return $this;
    }

    public function getMetalPerHour(): int
    {
        return $this->metalPerHour;
    }

    public function setMetalPerHour(int $metalPerHour): self
    {
        $this->metalPerHour = $metalPerHour;
        return $this;
    }

    public function getCrystalPerHour(): int
    {
        return $this->crystalPerHour;
    }

    public function setCrystalPerHour(int $crystalPerHour): self
    {
        $this->crystalPerHour = $crystalPerHour;
        return $this;
    }

    public function getDeuteriumPerHour(): int
    {
        return $this->deuteriumPerHour;
    }

    public function setDeuteriumPerHour(int $deuteriumPerHour): self
    {
        $this->deuteriumPerHour = $deuteriumPerHour;
        return $this;
    }
}
