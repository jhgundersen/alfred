<?php

namespace Alfred\Command\Calendar;

use Alfred\Command;
use DateTime;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Next extends Command {

	protected function configure() {
		$this->setName('calendar:next');
		$this->setDescription("Show next entry in today's calendar");
	}

	protected function execute(InputInterface $input, OutputInterface $output) {
		$service_factory = $this->getServiceFactory();
		$calendar_service = $service_factory->getCalendarService();

		$time_min = new DateTime();
		$time_max = new DateTime('23:59');

		$results = $calendar_service->events->listEvents('primary', [
			'maxResults' => 1,
			'orderBy' => 'startTime',
			'singleEvents' => TRUE,
			'timeMin' => $time_min->format('c'),
			'timeMax' => $time_max->format('c'),
		]);

		$events = $results->getItems();
		$event = array_shift($events);

		if($event){
			$start = new DateTime($event->start->dateTime ?: $event->start->date);

			printf("%s %s\n", $start->format('H:i'), $event->getSummary());
		}
	}
}