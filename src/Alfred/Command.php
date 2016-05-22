<?php

namespace Alfred;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;

abstract class Command extends \Symfony\Component\Console\Command\Command {

	protected function getServiceFactory(InputInterface $input){
		$account = $input->hasOption('account') ? $input->getOption('account') : '';
		return new GoogleServiceFactory($account);
	}

	protected function configure() {
		$this->addOption('account', 'a', InputOption::VALUE_REQUIRED, 'Specify which account to use', 'default');
	}
}