<?php

namespace Alfred\Google\Command\Calendar;

use Alfred\Google\Calendar\PrintEvents;
use Alfred\Google\Calendar\ListEvents;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Info extends Command {

	protected function configure() {
		$this->setName('calendar:info');
		$this->setDescription('Show info about available calendars for the account');

		parent::configure();
	}

	protected function execute(InputInterface $input, OutputInterface $output) {
		$service_factory = $this->getServiceFactory($input);
		$calendar_service = $service_factory->getCalendarService();

		$results = $calendar_service->calendarList->listCalendarList();

		foreach($results->getItems() as $item){
			printf("%s (%s)\n", $item->getSummary(), $item->id);
		}
	}
}