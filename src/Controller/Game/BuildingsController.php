<?php
declare(strict_types=1);

namespace App\Controller\Game;

use App\Entity\User;
use App\Exception\GameException;
use App\Service\BuildingService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/game/buildings', name: 'game_buildings_')]
class BuildingsController extends AbstractController
{
	public function __construct(
		private readonly BuildingService $buildingService,
	) {
	}

	#[Route('/resources', name: 'resources')]
	public function resourcesAction(): Response
	{
		$user = $this->getUser();

		assert($user instanceof User);

		return $this->render('game/buildings.html.twig', [
			'buildings' => $this->buildingService->getBuildings('resources')['resources'],
			'planet' => $user->getCurrentPlanet(),
		]);
	}

	#[Route('/facilities', name: 'facilities')]
	public function facilitiesAction(): Response
	{
		$user = $this->getUser();

		assert($user instanceof User);

		return $this->render('game/buildings.html.twig', [
			'buildings' => $this->buildingService->getBuildings('facilities')['facilities'],
			'planet' => $user->getCurrentPlanet(),
		]);
	}

	#[Route('/build/{buildingId}', name: 'build')]
	public function buildAction(string $buildingId): Response
	{
		$building = $this->buildingService->getBuilding($buildingId);

		if ($building === null) {
			return $this->redirectToRoute('game_overview_index');
		}

		$user = $this->getUser();
		assert($user instanceof User);

		try {
			$this->buildingService->upgradeBuilding(
				$building,
				$user->getCurrentPlanet(),
			);
		} catch (GameException $e) {
			dump($e);
		} catch (\Throwable $e) {
			// Some unknown error occurred
		}

		return $this->redirectToRoute('game_buildings_' . $building->category);
	}
}