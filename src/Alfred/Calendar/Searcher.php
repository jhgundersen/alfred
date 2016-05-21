<?php
namespace Alfred\Calendar;

use Alfred\ServiceFactory;
use DateTime;

class Searcher {

	private $service_factory;
	private $min_time;
	private $max_time;
	private $max_results = 250;

	public function __construct(ServiceFactory $service_factory) {
		$this->service_factory = $service_factory;
	}

	public function setMinTime(DateTime $date){
		$this->min_time = $date->format('c');
	}

	public function setMaxTime(DateTime $date){
		$this->max_time = $date->format('c');
	}

	/**
	 * @return array|\Google_Service_Calendar_Event[]
	 */
	public function search(){
		$calendar_service = $this->service_factory->getCalendarService();

		$results = $calendar_service->events->listEvents('primary', [
			'maxResults' => $this->max_results,
			'orderBy' => 'startTime',
			'singleEvents' => true,
			'timeMin' => $this->min_time,
			'timeMax' => $this->max_time,
		]);

		return $results->getItems();
	}

	public function setMaxResults($max_results) {
		$this->max_results = $max_results;
	}

}