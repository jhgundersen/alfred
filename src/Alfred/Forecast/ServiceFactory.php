<?php
namespace Alfred\Forecast;

use Exception;
use Forecast\Forecast;

class ServiceFactory extends \Alfred\ServiceFactory {

	private $current_location;

	public function getForecastService(){
		return new Forecast($this->getApiKey());
	}

	private function getApiKey(){
		$api_key_file = $this->configuration->getConfigFile('forecast.io.key');

		if(!file_exists($api_key_file)){
			throw new Exception("Please register an account at forecast.io and store the api key in '$api_key_file'");
		}

		return trim(file_get_contents($api_key_file));
	}

	public function setCurrentLocation($location){
		$this->current_location = $location;
	}

	public function getCurrentLocation(){
		return $this->getLocation($this->current_location);
	}

	public function setDefaultLocation($location){
		$this->configuration->set('default_forecast_location', $location);
	}

	public function getDefaultLocation() {
		return $this->configuration->get('default_forecast_location');
	}

	public function getLocation($location){
		$locations = $this->configuration->get('forecast_locations');

		if(!isset($locations[$location])){
			throw new Exception("Location '$location' is not registered");
		}

		return $locations[$location];
	}
}