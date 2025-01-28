<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Embeddable]
class PlanetTemperature
{
    public function __construct(
        #[ORM\Column]
        private int $min,
        #[ORM\Column]
        private int $max,
    ) {}

    public function getMin(): int
    {
        return $this->min;
    }

    public function getMax(): int
    {
        return $this->max;
    }
}
