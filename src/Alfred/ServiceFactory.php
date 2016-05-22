<?php
namespace Alfred;


class ServiceFactory {

	protected $configuration;

	public function __construct(Configuration $configuration) {
		$this->configuration = $configuration;
	}
}