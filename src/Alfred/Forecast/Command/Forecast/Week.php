<?php

namespace Alfred\Forecast\Command\Forecast;

use Alfred\Forecast\Command;
use Alfred\Forecast\PrintForecast;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Week extends Command {

	protected function configure() {
		$this->setName('forecast:week');
		$this->setDescription('Show forecast for next seven days');

		parent::configure();
	}

	protected function execute(InputInterface $input, OutputInterface $output) {
		$service_factory = $this->getServiceFactory($input);
		$forecast = $service_factory->getForecastService();
		$location = $service_factory->getCurrentLocation();
		
		$result = $forecast->get($location['latitude'], $location['longitude'], null, [
			'units' => 'si',
			'lang' => 'nb'
		]);

		$printer = new PrintForecast();
		$printer->printDailyForecast($result->daily->data, $output);
	}

}