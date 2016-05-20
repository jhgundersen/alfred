<?php

namespace Alfred\Command\Calendar;

use Alfred\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Week extends Command {

	protected function configure() {
		$this->setName('calendar:week');
		$this->setDescription('Yay');
	}

	protected function execute(InputInterface $input, OutputInterface $output) {
		print "hello from calendar";
	}
}