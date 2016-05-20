<?php
namespace Alfred;

use Google_Client;
use Google_Service_Calendar;
use Google_Service_Gmail;

class ServiceFactory {

	public function getCalendarService(){
		return new Google_Service_Calendar($this->createClient());
	}

	public function getGmailService(){
		return new Google_Service_Gmail($this->createClient());
	}
	
	private function createClient(){
		$client = new Google_Client();
		$client->setApplicationName('Alfred');
		$client->setScopes(implode(' ', [
				Google_Service_Calendar::CALENDAR_READONLY,
				Google_Service_Gmail::GMAIL_READONLY
		]));

		$client->setAuthConfigFile(__DIR__ . '/../../client_secret.json');
		$client->setAccessType('offline');


		$credentials_path = '/home/jonh/.credentials/alfred.json';

		if (file_exists($credentials_path)) {
			$access_token = file_get_contents($credentials_path);
		} else {
			$authUrl = $client->createAuthUrl();
			printf("Open the following link in your browser:\n%s\n", $authUrl);
			print 'Enter verification code: ';
			$authCode = trim(fgets(STDIN));

			// Exchange authorization code for an access token.
			$access_token = $client->authenticate($authCode);

			// Store the credentials to disk.
			if(!file_exists(dirname($credentials_path))) {
				mkdir(dirname($credentials_path), 0700, true);
			}
			file_put_contents($credentials_path, $access_token);
			printf("Credentials saved to %s\n", $credentials_path);
		}
		$client->setAccessToken($access_token);

		// Refresh the token if it's expired.
		if ($client->isAccessTokenExpired()) {
			$client->refreshToken($client->getRefreshToken());
			file_put_contents($credentials_path, $client->getAccessToken());
		}

		return $client;
	}
}