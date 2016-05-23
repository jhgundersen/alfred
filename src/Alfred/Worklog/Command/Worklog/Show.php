<?php
namespace Alfred\Worklog\Command\Worklog;

use Alfred\Worklog\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Show extends Command {

	protected function configure() {
		$this->setName('worklog:show');
		$this->setDescription('Show all logged hours');

		parent::configure();
	}

	protected function execute(InputInterface $input, OutputInterface $output) {
		$service_factory = $this->getServiceFactory();
		$server = $service_factory->getWorklogServer();
		$worklog = $service_factory->getWorklogPath();

		passthru("ssh $server $worklog show");
	}

}