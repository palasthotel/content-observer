<?php


namespace Palasthotel\WordPress\ContentObserver\Components;

use Palasthotel\WordPress\ContentObserver\Plugin;

/**
 * Class Component
 *
 * @package Palasthotel\WordPress
 * @version 0.1.1
 */
abstract class Component {

	protected Plugin $plugin;
	public function __construct(Plugin $plugin) {
		$this->plugin = $plugin;
		$this->onCreate();
	}

	/**
	 * overwrite this method in component implementations
	 */
	public function onCreate(){}
}
