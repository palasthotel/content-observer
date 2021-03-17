<?php


namespace Palasthotel\WordPress\ContentObserver;


/**
 * @property Plugin plugin
 */
abstract class _Component {

	public function __construct(Plugin $plugin ) {
		$this->plugin = $plugin;
		$this->onCreate();
	}

	function onCreate(){

	}
}