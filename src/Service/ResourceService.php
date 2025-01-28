<?php
declare(strict_types=1);

namespace App\Service;

use App\GameModel\Resource;

final readonly class ResourceService
{
	/**
	 * @var array<string, Resource>
	 */
	private array $resources;

	public function __construct(
		iterable $resources,
	) {
		$this->resources = iterator_to_array($resources);
	}

	public function getResourceIds(): array
	{
		return array_keys($this->resources);
	}
}