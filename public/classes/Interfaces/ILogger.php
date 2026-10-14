<?php


namespace Palasthotel\WordPress\ContentObserver\Interfaces;

defined( 'ABSPATH' ) || exit;


interface ILogger {
	function line($msg);
	function success($msg);

	function warning($msg);
	function error($msg);
}
