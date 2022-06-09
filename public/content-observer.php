<?php


/**
 * Plugin Name: Content Observer
 * Plugin URI: https://github.com/palasthotel/content-observer
 * Description: Efficiently observe content changes between wordpress instances
 * Version: 1.1.0
 * Author: Palasthotel <rezeption@palasthotel.de> (in person: Edward Bock)
 * Author URI: http://www.palasthotel.de
 * Requires at least: 5.0
 * Tested up to: 5.9.0
 * Text Domain: content-observer
 * License: http://www.gnu.org/licenses/gpl-2.0.html GPLv2
 * @copyright Copyright (c) 2022, Palasthotel
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
 * @property Schedule schedule
 */
class Plugin {

	const DOMAIN = "content-observer";

	// ----------------------------------------------------------------------
	// filters
	// ----------------------------------------------------------------------
	const FILTER_REMOTE_REQUEST_HEADER = "content_observer_remote_request_header";

	// ----------------------------------------------------------------------
	// actions
	// ----------------------------------------------------------------------
	const ACTION_ON_MODIFICATIONS = "content_observer_on_modifications";
	const ACTION_ON_SITE_MODIFICATIONS = "content_observer_on_modifications_%d";

	// ----------------------------------------------------------------------
	// schedules
	// ----------------------------------------------------------------------
	const ACTION_SCHEDULE_NOTIFY_OBSERVERS = "content_observer_notify_observers"; // notify external sites
	const ACTION_SCHEDULE_START_MODIFICATIONS_HOOK = "content_observer_start_modifications_hook"; // notify internally

	// ----------------------------------------------------------------------
	// options
	// ----------------------------------------------------------------------
	const OPTION_API_KEY = "_content_observer_api_key";
	const OPTION_SCHEDULE_NOTIFY_OBSERVERS_IS_DISABLED = "_content_observer_schedule_notify_observers_is_disabled";
	const OPTION_SCHEDULE_START_MODIFICATION_HOOK_IS_DISABLED = "_content_observer_schedule_start_modification_hook_is_disabled";
	const OPTION_LAST_MODIFICATIONS_HOOK_RUN = "_content_observer_last_modifications_hook_run";

	// ----------------------------------------------------------------------
	// asset handles
	// ----------------------------------------------------------------------
	const HANDLE_MODIFICATIONS_JS = "content-observer-modifications-js";
	const HANDLE_SETTINGS_JS = "content-observer-settings-js";

	// ----------------------------------------------------------------------
	// other constants
	// ----------------------------------------------------------------------
	const MODIFICATIONS_PAGE = "content-observer";
	const MANAGE_PAGE = "content-observer-manage";
	const SETTINGS_PAGE = "content-observer-settings";

	const REQUEST_PARAM_API_KEY = "content_observer_api_key";

	/**
	 * Plugin constructor.
	 */
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
		$this->schedule      = new Schedule( $this );
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

require_once dirname( __FILE__ ) . "/public-functions.php";