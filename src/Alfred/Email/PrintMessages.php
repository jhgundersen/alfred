<?php
namespace Alfred\Email;

use DateTime;
use DateTimeZone;
use Symfony\Component\Console\Output\OutputInterface;

class PrintMessages {


	private $gmail_service;
	private $include_full_body = false;
	private $header_template = 'Date <fg=green>From</> <options=bold>Subject</>';

	public function __construct(\Google_Service_Gmail $gmail_service){
		$this->gmail_service = $gmail_service;

	}

	public function setIncludeFullBody($include_full_body){
		$this->include_full_body = $include_full_body;
	}

	public function setHeaderTemplate($header_template){
		$this->header_template = $header_template;
	}

	/**
	 * @param array|\Google_Service_Gmail_Message[] $messages
	 * @param OutputInterface $output
	 */
	public function printMessages(array $messages, OutputInterface $output){
		foreach($messages as $message){
			$message = $this->gmail_service->users_messages->get('me', $message->getId(), ['format' => 'full']);
			$payload = $message->getPayload();
			$headers = $payload->getHeaders();
			$parts = $payload->getParts();
			$body = null;
			
			if(isset($payload->body['data'])){
				$body = $payload->body['data'];
			}
			elseif(isset($parts[0]['body'])){
				$body = $parts[0]['body']->data;
			}

			$metadata = [];
			foreach($headers as $header){
				$metadata[$header->name] = $header->value;
			}

			if($this->include_full_body && $body){
				$text_message = base64_decode(strtr($body,'-_', '+/'));
			}
			else {
				$text_message = $message->getSnippet();
			}

			$date = new DateTime('@' . floor($message->getInternalDate() / 1000));

			$metadata['Date'] = $date->format('c');
			$metadata['From'] = str_replace('"', '', $metadata['From']);
			$metadata['To'] = str_replace('"', '', $metadata['To']);

			$output->writeln(strtr($this->header_template, $metadata));
			$output->writeln($text_message);
			$output->writeln('');
		}
	}
}