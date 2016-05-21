<?php

namespace Alfred\Command\Calendar;

use Alfred\Calendar\Printer;
use Alfred\Calendar\Searcher;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Month extends Command {

	protected function configure() {
		$this->setName('calendar:month');
		$this->setDescription('Show all events happening this month');
		
		parent::configure();
	}

	protected function execute(InputInterface $input, OutputInterface $output) {
		$service_factory = $this->getServiceFactory($input);
		$date = $this->getStartDate($input);

		$searcher = new Searcher($service_factory);
		$searcher->setMinTime($date->modify('first day of this month'));
		$searcher->setMaxTime($date->modify('last day of this month'));

		$printer = new Printer('D d.m H:i');
		$printer->printEvents($searcher->search());
	}
}