<?php

namespace Alfred;

use Symfony\Component\Console\Input\InputOption;

abstract class Command extends \Symfony\Component\Console\Command\Command {

	private $service_factory;

	public function __construct(ServiceFactory $service_factory) {
		parent::__construct(null);

		$this->service_factory = $service_factory;
	}

	protected function getServiceFactory(){
		return $this->service_factory;
	}

	protected function configure() {
		$this->addOption('account', 'a', InputOption::VALUE_REQUIRED, 'Specify which account to use');
	}
}