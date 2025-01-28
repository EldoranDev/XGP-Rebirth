<?php

namespace App\EventListener;

use App\Entity\User;
use App\Service\Planet;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

#[AsEventListener(event: ControllerEvent::class)]
final readonly class UpdateListener
{
    public function __construct(
        private TokenStorageInterface $tokenStorage,
        private Planet\UpdaterService $planetUpdater,
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

        $this->planetUpdater->updateResources($user, $user->getCurrentPlanet(), new \DateTimeImmutable('now'));
    }
}
