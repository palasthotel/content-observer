<?php


namespace Palasthotel\WordPress\ContentObserver\Logger;


use Palasthotel\WordPress\ContentObserver\Interfaces\ILogger;

class CLILogger implements ILogger {

	function line( $msg ) {
		\WP_CLI::line($msg);
	}

	function success( $msg ) {
		\WP_CLI::success($msg);
	}

	function error( $msg ) {
		\WP_CLI::error($msg);
	}

	function warning( $msg ) {
		\WP_CLI::warning($msg);
	}
}
