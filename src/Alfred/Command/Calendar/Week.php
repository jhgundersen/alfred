<?php

namespace Alfred\Command\Calendar;

use Alfred\Calendar\PrintEvents;
use Alfred\Calendar\ListEvents;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Week extends Command {

	protected function configure() {
		$this->setName('calendar:week');
		$this->setDescription('Show all events happening this week');

		parent::configure();
	}

	protected function execute(InputInterface $input, OutputInterface $output) {
		$service_factory = $this->getServiceFactory($input);
		$date = $this->getStartDate($input);

		$searcher = new ListEvents($service_factory);
		$searcher->setMinTime($date->modify('monday this week'));
		$searcher->setMaxTime($date->modify('monday next week'));
		$searcher->setCalendars($input->getOption('calendar'));

		$printer = new PrintEvents('D d.m H:i');
		$printer->printEvents($searcher->search());
	}
}