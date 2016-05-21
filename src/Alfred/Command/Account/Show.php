<?php
namespace Alfred\Command\Account;

use Alfred\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Show extends Command {

	protected function configure() {
		$this->setName('account:show');
		$this->setDescription('Show registered accounts');

		parent::configure();
	}

	protected function execute(InputInterface $input, OutputInterface $output) {
		$service_factory = $this->getServiceFactory($input);
		$account_path = $service_factory->getAccountPath('file');

		foreach(glob(dirname($account_path) . '/*.json') as $filename){
			print str_replace('.json', '', basename($filename));


			if(is_link($filename)){
				print ' -> ' . str_replace('.json', '',basename(readlink($filename)));
			}

			print "\n";
		}
	}
}