<?php

namespace Alfred\Command\Calendar;

use Alfred\Calendar\Printer;
use Alfred\Calendar\Searcher;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Week extends Command {

	protected function configure() {
		$this->setName('calendar:week');
		$this->setDescription('Show all events happening this week');

		parent::configure();
	}

	protected function execute(InputInterface $input, OutputInterface $output) {
		$service_factory = $this->getServiceFactory();
		$date = $this->getDate($input);

		$searcher = new Searcher($service_factory);
		$searcher->setMinTime($date->modify('monday this week'));
		$searcher->setMaxTime($date->modify('monday next week'));

		$printer = new Printer('D d.m H:i');
		$printer->printEvents($searcher->search());
	}
}