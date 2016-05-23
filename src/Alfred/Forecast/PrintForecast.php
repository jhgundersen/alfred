<?php

namespace Alfred\Forecast;
use DateTime;
use DateTimeZone;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Output\OutputInterface;

class PrintForecast {

	public function printCurrentForecast($forecast, OutputInterface $output){
		$output->writeln(sprintf("%d°C %s(%s) %.2f m/s %s",
			$forecast->temperature,
			$this->getFormattedPrecipitation($forecast->precipIntensity),
			$this->getFormattedProbability($forecast->precipProbability),
			$forecast->windSpeed,
			$this->getWindBearing($forecast->windBearing)
		));
	}

	public function printHourlyForecast($summary, array $forecasts, OutputInterface $output){
		$lines = [];

		foreach($forecasts as $forecast){
			$date = $this->getDate($forecast);
			$column = $date->format('l');

			if(!isset($lines[$column])){
				$lines[$column] = [];
			}

			$lines[$column][] = sprintf("%s %'.02d°C %s(%s) %.2f m/s %s %s",
				$date->format('H:i'),
				$forecast->temperature,
				$this->getFormattedPrecipitation($forecast->precipIntensity),
				$this->getFormattedProbability($forecast->precipProbability),
				$forecast->windSpeed,
				$this->getWindBearing($forecast->windBearing),
				$forecast->summary
			);
		}

		$headers = array_keys($lines);

		$max = 0;
		foreach($headers as $header){
			$max = max($max, count($lines[$header]));
		}

		$rows = [];
		for($i = 0; $i < $max; $i++){
			$row = [];

			foreach($headers as $header){
				$row[] = isset($lines[$header][$i]) ? $lines[$header][$i] : '';
			}

			$rows[] = $row;
		}

		$table = new Table($output);
		$table->setHeaders($headers);
		$table->setRows($rows);
		$table->setStyle('borderless');
		$table->render();
		$output->writeln("<option=bold>Summary: $summary</>");
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

	private function getWindBearing($degree){
		if($degree < 11.25 || $degree > 348.75){
			return 'N';
		}
		elseif($degree < 33.75){
			return 'NNØ';
		}
		elseif($degree < 56.26){
			return 'NØ';
		}
		elseif($degree < 78.75){
			return 'ØNØ';
		}
		elseif($degree < 101.25){
			return 'Ø';
		}
		elseif($degree < 123.75){
			return 'ØSØ';
		}
		elseif($degree < 146.25){
			return 'SØ';
		}
		elseif($degree < 168.75){
			return 'SSØ';
		}
		elseif($degree < 191.25){
			return 'S';
		}
		elseif($degree < 213.75){
			return 'SSV';
		}
		elseif($degree < 236.25){
			return 'SV';
		}
		elseif($degree < 258.75){
			return 'VSV';
		}
		elseif($degree < 281.25){
			return 'V';
		}
		elseif($degree < 303.75){
			return 'VNV';
		}
		elseif($degree < 326.25){
			return 'NV';
		}
		else {
			return 'NNV';
		}
	}

	private function getFormattedPrecipitation($precipitation){
		return round($precipitation, 2) . "mm";
	}

	private function getFormattedProbability($probability) {
		return floor($probability * 100) . "%";
	}
}