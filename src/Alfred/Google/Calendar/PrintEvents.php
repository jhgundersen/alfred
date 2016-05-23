<?php
namespace Alfred\Google\Calendar;

use DateTime;
use Symfony\Component\Console\Output\OutputInterface;

class PrintEvents {

	private $date_format;
	private $max_length;

	public function __construct($date_format) {
		$this->date_format = $date_format;
	}

	/**
	 * @param array|\Google_Service_Calendar_Event[] $events
	 * @param OutputInterface $output
	 */
	public function printEvents(array $events, OutputInterface $output){
		foreach($events as $event){
			$start = new DateTime($event->start->dateTime ?: $event->start->date);
			$end = new DateTime($event->end->dateTime ?: $event->end->date);

			$interval = $end->diff($start);
			$summary = $event->getSummary();

			if($interval->days > 1){

				if($end->format('Hi') === "0000"){
					$end->modify('-1min');
				}
				
				$line = sprintf("%s-%s %s", $start->format('D d.m'), $end->format('d.m'), $summary);
			}
			else {
				$line = sprintf("%s %s", $start->format($this->date_format), $summary);
			}

			if($this->max_length && strlen($line) > $this->max_length){
				$line = mb_strcut($line, 0, $this->max_length - 3 ) . '...';
			}

			$output->writeln($line);
		}
	}

	public function setMaxLength($max_length) {
		$this->max_length = $max_length;
	}
}