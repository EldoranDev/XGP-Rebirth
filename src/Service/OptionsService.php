<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Option;
use App\Entity\OptionType;
use App\Repository\OptionRepository;
use Doctrine\ORM\EntityManagerInterface;

class OptionsService
{
    private array $options = [];

    public function __construct(
        private OptionRepository $optionRepository,
        private EntityManagerInterface $entityManager,
    ) {}

    public function loadOptions(array $options): void
    {
        $options = $this->optionRepository->findBy(['name' => $options]);

        foreach ($options as $option) {
            $this->options[$option->getName()] = $option->getValue();
        }
    }

    public function getOption(string $name, int|float|string|null $default = null): int|float|string|null
    {
        if (isset($this->options[$name])) {
            return $this->options[$name];
        }

        $option = $this->optionRepository->findOneBy(['name' => $name]);

        $this->options[$name] = $option?->getValue() ?? $default;

        return $this->options[$name];
    }

    public function setOption(string $name, mixed $value): void
    {
        $type = match (true) {
            is_string($value) => OptionType::STRING,
            is_float($value) => OptionType::FLOAT,
            is_int($value) => OptionType::INT,
        };

        $option = $this->optionRepository->findOneBy(['name' => $name]) ?? new Option($name, '', $type);

        $option->setValue($value);

        $this->entityManager->persist($option);
        $this->entityManager->flush();
    }
}
