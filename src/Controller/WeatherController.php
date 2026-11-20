<?php

namespace App\Controller;

use App\Entity\Place;
use App\Repository\PlaceRepository;
use App\Service\WeatherUtil;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
//z ta funkcja pomagala sztuczna inteligencja
final class WeatherController extends AbstractController
{
    #[Route(
        '/weather/{city}/{country}',
        name: 'app_weather',
        defaults: ['country' => null]
    )]
    public function city(
        string $city,
        WeatherUtil $util,
        PlaceRepository $placeRepository,
        ?string $country = null
    ): Response {
        $criteria = ['City' => $city];

        if ($country !== null) {
            $criteria['Country'] = $country;
        }

        $place = $placeRepository->findOneBy($criteria);

        if (!$place instanceof Place) {
            throw $this->createNotFoundException(
                'Nie znaleziono miejscowości.'
            );
        }

        $measurements = $util->getWeatherForLocation($place);

        return $this->render('weather/city.html.twig', [
            'place' => $place,
            'measurements' => $measurements,
        ]);
    }
}
