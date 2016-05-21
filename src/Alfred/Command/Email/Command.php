<?php

namespace Alfred\Command\Email;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;

abstract class Command extends \Alfred\Command {

	protected function configure() {
		$this->addOption('limit', 'l', InputOption::VALUE_REQUIRED, 'Max number of messages to fetch', 10);
		$this->addOption('full-body', null, InputOption::VALUE_NONE, 'Include full body of email');
		$this->addOption('query', null, InputOption::VALUE_REQUIRED, 'Search for something');

		parent::configure();
	}

	protected function getMaxResults(InputInterface $input){
		return $input->getOption('limit');
	}

	protected function includeFullBody(InputInterface $input){
		return $input->getOption('full-body');
	}

	protected function getQuery(InputInterface $input){
		return $input->getOption('query');
	}
}