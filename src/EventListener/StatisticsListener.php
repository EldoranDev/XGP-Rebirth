<?php
declare(strict_types=1);

namespace App\EventListener;

use App\Service\OptionsService;
use App\Service\StatisticsService;
use Psr\Clock\ClockInterface;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpKernel\Event\ControllerEvent;

#[AsEventListener(event: ControllerEvent::class, priority: -100)]
final readonly class StatisticsListener
{
	private const string OPTION_LAST_UPDATE = 'stat_last_update';
	private const string OPTION_UPDATE_TIME = 'stat_update_time';

	public function __construct(
		private OptionsService $optionsService,
		private ClockInterface $clock,
		private StatisticsService $statisticsService,
	) {}

	public function onKernelController(ControllerEvent $event): void
	{
		if (!$event->isMainRequest()) {
			return;
		}

		$updateTime = $this->optionsService->getOption(self::OPTION_UPDATE_TIME, 0);
		$lastUpdate = $this->optionsService->getOption(self::OPTION_LAST_UPDATE, 0);

		$currentTime = $this->clock->now()->getTimestamp();

		if ($currentTime - $lastUpdate < $updateTime) {
			return;
		}

		$this->statisticsService->updateStatistics();
		$this->optionsService->setOption(self::OPTION_LAST_UPDATE, $currentTime);
	}
}