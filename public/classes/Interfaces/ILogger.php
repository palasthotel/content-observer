<?php


namespace Palasthotel\WordPress\ContentObserver\Interfaces;


interface ILogger {
	function line($msg);
	function success($msg);

	function warning($msg);
	function error($msg);
}
