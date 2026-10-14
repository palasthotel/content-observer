<?php


namespace Palasthotel\WordPress\ContentObserver\Logger;


use Palasthotel\WordPress\ContentObserver\Interfaces\ILogger;

defined( 'ABSPATH' ) || exit;

class Logger implements ILogger {

	private bool $debug;

	public function __construct(bool $debug) {
		$this->debug = $debug;
	}

	private function log($msg){
		if($this->debug){
			error_log($msg); // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_error_log
		}
	}

	function line( $msg ) {
		$this->log($msg);
	}

	function success( $msg ) {
		$this->log($msg);
	}

	/*
	 * Failed syncs run in cron where nobody sees a response, so errors and
	 * warnings go to the PHP error log even without WP_DEBUG.
	 */
	function error( $msg ) {
		error_log($msg); // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_error_log
	}

	function warning( $msg ) {
		error_log($msg); // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_error_log
	}
}
