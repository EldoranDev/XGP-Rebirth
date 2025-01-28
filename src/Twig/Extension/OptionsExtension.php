<?php

declare(strict_types=1);

namespace App\Twig\Extension;

use App\Entity\Planet;
use App\Service\OptionsService;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

final class OptionsExtension extends AbstractExtension
{
    public function __construct(
        private readonly OptionsService $optionsService,
    ) {}

    public function getFilters(): array
    {
        return [
            new TwigFilter('option', [$this, 'getOptionValue']),
            new TwigFilter('planet', [$this, 'formatPlanet']),
        ];
    }

    public function getOptionValue(string $optionKey, mixed $default = null): mixed
    {
        return $this->optionsService->getOption($optionKey, $default);
    }

    public function formatPlanet(Planet $planet, string $format = 'N c'): string
    {
        return "{$planet->getName()} - {$planet->getCoordinates()}";
    }
}
