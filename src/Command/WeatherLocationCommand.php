<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Output\OutputInterface;
use App\Entity\Place;
use App\Repository\PlaceRepository;
use App\Service\WeatherUtil;

#[AsCommand(
    name: 'weather:place',
    description: 'Display the weather forecast for a location.',
)]
class WeatherLocationCommand extends Command
{
    public function __construct(
        private readonly WeatherUtil $weatherUtil,
        private readonly PlaceRepository $placeRepository,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addArgument('id', InputArgument::REQUIRED, 'Location ID');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $id = filter_var($input->getArgument('id'), FILTER_VALIDATE_INT);

        if ($id === false || $id < 1) {
            $io->error('Location ID must be a positive integer.');

            return Command::FAILURE;
        }

        $place = $this->placeRepository->find($id);

        if (!$place instanceof Place) {
            $io->error(sprintf('Location with ID %d was not found.', $id));

            return Command::FAILURE;
        }

        $measurements = $this->weatherUtil->getWeatherForLocation($place);
        $io->title(sprintf('Weather forecast for %s, %s', $place->getCity(), $place->getCountry()));

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
