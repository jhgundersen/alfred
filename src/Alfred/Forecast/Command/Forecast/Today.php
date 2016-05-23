<?php

namespace Alfred\Forecast\Command\Forecast;

use Alfred\Forecast\Command;
use Alfred\Forecast\PrintForecast;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Today extends Command {

	protected function configure() {
		$this->setName('forecast:today');
		$this->setDescription('Show forecast for coming hours');

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
		$printer->printHourlyForecast($result->hourly->summary, $result->hourly->data, $output);
	}

}