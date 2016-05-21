<?php

namespace Alfred\Command\Email;

use Alfred\Email\PrintMessages;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Inbox extends Command {

	protected function configure() {
		$this->setName('email:inbox');
		$this->setDescription('View emails in inbox');

		parent::configure();
	}

	protected function execute(InputInterface $input, OutputInterface $output) {
		$service_factory = $this->getServiceFactory($input);
		$gmail_service = $service_factory->getGmailService();

		$unread_messages = $gmail_service->users_messages->listUsersMessages('me', [
			'maxResults' => $this->getMaxResults($input),
			'labelIds' => 'INBOX'
		]);
		
		$printer = new PrintMessages($gmail_service);
		$printer->setIncludeFullBody($this->includeFullBody($input));
		$printer->printMessages($unread_messages->getMessages(), $output);
	}
}