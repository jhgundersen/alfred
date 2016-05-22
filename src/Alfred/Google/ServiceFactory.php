<?php
namespace Alfred\Google;

use Exception;
use Google_Client;
use Google_Service_Calendar;
use Google_Service_Gmail;

class ServiceFactory extends \Alfred\ServiceFactory {

	private $account;

	public function setAccount($account){
		$this->account = $account;
	}

	public function getCalendarService(){
		return new Google_Service_Calendar($this->getAuthenticatedClient());
	}

	public function getGmailService(){
		return new Google_Service_Gmail($this->getAuthenticatedClient());
	}

	public function addAccount($account, $access_token){
		$this->configuration->setConfigFileContent("$account.json", $access_token);
	}

	public function setDefaultAccount($account){
		$this->configuration->set('default_google_account', $account);
	}

	public function getDefaultGoogleAccount() {
		return $this->configuration->get('default_google_account');
	}

	public function getAvailableAccounts(){
		$accounts = [];
		$files = $this->configuration->glob('*.json');

		foreach($files as $file){
			$accounts[] = str_replace('.json', '', basename($file));
		}

		return $accounts;
	}

	public function getClient(){
		$client = new Google_Client();
		$client->setApplicationName('Alfred');
		$client->setScopes(implode(' ', [
			Google_Service_Calendar::CALENDAR_READONLY,
			Google_Service_Gmail::GMAIL_READONLY
		]));

		$auth_config_file = $this->configuration->getConfigFile('google.key');

		if(!file_exists($auth_config_file)){
			throw new Exception("Please download JSON file from Google Developer Console and store it as '$auth_config_file'");
		}


		$client->setAuthConfigFile($auth_config_file);
		$client->setAccessType('offline');

		return $client;
	}

	public function getAuthenticatedClient(){
		$client = $this->getClient();
		$filename = "$this->account.json";
		$access_token = $this->configuration->getConfigFileContent($filename);

		if(!$access_token){
			throw new Exception("Account '$this->account' does not exists. Use 'account:register $this->account' to register to account");
		}

		$client->setAccessToken($access_token);

		if ($client->isAccessTokenExpired()) {
			$client->refreshToken($client->getRefreshToken());
			$this->configuration->setConfigFileContent($filename, $client->getAccessToken());
		}

		return $client;
	}
}