<?php

namespace Alfred\Command\Calendar;

use DateTime;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;

abstract class Command extends \Alfred\Command {

	protected function configure() {
		$this->addOption('start_date', null, InputOption::VALUE_REQUIRED, 'Specify which start date to use', 'now');

		parent::configure();
	}

	protected function getStartDate(InputInterface $input){
		$date = $input->getOption('start_date');

		return new DateTime($date);
	}
}