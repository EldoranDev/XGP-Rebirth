<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class Premium
{
    #[ORM\OneToOne(inversedBy: 'premium', cascade: ['persist'])]
    #[ORM\JoinColumn(name: 'user_id', referencedColumnName: 'user_id')]
    #[ORM\Id]
    private ?User $user = null;

    public function __construct(
        #[ORM\Column]
        private int $darkMatter = 0,
        #[ORM\Column]
        private int $officierCommander = 0,
        #[ORM\Column]
        private int $officierAdmiral = 0,
        #[ORM\Column]
        private int $officierEngineer = 0,
        #[ORM\Column]
        private int $officierGeologist = 0,
        #[ORM\Column]
        private int $officierTechnocrat = 0,
    ) {}

    public function setUser(User $user): self
    {
        $this->user = $user;

        // set the owning side of the relation if necessary
        if ($user->getPremium() !== $this) {
            $user->setPremium($this);
        }

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function getDarkMatter(): int
    {
        return $this->darkMatter;
    }
}
