<?php
namespace Alfred;


class Configuration {

	private $path;
	private $config;
	
	public function __construct(){
		$this->path = getenv('HOME') . '/.alfred';

		if(!file_exists($this->path)) {
			mkdir($this->path, 0700, true);
		}
	}

	public function glob($pattern){
		return glob($this->path . '/' . $pattern);
	}

	public function getConfigFile($filename){
		return "$this->path/$filename";
	}

	public function getConfigFileContent($filename){
		$path = $this->getConfigFile($filename);

		if(file_exists($path)){
			return file_get_contents($path);
		}

		return false;
	}

	public function setConfigFileContent($filename, $data) {
		file_put_contents($this->getConfigFile($filename), $data);
	}

	public function get($key){
		$this->loadConfig();

		return isset($this->config[$key]) ? $this->config[$key] : null;
	}

	public function set($key, $value){
		$this->loadConfig();

		$this->config[$key] = $value;

		$this->setConfigFileContent('config', json_encode($this->config, JSON_PRETTY_PRINT));
	}

	private function loadConfig(){
		if($this->config === null) {
			$config = $this->getConfigFileContent('config');

			if ($config !== false) {
				$this->config = json_decode($config, true);
			} else {
				$this->config = [];
			}
		}
	}
}