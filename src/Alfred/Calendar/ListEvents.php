<?php
namespace Alfred\Calendar;

use Alfred\GoogleServiceFactory;
use DateTime;

class ListEvents {

	private $service_factory;
	private $min_time;
	private $max_time;
	private $max_results = 250;
	private $query;
	private $calendars = ['primary'];

	public function __construct(GoogleServiceFactory $service_factory) {
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

	public function setCalendars(array $calendars){
		$this->calendars = $calendars;
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

		$dates = [];
		$items = [];

		foreach($this->calendars as $calendar){
			$results = $calendar_service->events->listEvents($calendar, $options);

			foreach($results->getItems() as $item){
				$items[] = $item;
				$dates[] = $item->start->dateTime ?: $item->start->date;

			}
		}

		array_multisort($dates, $items);

		return $items;
	}

	public function setMaxResults($max_results) {
		$this->max_results = $max_results;
	}

}