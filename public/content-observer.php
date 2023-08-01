<?php

/**
 * Plugin Name: Content Observer
 * Plugin URI: https://github.com/palasthotel/content-observer
 * Description: Efficiently observe content changes between WordPress instances
 * Version: 1.1.0
 * Author: Palasthotel <rezeption@palasthotel.de> (in person: Edward Bock)
 * Author URI: http://www.palasthotel.de
 * Requires at least: 6.0
 * Tested up to: 6.3.0
 * Text Domain: content-observer
 * License: http://www.gnu.org/licenses/gpl-2.0.html GPLv2
 * @copyright Copyright (c) 2023, Palasthotel
 * @package Palasthotel\WordPress\ContentObserver
 *
 */

namespace Palasthotel\WordPress\ContentObserver;

use Palasthotel\WordPress\ContentObserver\Logger\CLILogger;

require_once dirname( __FILE__ ) . "/vendor/autoload.php";

class Plugin extends Components\Plugin {

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
	public Assets $assets;
	public Repository $repo;
	public REST $rest;
	public Settings $settings;
	public RemoteRequest $remoteRequest;
	public OnPostChange $onPostChange;
	public Schedule $schedule;
	public Tasks $tasks;
	public Ajax $ajax;

	public function onCreate() {

		$this->loadTextdomain( self::DOMAIN, "languages" );

		$this->assets        = new Assets( $this );
		$this->repo          = new Repository( $this );
		$this->rest          = new REST( $this );
		$this->ajax          = new Ajax( $this );
		$this->settings      = new Settings( $this );
		$this->remoteRequest = new RemoteRequest( $this );
		$this->onPostChange  = new OnPostChange( $this );
		$this->schedule      = new Schedule( $this );
		$this->tasks         = new Tasks( $this );

		if ( class_exists( "\WP_CLI" ) ) {
			$this->tasks->setLogger( new CLILogger() );
			\WP_CLI::add_command( 'content-observer', new CLI() );
		}

		if ( WP_DEBUG ) {
			$this->repo->init();
		}

	}

	public function onSiteActivation() {
		parent::onSiteActivation();
		$this->repo->init();
	}
}

Plugin::instance();

require_once dirname( __FILE__ ) . "/public-functions.php";
