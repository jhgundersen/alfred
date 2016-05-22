<?php

namespace Alfred\Forecast;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;

abstract class Command extends \Alfred\Command {
	
	private $service_factory;

	public function __construct(ServiceFactory $service_factory){
		$this->service_factory = $service_factory;

		parent::__construct();
	}

	/**
	 * @param InputInterface $input
	 * @return ServiceFactory
	 */
	protected function getServiceFactory(InputInterface $input){
		if($input->hasOption('location')){
			$this->service_factory->setCurrentLocation($input->getOption('location'));
		}

		return $this->service_factory;
	}

	protected function configure() {
		$this->addOption('location', null, InputOption::VALUE_REQUIRED, 'Specify which location to use', $this->service_factory->getDefaultLocation());
	}
}