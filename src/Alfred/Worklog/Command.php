<?php

namespace Alfred\Worklog;

abstract class Command extends \Alfred\Command {
	
	private $service_factory;

	public function __construct(ServiceFactory $service_factory){
		$this->service_factory = $service_factory;

		parent::__construct();
	}

	/**
	 * @return ServiceFactory
	 */
	protected function getServiceFactory(){
		return $this->service_factory;
	}
}