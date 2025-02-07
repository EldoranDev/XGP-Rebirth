<?php

namespace App\Doctrine\Type;

use App\Dto\BuildingQueueItem;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

class BuildingQueueType extends Type
{
	public const string TYPE_NAME = 'queue';

	public function getName(): string
	{
		return self::TYPE_NAME;
	}

	public function getSQLDeclaration(array $column, AbstractPlatform $platform)
	{
		return $platform->getJsonTypeDeclarationSQL($column);
	}

	public function convertToPHPValue($value, AbstractPlatform $platform)
	{
		$data = json_decode($value, true);

		return array_map(fn (array $item) => BuildingQueueItem::fromArray($item), $data);
	}

	public function convertToDatabaseValue($value, AbstractPlatform $platform)
	{
		return json_encode(array_map(fn (BuildingQueueItem $item) => $item->toArray(), $value));
	}
}