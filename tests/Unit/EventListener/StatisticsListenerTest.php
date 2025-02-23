<?php

namespace App\Tests\Unit\EventListener;

use App\EventListener\StatisticsListener;
use App\Service\OptionsService;
use App\Service\StatisticsService;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Clock\MockClock;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\KernelInterface;

class StatisticsListenerTest extends TestCase
{
    private StatisticsListener $listener;
    private MockClock $clock;
    private StatisticsService|MockObject $statisticsService;
    private OptionsService|MockObject $optionsService;

    public function setUp(): void
    {
        parent::setUp();

        $this->clock = new MockClock();

        $this->optionsService = $this->createMock(OptionsService::class);
        $this->statisticsService = $this->createMock(StatisticsService::class);

        $this->listener = new StatisticsListener(
            $this->optionsService,
            $this->clock,
            $this->statisticsService,
        );
    }

    public function testWillNotUpdateStatisticsWhenJustUpdated(): void
    {
        $lastUpdate = $this->clock->now()->getTimestamp();
        $updateTime = 900;

        $this->optionsService->method('getOption')->willReturnCallback(fn($option) => match ($option) {
            'stat_last_update' => $lastUpdate,
            'stat_update_time' => $updateTime,
        });

        // Move the Time forward but make sure it stays before the required update time
        $this->clock->modify("+{$updateTime} seconds");
        $this->clock->modify('-10 seconds');

        $this->statisticsService->expects($this->never())->method('updateStatistics');

        $this->listener->onKernelController($this->getEvent());
    }

    public function testWillUpdateStatisticsAfterExpectedTime(): void
    {
        $lastUpdate = $this->clock->now()->getTimestamp();
        $updateTime = 900;

        $this->optionsService->method('getOption')->willReturnCallback(fn($option) => match ($option) {
            'stat_last_update' => $lastUpdate,
            'stat_update_time' => $updateTime,
        });

        // Move the Time forward (Update Time + 10s buffer)
        $this->clock->modify("+{$updateTime} seconds");
        $this->clock->modify('+10 seconds');

        $this->statisticsService->expects($this->once())->method('updateStatistics');

        $this->listener->onKernelController($this->getEvent());
    }

    private function getEvent(): ControllerEvent
    {
        return new ControllerEvent(
            $this->createMock(KernelInterface::class),
            fn() => new Response(null),
            new Request(),
            HttpKernelInterface::MAIN_REQUEST,
        );
    }
}
