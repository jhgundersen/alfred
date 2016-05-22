<?php
namespace Alfred;

use Exception;
use Google_Client;
use Google_Service_Calendar;
use Google_Service_Gmail;

class GoogleServiceFactory {

	private $account;

	public function __construct($account){
		$this->account = $account;
	}

	public function getCalendarService(){
		return new Google_Service_Calendar($this->getAuthenticatedClient());
	}

	public function getGmailService(){
		return new Google_Service_Gmail($this->getAuthenticatedClient());
	}

	public function getAccountPath($account){
		return $this->getConfigFile("$account.json");
	}

	private function getConfigFile($filename){
		$home_dir = getenv('HOME');
		return "$home_dir/.alfred/$filename";
	}
	
	public function getClient(){
		$client = new Google_Client();
		$client->setApplicationName('Alfred');
		$client->setScopes(implode(' ', [
			Google_Service_Calendar::CALENDAR_READONLY,
			Google_Service_Gmail::GMAIL_READONLY
		]));

		$auth_config_file = $this->getConfigFile('google.key');

		if(!file_exists($auth_config_file)){
			throw new Exception("Please download JSON file from Google Developer Console and store it as '$auth_config_file'");
		}


		$client->setAuthConfigFile($auth_config_file);
		$client->setAccessType('offline');

		return $client;
	}

	public function getAuthenticatedClient(){
		$client = $this->getClient();
		$account_path = $this->getAccountPath($this->account);

		if(!file_exists($account_path)){
			throw new \Exception("Account '$this->account' does not exists. Use 'account:register $this->account' to register to account");
		}

		$client->setAccessToken(file_get_contents($account_path));

		if ($client->isAccessTokenExpired()) {
			$client->refreshToken($client->getRefreshToken());
			file_put_contents($account_path, $client->getAccessToken());
		}

		return $client;
	}
}