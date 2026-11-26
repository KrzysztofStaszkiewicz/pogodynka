<?php

namespace App\Tests\Entity;

use App\Entity\Measurement;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;

class MeasurementTest extends TestCase
{
    public static function dataGetFahrenheit(): array
    {
        return [
            ['0', '32'],
            ['-100', '-148'],
            ['100', '212'],
            ['0.5', '32.9'],
            ['10.5', '50.9'],
            ['-10.5', '13.1'],
            ['25.5', '77.9'],
            ['37.5', '99.5'],
            ['-40.5', '-40.9'],
            ['100.5', '212.9'],
        ];
    }

    #[DataProvider('dataGetFahrenheit')]
    public function testGetFahrenheit(
        string $celsius,
        string $expectedFahrenheit
    ): void {
        $measurement = new Measurement();

        $measurement->setTemperature((float) $celsius);

        $this->assertEquals(
            (float) $expectedFahrenheit,
            $measurement->getFahrenheit()
        );
    }
}
