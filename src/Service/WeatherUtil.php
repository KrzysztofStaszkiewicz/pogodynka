<?php
declare(strict_types=1);


namespace App\Service;


use App\Entity\Place;
use App\Entity\Measurement;
use App\Repository\MeasurementRepository;
use App\Repository\PlaceRepository;


class WeatherUtil
{
    public function __construct(
        private readonly MeasurementRepository $measurementRepository,
        private readonly PlaceRepository $placeRepository,
    ) {
    }

    /**
     * @return Measurement[]
     */
    public function getWeatherForLocation(Place $place): array
    {
        return $this->measurementRepository->findByLocation($place);
    }


    /**
     * @return Measurement[]
     */
    public function getWeatherForCountryAndCity(string $country, string $cityName): array
    {
        $place = $this->placeRepository->findOneBy([
            'Country' => $country,
            'City' => $cityName,
        ]);

        if (!$place instanceof Place) {
            throw new \InvalidArgumentException(sprintf(
                'Place "%s" in country "%s" was not found.',
                $cityName,
                $country,
            ));
        }

        return $this->getWeatherForLocation($place);
    }
}
