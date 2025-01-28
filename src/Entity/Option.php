<?php

declare(strict_types=1);

namespace App\Entity;

use App\Exception\InvalidOptionTypeException;
use App\Repository\OptionRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OptionRepository::class)]
#[ORM\Table(name: '`option`')]
class Option
{
    public function __construct(
        #[ORM\Id]
        #[ORM\Column]
        private string $name,
        #[ORM\Column]
        private string $value,
        #[ORM\Column(type: 'string', enumType: OptionType::class)]
        private OptionType $type,
    ) {}

    public function getName(): string
    {
        return $this->name;
    }

    public function setValue(mixed $value): self
    {
        $this->value = strval($value);

        return $this;
    }

    /**
     * @throws InvalidOptionTypeException
     */
    public function getValue(): mixed
    {
        switch ($this->type) {
            case OptionType::STRING:
                return $this->value;
            case OptionType::INT:
                return intval($this->value);
            case OptionType::FLOAT:
                return floatval($this->value);
        }

        throw new InvalidOptionTypeException();
    }
}
