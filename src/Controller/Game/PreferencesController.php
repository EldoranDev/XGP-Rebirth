<?php

declare(strict_types=1);

namespace App\Controller\Game;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;

#[AsController]
#[Route('/game/preferences', name: 'game_preferences_')]
class PreferencesController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function indexAction(): Response
    {
        // TODO: Implement
        return new Response('PREFERENCES');
    }
}
