<?php
namespace Alfred\Calendar;

use DateTime;

class PrintEvents {

	private $date_format;

	public function __construct($date_format) {
		$this->date_format = $date_format;
	}

	/**
	 * @param array|\Google_Service_Calendar_Event[] $events
	 */
	public function printEvents(array $events){
		foreach($events as $event){
			$start = new DateTime($event->start->dateTime ?: $event->start->date);
			$end = new DateTime($event->end->dateTime ?: $event->end->date);

			$interval = $end->diff($start);
			$summary = $event->getSummary();

			if($interval->days > 1){
				printf("%s-%s %s\n", $start->format('D d.m'), $end->format('d.m'), $summary);
			}
			else {
				printf("%s %s\n", $start->format($this->date_format), $summary);
			}
		}
	}
}