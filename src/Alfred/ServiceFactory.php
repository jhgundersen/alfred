<?php
namespace Alfred;

use Google_Client;
use Google_Service_Calendar;
use Google_Service_Gmail;

class ServiceFactory {

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
		$home_dir = getenv('HOME');
		return "$home_dir/.alfred/$account.json";
	}
	
	public function getClient(){
		$client = new Google_Client();
		$client->setApplicationName('Alfred');
		$client->setScopes(implode(' ', [
			Google_Service_Calendar::CALENDAR_READONLY,
			Google_Service_Gmail::GMAIL_READONLY
		]));

		$client->setAuthConfigFile(__DIR__ . '/../../client_secret.json');
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