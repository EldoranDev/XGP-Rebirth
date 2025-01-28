<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Embeddable]
class PlanetEnergy
{
    public function __construct(
        #[ORM\Column]
        private int $used = 0,
        #[ORM\Column]
        private int $max = 0,
    ) {}

    public function getUsed(): int
    {
        return $this->used;
    }

    public function setUsed(int $used): self
    {
        $this->used = $used;
        return $this;
    }

    public function getMax(): int
    {
        return $this->max;
    }

    public function setMax(int $max): self
    {
        $this->max = $max;
        return $this;
    }

    public function getAvailable(): int
    {
        return $this->max + $this->used;
    }
}
