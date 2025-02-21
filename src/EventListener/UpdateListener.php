<?php

namespace App\EventListener;

use App\Entity\User;
use App\Service\Planet;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

#[AsEventListener(event: ControllerEvent::class)]
final readonly class UpdateListener
{
    public function __construct(
        private TokenStorageInterface $tokenStorage,
        private Planet\UpdaterService $planetUpdater,
        private EntityManagerInterface $entityManager,
    ) {}

    public function onKernelController(ControllerEvent $event): void
    {
        if (!$event->isMainRequest()) {
            return;
        }

        $user = $this->tokenStorage->getToken()?->getUser();

        if ($user === null) {
            return;
        }

        assert($user instanceof User);

        $this->planetUpdater->updateResources($user, $user->getCurrentPlanet());
        $this->planetUpdater->updateBuildings($user->getCurrentPlanet());

        $this->entityManager->persist($user->getCurrentPlanet());
        $this->entityManager->flush();
    }
}
