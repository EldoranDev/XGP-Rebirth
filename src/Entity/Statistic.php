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
        private int $totalOldRank = 0,
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

	public function getBuildingsRank(): int
	{
		return $this->buildingsRank;
	}

	public function setBuildingsRank(int $buildingsRank): self
	{
		$this->buildingsRank = $buildingsRank;

		return $this;
	}

	public function setTotalPoints(float $totalPoints): void
	{
		$this->totalPoints = $totalPoints;
	}

	public function setTotalRank(int $totalRank): void
	{
		$this->totalRank = $totalRank;
	}

	public function getBuildingsOldRank(): int
	{
		return $this->buildingsOldRank;
	}

	public function setBuildingsOldRank(int $buildingsOldRank): self
	{
		$this->buildingsOldRank = $buildingsOldRank;

		return $this;
	}

	public function getDefensePoints(): float
	{
		return $this->defensePoints;
	}

	public function setDefensePoints(float $defensePoints): self
	{
		$this->defensePoints = $defensePoints;

		return $this;
	}

	public function getDefenseOldRank(): int
	{
		return $this->defenseOldRank;
	}

	public function setDefenseOldRank(int $defenseOldRank): self
	{
		$this->defenseOldRank = $defenseOldRank;

		return $this;
	}

	public function getDefenseRank(): int
	{
		return $this->defenseRank;
	}

	public function setDefenseRank(int $defenseRank): self
	{
		$this->defenseRank = $defenseRank;

		return $this;
	}

	public function getShipsPoints(): float
	{
		return $this->shipsPoints;
	}

	public function setShipsPoints(float $shipsPoints): self
	{
		$this->shipsPoints = $shipsPoints;

		return $this;
	}

	public function getShipsOldRank(): int
	{
		return $this->shipsOldRank;
	}

	public function setShipsOldRank(int $shipsOldRank): self
	{
		$this->shipsOldRank = $shipsOldRank;

		return $this;
	}

	public function getShipsRank(): int
	{
		return $this->shipsRank;
	}

	public function setShipsRank(int $shipsRank): self
	{
		$this->shipsRank = $shipsRank;

		return $this;
	}

	public function getTechnologyPoints(): float
	{
		return $this->technologyPoints;
	}

	public function setTechnologyPoints(float $technologyPoints): self
	{
		$this->technologyPoints = $technologyPoints;

		return $this;
	}

	public function getTechnologyOldRank(): int
	{
		return $this->technologyOldRank;
	}

	public function setTechnologyOldRank(int $technologyOldRank): self
	{
		$this->technologyOldRank = $technologyOldRank;

		return $this;
	}

	public function getTechnologyRank(): int
	{
		return $this->technologyRank;
	}

	public function setTechnologyRank(int $technologyRank): self
	{
		$this->technologyRank = $technologyRank;

		return $this;
	}

	public function getTotalOldRank(): int
	{
		return $this->totalOldRank;
	}

	public function setTotalOldRank(int $totalOldRank): self
	{
		$this->totalOldRank = $totalOldRank;

		return $this;
	}

	public function getUpdateTime(): \DateTimeImmutable
	{
		return $this->updateTime;
	}

	public function setUpdateTime(\DateTimeImmutable $updateTime): self
	{
		$this->updateTime = $updateTime;

		return $this;
	}


	public function getBuildingsPoints(): float
	{
		return $this->buildingsPoints;
	}

	public function setBuildingsPoints(float $buildingsPoints): self
	{
		$this->buildingsPoints = $buildingsPoints;

		return $this;
	}
}
