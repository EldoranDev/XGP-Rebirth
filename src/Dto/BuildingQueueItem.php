<?php

declare(strict_types=1);

namespace App\Dto;

use App\Enum\BuildQueueMode;

final readonly class BuildingQueueItem
{
    public function __construct(
        public string $id,
        public int $level,
        public int $time,
        public int $endTime,
        public BuildQueueMode $mode,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            $data['id'],
            $data['level'],
            $data['time'],
            $data['endTime'],
            BuildQueueMOde::from($data['mode']),
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'level' => $this->level,
            'time' => $this->time,
            'endTime' => $this->endTime,
            'mode' => $this->mode,
        ];
    }
}
