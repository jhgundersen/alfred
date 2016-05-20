<?php

namespace Alfred;

abstract class Command extends \Symfony\Component\Console\Command\Command {

	private $service_factory;

	public function __construct(ServiceFactory $service_factory) {
		parent::__construct(null);

		$this->service_factory = $service_factory;
	}

	protected function getServiceFactory(){
		return $this->service_factory;
	}
}