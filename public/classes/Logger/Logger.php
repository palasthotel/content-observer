<?php


namespace Palasthotel\WordPress\ContentObserver\Logger;


use Palasthotel\WordPress\ContentObserver\Interfaces\ILogger;

/**
 * @property bool debug
 */
class Logger implements ILogger {

	/**
	 * DebugLogger constructor.
	 *
	 * @param bool $debug
	 */
	public function __construct($debug) {
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