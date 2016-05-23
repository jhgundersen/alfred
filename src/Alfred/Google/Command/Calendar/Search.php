<?php

namespace Alfred\Google\Command\Calendar;

use Alfred\Google\Calendar\PrintEvents;
use Alfred\Google\Calendar\ListEvents;
use DateTime;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class Search extends Command {

	protected function configure() {
		$this->setName('calendar:search');
		$this->setDescription('Search for events');

		$this->addArgument('query', InputArgument::REQUIRED, 'The query to search for');
		$this->addOption('end-date', null, InputOption::VALUE_REQUIRED, 'Specify which end date to use', '+1 year');

		parent::configure();
	}

	protected function getEndDate(InputInterface $input){
		$date = $input->getOption('end-date');

		return new DateTime($date);
	}

	protected function execute(InputInterface $input, OutputInterface $output) {
		$service_factory = $this->getServiceFactory($input);

		$searcher = new ListEvents($service_factory);
		$searcher->setMinTime($this->getStartDate($input));
		$searcher->setMaxTime($this->getEndDate($input));
		$searcher->setQuery($input->getArgument('query'));
		$searcher->setCalendars($input->getOption('calendar'));

		$printer = new PrintEvents('D d.m H:i');
		$printer->printEvents($searcher->search(), $output);
	}
}