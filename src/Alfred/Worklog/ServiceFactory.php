<?php
namespace Alfred\Worklog;

use Exception;

class ServiceFactory extends \Alfred\ServiceFactory {

	public function getWorklogServer(){
		$server = $this->configuration->get('worklog_server');

		if(!$server){
			throw new Exception("Please specify 'worklog_server' in your config file");
		}

		return $server;
	}

	public function getWorklogPath(){
		return $this->configuration->get('worklog_path') ?: 'worklog';
	}
}