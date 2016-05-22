<?php

namespace Alfred\Google\Command\Calendar;

use DateTime;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;

abstract class Command extends \Alfred\Google\Command {

	protected function configure() {
		$this->addOption('start-date', null, InputOption::VALUE_REQUIRED, 'Specify which start date to use', 'now');
		$this->addOption('calendar', 'c', InputOption::VALUE_IS_ARRAY |InputOption::VALUE_REQUIRED, 'Specify which calendars to use', ['primary']);

		parent::configure();
	}

	protected function getStartDate(InputInterface $input){
		$date = $input->getOption('start-date');

		return new DateTime($date);
	}
}