<?php


namespace Palasthotel\WordPress\ContentObserver;


class CLI {

	/**
	 * ping content-observer sites
	 *
	 * ## OPTIONS
	 *
	 * [--url=<url>]
	 * : ping site with url
	 *
	 * [--site_id=<site_id>]
	 * : ping site with id
	 *
	 * ## EXAMPLES
	 *
	 *   wp content-observer ping
	 *   wp content-observer ping --url=https://domain.de/
	 *   wp content-observer ping --site_id=1
	 *
	 * @when after_wp_load
	 */
	public function ping($args, $assoc_args){
		Plugin::instance()->tasks->ping(isset($assoc_args["site_id"]) ? intval($assoc_args["site_id"]) : null );
	}

	/**
	 * update connections to content-observer sites
	 *
	 * ## OPTIONS
	 *
	 * [--site_id=<site_id>]
	 * : update connection for site with id
	 *
	 * ## EXAMPLES
	 *
	 *   wp content-observer connect
	 *   wp content-observer connect --site_id=1
	 *
	 * @when after_wp_load
	 */
	public function connect($args, $assoc_args){
		Plugin::instance()->tasks->connect(
			isset($assoc_args["site_id"]) ? intval($assoc_args["site_id"]) : null
		);
	}

	/**
	 * notify content observers sites about changes
	 *
	 * ## OPTIONS
	 *
	 * [--site_id=<site_id>]
	 * : notify only one site with id
	 *
	 * ## EXAMPLES
	 *
	 *   wp content-observer notify
	 *   wp content-observer notify --site_id=1
	 *
	 * @when after_wp_load
	 */
	public function notify($args, $assoc_args){
		Plugin::instance()->tasks->notify(
			isset($assoc_args["site_id"]) ? intval($assoc_args["site_id"]) : null
		);
	}

	/**
	 * fetch content observable sites
	 *
	 * ## OPTIONS
	 *
	 * [--full=<bool>]
	 * : incremental or full import of modifications
	 *
	 * ---
	 * default: success
	 * options:
	 *   - true
	 *   - false
	 * ---
	 *
	 * [--site_id=<site_id>]
	 * : notify only one site with id
	 *
	 * [--mods_per_request=<number>]
	 * : number of modifications per request
	 *
	 * ---
	 * default: 100
	 * ---
	 * ## EXAMPLES
	 *
	 *   wp content-observer fetch
	 *   wp content-observer fetch --site_id=1
	 *   wp content-observer fetch --site_id=1 --full=true
	 *
	 * @when after_wp_load
	 */
	public function fetch($args, $assoc_args){
		Plugin::instance()->tasks->fetch(
			isset($assoc_args["site_id"]) ? intval($assoc_args["site_id"]) : null,
			isset($assoc_args["full"]) && "true" === $assoc_args["full"],
			isset($assoc_args["mods_per_request"]) && intval($assoc_args["mods_per_request"]) > 0 ? intval($assoc_args["mods_per_request"]) : 100
		);
	}

	/**
	 * start processing modifications
	 *
	 * ## OPTIONS
	 *
	 * [--since=<timestamp>]
	 * : only modifications later than since
	 *
	 * ---
	 * default: 0
	 * ---
	 *
	 * [--site_id=<site_id>]
	 * : notify only one site with id
	 *
	 * ## EXAMPLES
	 *
	 *   wp content-observer modifications
	 *   wp content-observer modifications --since=1616417666 --site_id=1
	 *
	 * @when after_wp_load
	 */
	public function modifications($args, $assoc_args){
		Plugin::instance()->tasks->doModificationsHook(
			isset($assoc_args["since"]) ? intval($assoc_args["since"]) : null
		);
	}


}