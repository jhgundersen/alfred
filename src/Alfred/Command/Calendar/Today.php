<?php

namespace Alfred\Command\Calendar;

use Alfred\Calendar\Printer;
use Alfred\Calendar\Searcher;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Today extends Command {

	protected function configure() {
		$this->setName('calendar:today');
		$this->setDescription('Show all events happening today');

		parent::configure();
	}

	protected function execute(InputInterface $input, OutputInterface $output) {
		$service_factory = $this->getServiceFactory();
		$date = $this->getDate($input);

		$searcher = new Searcher($service_factory);
		$searcher->setMinTime($date->modify('midnight'));
		$searcher->setMaxTime($date->modify('tomorrow'));

		$printer = new Printer('H:i');
		$printer->printEvents($searcher->search());
	}
}