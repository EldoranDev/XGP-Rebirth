<?php

namespace App\Repository;

use App\Entity\Coordinates;
use App\Entity\Planet;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Planet>
 */
class PlanetRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Planet::class);
    }

    public function doesPlanetExists(Coordinates $coordinates): bool
    {
        $query = $this->createQueryBuilder('p')
            ->select('1')
            ->where('p.coordinates.galaxy = :galaxy')
            ->andWhere('p.coordinates.system = :system')
            ->andWhere('p.coordinates.position = :position')
            ->setParameter('galaxy', $coordinates->getGalaxy())
            ->setParameter('system', $coordinates->getSystem())
            ->setParameter('position', $coordinates->getPosition())
            ->getQuery();

        return $query->getOneOrNullResult() !== null;
    }
}
