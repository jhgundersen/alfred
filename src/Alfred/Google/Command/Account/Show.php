<?php
namespace Alfred\Google\Command\Account;

use Alfred\Google\Command;
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
		$accounts = $service_factory->getAvailableAccounts();

		foreach($accounts as $account){
			echo $account, "\n";
		}
	}
}