<?php
namespace Alfred\Google\Command\Account;

use Alfred\Google\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SetDefault extends Command {

	protected function configure() {
		$this->setName('account:default');
		$this->setDescription('Register default account');
		$this->addArgument('name', InputArgument::REQUIRED, 'Name of account');
	}

	protected function execute(InputInterface $input, OutputInterface $output) {
		$account = $input->getArgument('name');

		$service_factory = $this->getServiceFactory($input);
		$service_factory->setDefaultAccount($account);

		print "Using '$account' as default account\n";
	}
}