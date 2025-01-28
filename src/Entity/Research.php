<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class Research
{
    #[ORM\OneToOne(inversedBy: 'research', cascade: ['persist'])]
    #[ORM\JoinColumn(name: 'user_id', referencedColumnName: 'user_id')]
    #[ORM\Id]
    private ?User $user = null;

    public function __construct(
        #[ORM\Column(nullable: true)]
        private ?string $current = null,
        #[ORM\Column]
        private int $espionageTechnology = 0,
        #[ORM\Column]
        private int $computerTechnology = 0,
        #[ORM\Column]
        private int $weaponsTechnology = 0,
        #[ORM\Column]
        private int $shieldingTechnology = 0,
        #[ORM\Column]
        private int $armorTechnology = 0,
        #[ORM\Column]
        private int $energyTechnology = 0,
        #[ORM\Column]
        private int $hyperspaceTechnology = 0,
        #[ORM\Column]
        private int $combustionDrive = 0,
        #[ORM\Column]
        private int $impulseDrive = 0,
        #[ORM\Column]
        private int $hyperspaceDrive = 0,
        #[ORM\Column]
        private int $laserTechnology = 0,
        #[ORM\Column]
        private int $ionicTechnology = 0,
        #[ORM\Column]
        private int $plasmaTechnology = 0,
        #[ORM\Column]
        private int $intergalacticResearchNetwork = 0,
        #[ORM\Column]
        private int $astrophysics = 0,
        #[ORM\Column]
        private int $gravitonTechnology = 0,
    ) {}

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        // set the owning side of the relation if necessary
        if ($user !== null && $user->getResearch() !== $this) {
            $user->setResearch($this);
        }

        return $this;
    }

    public function getEnergyTechnology(): int
    {
        return $this->energyTechnology;
    }
}
