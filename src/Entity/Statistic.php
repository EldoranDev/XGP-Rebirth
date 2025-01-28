<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\UserStatisticRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserStatisticRepository::class)]
class Statistic
{
    #[ORM\OneToOne(inversedBy: 'statistic', cascade: ['persist'])]
    #[ORM\JoinColumn(name: 'user_id', referencedColumnName: 'user_id')]
    #[ORM\Id]
    private ?User $user = null;

    public function __construct(
        #[ORM\Column]
        private float $buildingsPoints = 0,
        #[ORM\Column]
        private int $buildingsOldRank = 0,
        #[ORM\Column]
        private int $buildingsRank = 0,
        #[ORM\Column]
        private float $defensePoints = 0,
        #[ORM\Column]
        private int $defenseOldRank = 0,
        #[ORM\Column]
        private int $defenseRank = 0,
        #[ORM\Column]
        private float $shipsPoints = 0,
        #[ORM\Column]
        private int $shipsOldRank = 0,
        #[ORM\Column]
        private int $shipsRank = 0,
        #[ORM\Column]
        private float $technologyPoints = 0,
        #[ORM\Column]
        private int $technologyOldRank = 0,
        #[ORM\Column]
        private int $technologyRank = 0,
        #[ORM\Column]
        private float $totalPoints = 0,
        #[ORM\Column]
        private int $totalOldrank = 0,
        #[ORM\Column]
        private int $totalRank = 0,
        #[ORM\Column]
        private \DateTimeImmutable $updateTime = new \DateTimeImmutable('now'),
    ) {}

    public function getTotalPoints(): float
    {
        return $this->totalPoints;
    }

    public function getTotalRank(): int
    {
        return $this->totalRank;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        // set the owning side of the relation if necessary
        if ($user !== null && $user->getStatistic() !== $this) {
            $user->setStatistic($this);
        }

        return $this;
    }
}
