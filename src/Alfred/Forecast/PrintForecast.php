<?php

namespace Alfred\Forecast;
use DateTime;
use DateTimeZone;
use Symfony\Component\Console\Output\OutputInterface;

class PrintForecast {

	public function printCurrentForecast($forecast, OutputInterface $output){
		$output->writeln(sprintf("%d°C %s(%s) %s",
			$forecast->temperature,
			$this->getFormattedPrecipitation($forecast->precipIntensity),
			$this->getFormattedProbability($forecast->precipProbability),
			$forecast->summary
		));
	}

	public function printHourlyForecast(array $forecasts, OutputInterface $output){
		foreach($forecasts as $forecast){
			$date = $this->getDate($forecast);

			$output->writeln(sprintf("%s %d°C %s(%s) %s",
				$date->format('D H:i'),
				$forecast->temperature,
				$this->getFormattedPrecipitation($forecast->precipIntensity),
				$this->getFormattedProbability($forecast->precipProbability),
				$forecast->summary
			));
		}
	}

	public function printDailyForecast(array $forecasts, OutputInterface $output){
		foreach($forecasts as $forecast){
			$date = $this->getDate($forecast);

			$output->writeln(sprintf("%s %'.02d-%'.02d°C %s-%s(%s) %s",
				$date->format('D d.m'),
				$forecast->temperatureMin,
				$forecast->temperatureMax,
				round($forecast->precipIntensity, 2),
				$this->getFormattedPrecipitation($forecast->precipIntensityMax),
				$this->getFormattedProbability($forecast->precipPropability),
				$forecast->summary
			));
		}
	}

	private function getDate($forecast){
		$date = new DateTime('@' . $forecast->time);
		$date->setTimezone(new DateTimeZone('Europe/Oslo'));

		return $date;
	}

	private function getIcon($forecast){
		switch($forecast->icon) {
			case 'clear-day':
				return "☼";
			case 'clear-night':
				return "☾";
			case 'rain':
				return "☔";
			case 'snow':
				return "❄";
			case 'sleet':
			case 'wind':
			case 'fog':
			case 'cloudy':
				return "☁";
			case 'partly-cloudy-day':
				return "☁";
			case 'partly-cloudy-night':
				return "☁";
		}
	}

	private function getFormattedPrecipitation($precipitation){
		return round($precipitation, 2) . "mm";
	}

	private function getFormattedProbability($probability) {
		return floor($probability * 100) . "%";
	}
}