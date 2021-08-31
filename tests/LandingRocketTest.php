<?php


use App\Exceptions\InvalidatePlatformCoordinatesException;
use App\Models\Enums\PositionsCodes;
use App\Models\LandingArea;
use App\Models\PlatformArea;
use App\Models\Rocket;
use App\Services\RocketLandingService;

class LandingRocketTest extends TestCase
{
    /**
     * @test
     * @runTestsInSeparateProcesses
     */
    public function will_land_rocket_in_empty_position_in_platform_successfully()
    {
        $area = new LandingArea(100, 100);
        $platform = new PlatformArea(5, 5, 10, 10, $area);
        $service = new RocketLandingService($platform);
        $this->assertEquals(PositionsCodes::getCodeText(PositionsCodes::OK),
            $service->land(new Rocket(), 5, 5));
        $this->assertEquals(PositionsCodes::getCodeText(PositionsCodes::OK),
            $service->land(new Rocket(), 7, 7));

        $platform = new PlatformArea(0, 0, 1, 1, $area);
        $service = new RocketLandingService($platform);
        $this->assertEquals(PositionsCodes::getCodeText(PositionsCodes::OK),
            $service->land(new Rocket(), 0, 0));

        $platform = new PlatformArea(100, 100, 1, 1, $area);
        $service = new RocketLandingService($platform);
        $this->assertEquals(PositionsCodes::getCodeText(PositionsCodes::OK),
            $service->land(new Rocket(), 100, 100));
    }

    /**
     * @test
     * @runInSeparateProcess
     */
    public function will_fail_if_landing_outside_landing_area()
    {
        $area = new LandingArea(100, 100);
        $platform = new PlatformArea(5, 5, 10, 10, $area);
        $service = new RocketLandingService($platform);
        $this->assertEquals(PositionsCodes::getCodeText(PositionsCodes::OUTSIDE_LANDING_AREA),
            $service->land(new Rocket(), 101, 101));
        $this->assertEquals(PositionsCodes::getCodeText(PositionsCodes::OUTSIDE_LANDING_AREA),
            $service->land(new Rocket(), 100, 101));
    }

    /**
     * @test
     * @runInSeparateProcess
     */
    public function will_fail_if_landing_outside_platform_area()
    {
        $area = new LandingArea(100, 100);
        $platform = new PlatformArea(5, 5, 10, 10, $area);
        $service = new RocketLandingService($platform);
        $this->assertEquals(PositionsCodes::getCodeText(PositionsCodes::OUTSIDE_PLATFORM_AREA),
            $service->land(new Rocket(), 15, 15));
        $this->assertEquals(PositionsCodes::getCodeText(PositionsCodes::OUTSIDE_PLATFORM_AREA),
            $service->land(new Rocket(), 20, 14));
    }

    /**
     * @test
     * @runInSeparateProcess
     */
    public function will_clash_if_landing_in_used_position()
    {
        $area = new LandingArea(100, 100);
        $platform = new PlatformArea(5, 5, 10, 10, $area);
        $service = new RocketLandingService($platform);
        $this->assertEquals(PositionsCodes::getCodeText(PositionsCodes::OK),
            $service->land(new Rocket(), 5, 5));
        $this->assertEquals(PositionsCodes::getCodeText(PositionsCodes::USED_POSITION),
            $service->land(new Rocket(), 5, 5));
    }

    /**
     * @test
     * @runInSeparateProcess
     */
    public function will_fail_if_rocket_asks_for_position_that_has_previously_been_checked_by_another_rocket()
    {
        $area = new LandingArea(100, 100);
        $platform = new PlatformArea(5, 5, 10, 10, $area);
        $service = new RocketLandingService($platform);
        $this->assertEquals(PositionsCodes::getCodeText(PositionsCodes::OK),
            $service->land(new Rocket(), 7, 7));
        $this->assertEquals(PositionsCodes::getCodeText(PositionsCodes::NEXT_TO_USED_POSITION),
            $service->land(new Rocket(), 6, 6));
        $this->assertEquals(PositionsCodes::getCodeText(PositionsCodes::NEXT_TO_USED_POSITION),
            $service->land(new Rocket(), 6, 7));
        $this->assertEquals(PositionsCodes::getCodeText(PositionsCodes::NEXT_TO_USED_POSITION),
            $service->land(new Rocket(), 6, 8));
        $this->assertEquals(PositionsCodes::getCodeText(PositionsCodes::NEXT_TO_USED_POSITION),
            $service->land(new Rocket(), 7, 6));
        $this->assertEquals(PositionsCodes::getCodeText(PositionsCodes::NEXT_TO_USED_POSITION),
            $service->land(new Rocket(), 7, 8));
        $this->assertEquals(PositionsCodes::getCodeText(PositionsCodes::NEXT_TO_USED_POSITION),
            $service->land(new Rocket(), 8, 6));
        $this->assertEquals(PositionsCodes::getCodeText(PositionsCodes::NEXT_TO_USED_POSITION),
            $service->land(new Rocket(), 8, 7));
        $this->assertEquals(PositionsCodes::getCodeText(PositionsCodes::NEXT_TO_USED_POSITION),
            $service->land(new Rocket(), 8, 8));
    }

    /**
     * @test
     * @runInSeparateProcess
     */
    public function will_fail_if_rocket_asks_for_invalid_position()
    {
        $area = new LandingArea(100, 100);
        $platform = new PlatformArea(5, 5, 10, 10, $area);
        $service = new RocketLandingService($platform);
        $this->assertEquals(PositionsCodes::getCodeText(PositionsCodes::OK),
            $service->land(new Rocket(), 5, 5));
        $this->assertEquals(PositionsCodes::getCodeText(PositionsCodes::NEXT_TO_USED_POSITION),
            $service->land(new Rocket(), 6, 6));
        $this->assertEquals(PositionsCodes::getCodeText(PositionsCodes::USED_POSITION),
            $service->land(new Rocket(), 5, 5));
        $this->assertEquals(PositionsCodes::getCodeText(PositionsCodes::OUTSIDE_PLATFORM_AREA),
            $service->land(new Rocket(), 16, 15));
        $this->assertEquals(PositionsCodes::getCodeText(PositionsCodes::OUTSIDE_LANDING_AREA),
            $service->land(new Rocket(), 5, 105));
        $this->assertEquals(PositionsCodes::getCodeText(PositionsCodes::OK),
            $service->land(new Rocket(), 7, 7));
        $this->assertEquals(PositionsCodes::getCodeText(PositionsCodes::NEXT_TO_USED_POSITION),
            $service->land(new Rocket(), 8, 6));

        $this->assertCount(5, $service->getInvalidRockets());
    }

    /**
     * @test
     * @runInSeparateProcess
     */
    public function will_throw_error_if_passing_negative_coordinates_while_creating_area()
    {
        $this->expectException(InvalidatePlatformCoordinatesException::class);

        new LandingArea(-5, -5);
    }

    /**
     * @test
     * @runInSeparateProcess
     */
    public function will_throw_error_if_platform_coordinates_is_negative()
    {
        $this->expectException(InvalidatePlatformCoordinatesException::class);

        $area = new LandingArea(100, 100);

        new PlatformArea(-1, -1, 10, 10, $area);
    }

    /**
     * @test
     * @runInSeparateProcess
     */
    public function will_throw_error_if_platform_outside_landing_area()
    {
        $this->expectException(InvalidatePlatformCoordinatesException::class);

        $area = new LandingArea(100, 100);

        new PlatformArea(99, 99, 3, 2, $area);
    }
}
