<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use App\Service\WeatherUtil;

#[AsCommand(
    name: 'weather:country-city',
    description: 'Display the weather forecast for a country and city.',
)]
class WeatherCountryCityCommand extends Command
{
    public function __construct(
        private readonly WeatherUtil $weatherUtil,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('country', InputArgument::REQUIRED, 'Country code, e.g. PL')
            ->addArgument('city', InputArgument::REQUIRED, 'City name');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $country = strtoupper(trim((string) $input->getArgument('country')));
        $city = trim((string) $input->getArgument('city'));

        if ($country === '' || $city === '') {
            $io->error('Both country code and city name must be provided.');

            return Command::FAILURE;
        }

        try {
            $measurements = $this->weatherUtil->getWeatherForCountryAndCity($country, $city);
        } catch (\InvalidArgumentException $exception) {
            $io->error($exception->getMessage());

            return Command::FAILURE;
        }

        $io->title(sprintf('Weather forecast for %s, %s', $city, $country));

        if ($measurements === []) {
            $io->warning('No forecast measurements are available for this location.');

            return Command::SUCCESS;
        }

        $io->table(
            ['Date', 'Temperature (°C)', 'Wind (km/h)', 'Cloudiness', 'Precipitation (%)'],
            array_map(static fn ($measurement): array => [
                $measurement->getDate()?->format('Y-m-d') ?? '',
                $measurement->getTemperature(),
                $measurement->getWind(),
                $measurement->getCloudiness(),
                $measurement->getPrecipitation(),
            ], $measurements),
        );

        return Command::SUCCESS;
    }
}
