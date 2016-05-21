<?php

namespace Alfred\Command\Email;

use Alfred\Email\PrintMessages;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class Chat extends Command {

	protected function configure() {
		$this->setName('email:chat');
		$this->setDescription('View chats');

		parent::configure();
	}

	protected function execute(InputInterface $input, OutputInterface $output) {
		$service_factory = $this->getServiceFactory($input);
		$gmail_service = $service_factory->getGmailService();

		$unread_messages = $gmail_service->users_messages->listUsersMessages('me', [
			'maxResults' => $this->getMaxResults($input),
			'labelIds' => 'CHAT',
			'q' => $this->getQuery($input)
		]);

		$printer = new PrintMessages($gmail_service);
		$printer->setIncludeFullBody(true);
		$printer->setHeaderTemplate('Date <fg=green>From</>');
		$printer->printMessages($unread_messages->getMessages(), $output);
	}
}