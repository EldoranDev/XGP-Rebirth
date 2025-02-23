<?php

declare(strict_types=1);

namespace App\Twig;

use App\Entity\Planet;
use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

final readonly class GameVariable
{
    public function __construct(
        private TokenStorageInterface $tokenStorage,
        private UserRepository $userRepository,
    ) {}

    public function getPlanet(): Planet
    {
        $user = $this->tokenStorage->getToken()?->getUser();

        assert($user instanceof User);

        return $user->getCurrentPlanet();
    }

    /**
     * @deprecated We should most likely not count users on every request. Taken over from original code
     *
     */
    public function getUsersCount(): int
    {
        return $this->userRepository->getUserCount();
    }
}
