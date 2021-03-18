<?php


/**
 * Plugin Name: Content Observer
 * Plugin URI: https://github.com/palasthotel/content-observer
 * Description: Efficiently observe content changes between wordpress instances
 * Version: 1.0.0
 * Author: Palasthotel <rezeption@palasthotel.de> (in person: Edward Bock)
 * Author URI: http://www.palasthotel.de
 * Requires at least: 5.0
 * Tested up to: 5.7.0
 * Text Domain: content-observer
 * License: http://www.gnu.org/licenses/gpl-2.0.html GPLv2
 * @copyright Copyright (c) 2020, Palasthotel
 * @package Palasthotel\WordPress\ContentSync
 *
 */

namespace Palasthotel\WordPress\ContentObserver;

use Palasthotel\WordPress\ContentObserver\Logger\CLILogger;

/**
 * @property string url
 * @property string path
 * @property Repository repo
 * @property REST rest
 * @property Settings settings
 * @property Assets assets
 * @property OnPostChange $onPostChange
 * @property RemoteRequest remoteRequest
 * @property Tasks tasks
 */
class Plugin {

	const DOMAIN = "content-observer";

	const ACTION_SCHEDULE_NOTIFY_OBSERVERS = "content_observer_notify_observers";
	const ACTION_SCHEDULE_NOTIFY_OBSERVABLES = "content_observer_notify_observables";

	const SETTINGS_PAGE = "content-observer-settings";

	const REQUEST_PARAM_API_KEY = "content_observer_api_key";

	const ACTION_ON_UPDATE = "content_observer_on_update";

	const OPTION_API_KEY = "_content_observer_api_key";

	const HANDLE_SETTINGS_JS = "content-observer-settings-js";

	private function __construct() {

		/**
		 * load translations
		 */
		load_plugin_textdomain(
			Plugin::DOMAIN,
			false,
			dirname( plugin_basename( __FILE__ ) ) . '/languages'
		);

		$this->url  = plugin_dir_url( __FILE__ );
		$this->path = plugin_dir_path( __FILE__ );

		require_once dirname( __FILE__ ) . "/vendor/autoload.php";

		$this->assets        = new Assets( $this );
		$this->repo          = new Repository( $this );
		$this->rest          = new REST( $this );
		$this->settings      = new Settings( $this );
		$this->remoteRequest = new RemoteRequest( $this );
		$this->onPostChange  = new OnPostChange( $this );
		$this->tasks         = new Tasks( $this );

		if ( class_exists( "\WP_CLI" ) ) {
			$this->tasks->setLogger( new CLILogger() );
			\WP_CLI::add_command( 'content-observer', new CLI( $this ) );
		}

		register_activation_hook( __FILE__, array( $this, "activation" ) );
		register_deactivation_hook( __FILE__, array( $this, "deactivation" ) );

		if ( WP_DEBUG ) {
			$this->repo->init();
		}

	}

	/**
	 * on plugin activation
	 */
	function activation() {
		$this->repo->init();
	}

	/**
	 * on plugin deactivation
	 */
	function deactivation() {
	}

	private static $instance = null;

	/**
	 * @return Plugin
	 */
	public static function instance() {
		if ( null == static::$instance ) {
			static::$instance = new self();
		}

		return static::$instance;
	}
}

Plugin::instance();