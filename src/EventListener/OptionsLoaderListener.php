<?php

declare(strict_types=1);

namespace App\EventListener;

use App\Service\OptionsService;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpKernel\Event\RequestEvent;

#[AsEventListener(event: RequestEvent::class)]
final readonly class OptionsLoaderListener
{
    public function __construct(
        private OptionsService $optionsService,
        private array $preloadedOptions = [],
    ) {}

    public function onKernelRequest(RequestEvent $event): void
    {
        if (!$event->isMainRequest()) {
            return;
        }

        $this->optionsService->loadOptions($this->preloadedOptions);

        // TODO: validate the app is installed properly and redirect to info screen if not
    }
}
