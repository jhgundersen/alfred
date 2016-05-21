<?php
namespace Alfred\Command\Account;

use Alfred\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

class Register extends Command {

	protected function configure() {
		$this->setName('account:register');
		$this->setDescription('Register a new account');
		$this->addArgument('name', InputArgument::REQUIRED, 'Name of account');
	}

	protected function execute(InputInterface $input, OutputInterface $output) {
		$service_factory = $this->getServiceFactory($input);
		$account = $input->getArgument('name');

		$client = $service_factory->getClient();
		$auth_url = $client->createAuthUrl();

		printf("Open the following link in your browser:\n%s\n", $auth_url);

		$helper = $this->getHelper('question');
		$question = new Question("Enter verification code: ");

		$auth_code = $helper->ask($input, $output, $question);
		$access_token = $client->authenticate($auth_code);
		$account_path = $service_factory->getAccountPath($account);

		if(!file_exists(dirname($account_path))) {
			mkdir(dirname($account_path), 0700, true);
		}
		file_put_contents($account_path, $access_token);
	}
}