<?php

namespace Alfred\Command\Email;

use Alfred\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Unread extends Command {

	protected function configure() {
		$this->setName('email:unread');
		$this->setDescription('Count number of unread emails in inbox');

		parent::configure();
	}

	protected function execute(InputInterface $input, OutputInterface $output) {
		$service_factory = $this->getServiceFactory();
		$gmail_service = $service_factory->getGmailService();

		$unread_messages = $gmail_service->users_messages->listUsersMessages('me', [
			'maxResults' => 100,
			'labelIds' => 'INBOX',
			'q' => 'is:unread'
		]);

		print count($unread_messages->getMessages()) . "\n";
	}
}