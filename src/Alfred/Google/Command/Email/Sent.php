<?php

namespace Alfred\Google\Command\Email;

use Alfred\Google\Email\PrintMessages;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Sent extends Command {

	protected function configure() {
		$this->setName('email:sent');
		$this->setDescription('View sent emails');

		parent::configure();
	}

	protected function execute(InputInterface $input, OutputInterface $output) {
		$service_factory = $this->getServiceFactory($input);
		$gmail_service = $service_factory->getGmailService();

		$unread_messages = $gmail_service->users_messages->listUsersMessages('me', [
			'maxResults' => $this->getMaxResults($input),
			'labelIds' => 'SENT',
			'q' => $this->getQuery($input)
		]);
		
		$printer = new PrintMessages($gmail_service);
		$printer->setIncludeFullBody($this->includeFullBody($input));
		$printer->setHeaderTemplate('Date <fg=green>To</> <options=bold>Subject</>');
		$printer->printMessages($unread_messages->getMessages(), $output);
	}
}