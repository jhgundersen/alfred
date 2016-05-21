<?php

namespace Alfred\Command\Email;

use Alfred\Email\PrintMessages;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Search extends Command {

	protected function configure() {
		$this->setName('email:search');
		$this->setDescription('Search for emails');
		$this->addArgument('query', InputArgument::REQUIRED);

		parent::configure();
	}

	protected function execute(InputInterface $input, OutputInterface $output) {
		$service_factory = $this->getServiceFactory($input);
		$gmail_service = $service_factory->getGmailService();

		$unread_messages = $gmail_service->users_messages->listUsersMessages('me', [
			'maxResults' => $this->getMaxResults($input),
			'q' => $input->getArgument('query')
		]);
		
		$printer = new PrintMessages($gmail_service);
		$printer->setIncludeFullBody($this->includeFullBody($input));
		$printer->printMessages($unread_messages->getMessages(), $output);
	}
}