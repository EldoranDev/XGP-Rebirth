<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Statistic;
use App\Entity\User;
use App\Repository\UserStatisticRepository;
use Doctrine\ORM\EntityManagerInterface;

class StatisticsService
{
    public function __construct(
        private readonly UserStatisticRepository $userStatisticRepository,
        private readonly EntityManagerInterface $entityManager,
    ) {}

    public function updateStatistics(): void
    {
        $this->updateUserStatistics();
        $this->updateAllianceStatistics();
    }

    private function updateUserStatistics(): void
    {
        $stats = $this->userStatisticRepository->findAll();

        if (empty($stats) || count($stats) === 0) {
            return;
        }

        /** @var array<int, Statistic> $map */
        $map = [];

        $tech = [];
        $ships = [];
        $def = [];
        $build = [];
        $total = [];

        foreach ($stats as $stat) {
            $user = $stat->getUser();

            assert($user instanceof User);

            $map[$user->getId()] = $stat;

            $stat
                ->setBuildingsOldRank($stat->getBuildingsRank())
                ->setDefenseOldRank($stat->getDefenseRank())
                ->setShipsOldRank($stat->getShipsRank())
                ->setTechnologyOldRank($stat->getTechnologyRank())
                ->setTotalOldRank($stat->getTotalRank())
            ;

            $stat->setTotalPoints(
                $stat->getBuildingsPoints() +
                $stat->getDefensePoints() +
                $stat->getShipsPoints() +
                $stat->getTechnologyPoints(),
            );

            $build[$user->getId()] = $stat->getBuildingsPoints();
            $tech[$user->getId()] = $stat->getTechnologyPoints();
            $def[$user->getId()] = $stat->getDefensePoints();
            $ships[$user->getId()] = $stat->getShipsPoints();
            $total[$user->getId()] = $stat->getTotalPoints();
        }

        arsort($tech);
        arsort($ships);
        arsort($def);
        arsort($ships);
        arsort($total);

        $pos = 1;
        foreach ($tech as $id => $points) {
            $map[$id]->setTechnologyRank($pos++);
        }

        $pos = 1;
        foreach ($ships as $id => $points) {
            $map[$id]->setShipsRank($pos++);
        }

        $pos = 1;
        foreach ($build as $id => $points) {
            $map[$id]->setBuildingsRank($pos++);
        }

        $pos = 1;
        foreach ($def as $id => $points) {
            $map[$id]->setDefenseRank($pos++);
        }

        $pos = 1;
        foreach ($total as $id => $points) {
            $map[$id]->setTotalRank($pos++);
        }

        foreach ($map as $stat) {
            $this->entityManager->persist($stat);
        }

        $this->entityManager->flush();
    }

    private function updateAllianceStatistics(): void
    {
        // TODO: Implement alliance points
    }
}
