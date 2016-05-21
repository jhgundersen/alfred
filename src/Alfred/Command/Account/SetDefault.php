<?php
namespace Alfred\Command\Account;

use Alfred\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

class SetDefault extends Command {

	protected function configure() {
		$this->setName('account:default');
		$this->setDescription('Register default account');
		$this->addArgument('name', InputArgument::REQUIRED, 'Name of account');
	}

	protected function execute(InputInterface $input, OutputInterface $output) {
		$service_factory = $this->getServiceFactory($input);
		$account = $input->getArgument('name');

		$default_path = $service_factory->getAccountPath('default');
		$new_default_path = $service_factory->getAccountPath($account);

		if(!file_exists($new_default_path)){
			throw new \Exception("Account '$account' does not exists");
		}

		if(file_exists($default_path)){
			unlink($default_path);
		}

		symlink(basename($new_default_path), $default_path);

		print "Using '$account' as default account\n";
	}
}