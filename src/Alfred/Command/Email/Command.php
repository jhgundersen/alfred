<?php

namespace Alfred\Command\Email;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;

abstract class Command extends \Alfred\Command {

	protected function configure() {
		$this->addOption('limit', 'l', InputOption::VALUE_REQUIRED, 'Max number of messages to fetch', 10);
		$this->addOption('include-full-body', null, InputOption::VALUE_NONE, 'Include full body of email');

		parent::configure();
	}

	protected function getMaxResults(InputInterface $input){
		return $input->getOption('limit');
	}

	protected function includeFullBody(InputInterface $input){
		return $input->getOption('include-full-body');
	}
}