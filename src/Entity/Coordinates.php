<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Embeddable()]
class Coordinates
{
    public function __construct(
        #[ORM\Column]
        private int $galaxy,
        #[ORM\Column]
        private int $system,
        #[ORM\Column]
        private int $position,
    ) {}

    public function setGalaxy(int $galaxy): self
    {
        $this->galaxy = $galaxy;

        return $this;
    }

    public function getGalaxy(): int
    {
        return $this->galaxy;
    }

    public function getSystem(): int
    {
        return $this->system;
    }

    public function setSystem(int $system): self
    {
        $this->system = $system;

        return $this;
    }

    public function getPosition(): int
    {
        return $this->position;
    }

    public function setPosition(int $position): self
    {
        $this->position = $position;

        return $this;
    }

    public function __toString(): string
    {
        return "[{$this->galaxy}:{$this->system}:{$this->position}]";
    }
}
