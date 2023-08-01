<?php


namespace Palasthotel\WordPress\ContentObserver\Logger;


use Palasthotel\WordPress\ContentObserver\Interfaces\ILogger;

class Logger implements ILogger {

	private bool $debug;

	public function __construct(bool $debug) {
		$this->debug = $debug;
	}

	private function log($msg){
		if($this->debug){
			error_log($msg);
		}
	}

	function line( $msg ) {
		$this->log($msg);
	}

	function success( $msg ) {
		$this->log($msg);
	}

	function error( $msg ) {
		error_log($msg);
	}
}
