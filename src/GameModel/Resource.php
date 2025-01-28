<?php

namespace App\GameModel;

final readonly class Resource
{
	public function __construct(
		public string $id,
		public string $name,
		public string $image,
	) {
	}
}