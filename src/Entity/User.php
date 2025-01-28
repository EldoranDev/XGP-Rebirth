<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $userId = null;

    #[ORM\Column(length: 180)]
    private ?string $username = null;

    #[ORM\Column(length: 255)]
    private ?string $email = null;

    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column]
    private array $roles = [];

    #[ORM\OneToOne(cascade: ['persist'])]
    #[ORM\JoinColumn(referencedColumnName: 'planet_id')]
    private ?Planet $homePlanet = null;

    #[ORM\OneToOne(mappedBy: 'user', cascade: ['persist'], fetch: 'EAGER')]
    private ?Statistic $statistic = null;

    #[ORM\OneToOne(mappedBy: 'user', cascade: ['persist'], fetch: 'EAGER')]
    private ?Research $research = null;

    #[ORM\OneToOne(mappedBy: 'user', cascade: ['persist'], fetch: 'EAGER')]
    private ?Premium $premium = null;

    #[ORM\OneToOne(cascade: ['persist'], fetch: 'EAGER')]
    #[ORM\JoinColumn(referencedColumnName: 'planet_id')]
    private ?Planet $currentPlanet = null;

    #[ORM\Column]
    private bool $banned = false;

    #[ORM\Embedded(class: Coordinates::class, columnPrefix: false)]
    private Coordinates $coordinates;

    public function __construct()
    {
        $this->coordinates = new Coordinates(0, 0, 0);
    }

    public function getId(): int
    {
        return $this->userId;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(?string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }


    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function setCurrentPlanet(Planet $currentPlanet): self
    {
        $this->currentPlanet = $currentPlanet;

        return $this;
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

    public function getRoles(): array
    {
        $roles = $this->roles;

        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function eraseCredentials(): void
    {
        // TODO: Implement eraseCredentials() method.
    }

    public function getUserIdentifier(): string
    {
        return $this->email;
    }

    public function getCurrentPlanet(): Planet
    {
        return $this->currentPlanet;
    }

    public function getHomePlanet(): ?Planet
    {
        return $this->homePlanet;
    }

    public function setHomePlanet(?Planet $homePlanet): static
    {
        $this->homePlanet = $homePlanet;

        $this->setCoordinates($homePlanet->getCoordinates());

        return $this;
    }

    public function getStatistic(): ?Statistic
    {
        return $this->statistic;
    }

    public function setStatistic(Statistic $statistic): static
    {
        $this->statistic = $statistic;

        // set the owning side of the relation if necessary
        if ($statistic->getUser() !== $this) {
            $statistic->setUser($this);
        }

        return $this;
    }

    public function setPremium(Premium $premium): static
    {
        $this->premium = $premium;

        if ($premium->getUser() !== $this) {
            $premium->setUser($this);
        }


        return $this;
    }

    public function getPremium(): ?Premium
    {
        return $this->premium;
    }

    public function setResearch(Research $research): static
    {
        $this->research = $research;

        if ($research->getUser() !== $this) {
            $research->setUser($this);
        }


        return $this;
    }

    public function getResearch(): ?Research
    {
        return $this->research;
    }

    public function isBanned(): bool
    {
        return $this->banned;
    }

    public function setBanned(bool $banned): self
    {
        $this->banned = $banned;

        return $this;
    }
}
