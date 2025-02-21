<?php

namespace App\Controller\Game;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;

#[AsController]
#[Route('/game/info', name: 'game_info_')]
class InfoController extends AbstractController
{
    #[Route('/building/{id}', name: 'building')]
    public function buildingInfo(): Response
    {
        return new Response("BUILDING INFO");
    }
}
