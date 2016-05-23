<?php

namespace Alfred\Google\Command\Calendar;

use Alfred\Google\Calendar\PrintEvents;
use Alfred\Google\Calendar\ListEvents;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Birthdays extends Command {

	protected function configure() {
		$this->setName('calendar:birthdays');
		$this->setDescription('Show all birthdays happening next 30 days');
		
		parent::configure();
	}

	protected function execute(InputInterface $input, OutputInterface $output) {
		$service_factory = $this->getServiceFactory($input);
		$date = $this->getStartDate($input);

		$searcher = new ListEvents($service_factory);
		$searcher->setMinTime($date);
		$searcher->setMaxTime($date->modify('+30 days'));
		$searcher->setCalendars(['#contacts@group.v.calendar.google.com']);

		$printer = new PrintEvents('D d.m');
		$printer->printEvents($searcher->search(), $output);
	}
}