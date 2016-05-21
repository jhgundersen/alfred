<?php

namespace Alfred\Command\Calendar;

use DateTime;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;

abstract class Command extends \Alfred\Command {

	protected function configure() {
		$this->addOption('date', 'd', InputOption::VALUE_REQUIRED, 'Specify which date to use', 'now');

		parent::configure();
	}

	protected function getDate(InputInterface $input){
		$date = $input->getOption('date');

		return new DateTime($date);
	}
}