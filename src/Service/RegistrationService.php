<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Premium;
use App\Entity\User;
use App\Entity\Statistic;
use Doctrine\ORM\EntityManagerInterface;

final readonly class RegistrationService
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private Planet\LocatorService $planetLocator,
        private Planet\CreationService $planetCreator,
    ) {}

    public function createUser(User $user): User
    {
        try {
            $this->entityManager->beginTransaction();

            $this->entityManager->persist($user);

            $coordinates = $this->planetLocator->getNextFreeCoordinates();
            $planet = $this->planetCreator->createPlanet($coordinates, $user, main: true);

            $user
                ->setHomePlanet($planet)
                ->setCurrentPlanet($planet)
                ->setStatistic(new Statistic())
                ->setPremium(new Premium())
            ;

            $this->entityManager->flush();
            $this->entityManager->commit();

            return $user;
        } catch (\Exception $e) {
            $this->entityManager->rollback();

            throw $e;
        }
    }
}
