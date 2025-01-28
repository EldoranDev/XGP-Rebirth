<?php

declare(strict_types=1);

namespace App\Controller\Game;

use App\Entity\User;
use App\Repository\PlanetRepository;
use App\Repository\UserStatisticRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;

#[AsController]
#[Route('/game', name: 'game_overview_')]
class OverviewController extends AbstractController
{
    public function __construct(
        private PlanetRepository $planetRepository,
        private UserStatisticRepository $userStatisticRepository,
    ) {}

    #[Route('/', name: 'index')]
    public function overviewAction(): Response
    {
        $user = $this->getUser();

        assert($user instanceof User);

        return $this->render('game/overview.html.twig', [
            'planet' => $user->getCurrentPlanet(),
            'statistics' => $user->getStatistic(),
        ]);
    }
}
