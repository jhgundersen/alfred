<?php
namespace Alfred\Calendar;

use Alfred\ServiceFactory;
use DateTime;

class Searcher {

	private $service_factory;
	private $min_time;
	private $max_time;
	private $max_results = 250;
	private $query;

	public function __construct(ServiceFactory $service_factory) {
		$this->service_factory = $service_factory;
	}

	public function setMinTime(DateTime $date){
		$this->min_time = $date->format('c');
	}

	public function setMaxTime(DateTime $date){
		$this->max_time = $date->format('c');
	}

	public function setQuery($query){
		$this->query = $query;
	}

	/**
	 * @return array|\Google_Service_Calendar_Event[]
	 */
	public function search(){
		$calendar_service = $this->service_factory->getCalendarService();
		$options = [
			'maxResults' => $this->max_results,
			'orderBy' => 'startTime',
			'singleEvents' => true
		];

		if($this->min_time){
			$options['timeMin'] = $this->min_time;
		}

		if($this->max_time){
			$options['timeMax'] = $this->max_time;
		}

		if($this->query){
			$options['q'] = $this->query;
		}

		$results = $calendar_service->events->listEvents('primary', $options);

		return $results->getItems();
	}

	public function setMaxResults($max_results) {
		$this->max_results = $max_results;
	}

}