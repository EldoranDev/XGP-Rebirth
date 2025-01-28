<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Embeddable]
class PlanetSize
{
    public function __construct(
        #[ORM\Column]
        private int $diameter,
        #[ORM\Column]
        private int $fieldsMax,
    ) {}

    public function getDiameter(): int
    {
        return $this->diameter;
    }

    public function getFieldsMax(): int
    {
        return $this->fieldsMax;
    }
}
