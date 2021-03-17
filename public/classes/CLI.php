<?php


namespace Palasthotel\WordPress\ContentObserver;


use Palasthotel\WordPress\ContentObserver\Model\Site;

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
		$repo = Plugin::instance()->repo;
		$rest = Plugin::instance()->rest;
		$request = Plugin::instance()->remoteRequest;
		if(isset($assoc_args["site_id"])){
			$site_id = intval($assoc_args["site_id"]);
			$site = $repo->getSite(intval($assoc_args["site_id"]));
			if($site instanceof Site){
				\WP_CLI::success("Ping ".$site->url);
				return;
			}
			\WP_Cli::error("Site with id $site_id not found");
			return;
		}

		foreach ($repo->getSites() as $site){
			$restUrl = $rest->getPingUrl($site);
			$result = $request->get($restUrl, $site->api_key);
			if($result instanceof \WP_Error){
				\WP_CLI::error( "Error with site : $restUrl -> {$result->get_error_message()}" );
			}
			\WP_CLI::line( "Ping $site->relation_type : $restUrl ✅ " );
		}

		\WP_CLI::success("Ping done.");
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
		$rest = Plugin::instance()->rest;
		$request = Plugin::instance()->remoteRequest;

		$sites = $this->getSites(isset($assoc_args["site_id"]) ? $assoc_args["site_id"] : null);

		var_dump($sites);

		if(null === $sites){
			\WP_CLI::error("Could not find site with id ".$assoc_args["site_id"]);
		}

		/**
		 * @var Site[] $sites
		 */
		foreach ($sites as $site){
			$response = $request->post($rest->getConnectUrl($site), $site->api_key, [
				"site_url" => get_site_url(),
				"foreign_api_key" => Plugin::instance()->settings->getApiKey(),
				"relation_type" => $site->relation_type === Site::OBSERVER ? Site::OBSERVABLE : Site::OBSERVER,
			]);
			if($response instanceof \WP_Error){
				\WP_CLI::error("Could not connect to site ID: $site->id , url: $site->url, api_key: $site->api_key -> {$response->get_error_message()}");
			} if(!is_object($response)){
				\WP_CLI::error("Could not connect to site ID: $site->id , url: $site->url, api_key: $site->api_key -> response is no object");
			} else if(!isset($response->success) || !$response->success){
				\WP_CLI::error("Could not connect to site ID: $site->id , url: $site->url, api_key: $site->api_key -> response was no success ".json_encode($response));
			}

		}

		\WP_CLI::success("All sites connection updated.");
	}

	/**
	 * notify content observers sites
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

	}

	/**
	 * fetch content observable sites
	 *
	 * ## OPTIONS
	 *
	 * [--site_id=<site_id>]
	 * : notify only one site with id
	 *
	 * ## EXAMPLES
	 *
	 *   wp content-observer fetch
	 *   wp content-observer fetch --site_id=1
	 *
	 * @when after_wp_load
	 */
	public function fetch($args, $assoc_args){

	}

	/**
	 * @param null|number $site_id
	 *
	 * @return Site[]|null
	 */
	private function getSites($site_id){
		if(null != $site_id){
			$site_id = intval($site_id);
			$site = Plugin::instance()->repo->getSite($site_id);
			if(!($site instanceof Site)){
				return null;
			}
			return [$site];
		}
		return Plugin::instance()->repo->getSites();
	}


}