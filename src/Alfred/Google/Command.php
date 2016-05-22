<?php

namespace Alfred\Google;

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
		if($input->hasOption('account')){
			$this->service_factory->setAccount($input->getOption('account'));
		}

		return $this->service_factory;
	}

	protected function configure() {
		$this->addOption('account', 'a', InputOption::VALUE_REQUIRED, 'Specify which account to use', $this->service_factory->getDefaultGoogleAccount());
	}
}