<?php
declare(strict_types=1);

namespace App\Dto;

final readonly class BuildingQueueItem
{
	public function __construct(
		public string $id,
		public int $level,
		public int $time,
		public int $endTime,
		public string $mode,
	) {}

	public static function fromArray(array $data): self
	{
		return new self(
			$data['id'],
			$data['level'],
			$data['time'],
			$data['endTime'],
			$data['mode'],
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