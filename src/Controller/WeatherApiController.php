<?php

namespace App\Controller;

use App\Entity\Measurement;
use App\Service\WeatherUtil;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\Routing\Attribute\Route;

final class WeatherApiController extends AbstractController
{
    #[Route('/api/v1/weather', name: 'app_weather_api', methods: ['GET'])]
    public function index(
        #[MapQueryParameter] string $country,
        #[MapQueryParameter] string $city,
        WeatherUtil $weatherUtil,
        #[MapQueryParameter] string $format = 'json',
        #[MapQueryParameter('twig')] bool $twig = false
    ): Response {
        $measurements = $weatherUtil->getWeatherForCountryAndCity(
            $country,
            $city
        );
        if (!in_array($format, ['json', 'csv'], true)) {
            return new JsonResponse([
                'error' => 'Unsupported format. Use json or csv.'
            ], 400);
        }
        if ($twig) {
            if ($format === 'json') {
                $response = $this->render(
                    'weather_api/index.json.twig',
                    [
                        'city' => $city,
                        'country' => $country,
                        'measurements' => $measurements,
                    ]
                );
                $response->headers->set(
                    'Content-Type',
                    'application/json; charset=UTF-8'
                );
                return $response;
            }
            $response = $this->render(
                'weather_api/index.csv.twig',
                [
                    'city' => $city,
                    'country' => $country,
                    'measurements' => $measurements,
                ]
            );
            return $response;
        }
        if ($format === 'json') {
            return $this->json([
                'city' => $city,
                'country' => $country,
                'measurements' => array_map(fn(Measurement $m) => [
                    'date' => $m->getDate()?->format('Y-m-d'),
                    'celsius' => $m->getTemperature(),
                    'fahrenheit' => $m->getFahrenheit(),
                    'wind' => $m->getWind(),
                    'cloudiness' => $m->getCloudiness(),
                    'precipitation' => $m->getPrecipitation(),
                ], $measurements),
            ]);
        }
        $csv = fopen('php://temp', 'r+');
        fputcsv($csv, [
            'city',
            'country',
            'date',
            'celsius',
            'fahrenheit',
            'wind',
            'cloudiness',
            'precipitation',
        ], ',', '"', '');
        foreach ($measurements as $m) {
            fputcsv($csv, [
                $city,
                $country,
                $m->getDate()?->format('Y-m-d'),
                $m->getTemperature(),
                $m->getFahrenheit(),
                $m->getWind(),
                $m->getCloudiness(),
                $m->getPrecipitation(),
            ], ',', '"', '');
        }
        rewind($csv);
        $content = stream_get_contents($csv);
        fclose($csv);
        return new Response(
            $content,
            200,
        );
    }
}
