<?php

namespace Alfred\Command\Calendar;

use Alfred\Calendar\PrintEvents;
use Alfred\Calendar\ListEvents;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Today extends Command {

	protected function configure() {
		$this->setName('calendar:today');
		$this->setDescription('Show all events happening today');

		parent::configure();
	}

	protected function execute(InputInterface $input, OutputInterface $output) {
		$service_factory = $this->getServiceFactory($input);
		$date = $this->getStartDate($input);

		$searcher = new ListEvents($service_factory);
		$searcher->setMinTime($date->modify('midnight'));
		$searcher->setMaxTime($date->modify('tomorrow'));
		$searcher->setCalendars($input->getOption('calendar'));

		$printer = new PrintEvents('H:i');
		$printer->printEvents($searcher->search());
	}
}