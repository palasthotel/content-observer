<?php


namespace Palasthotel\WordPress\ContentObserver;


use Palasthotel\WordPress\ContentObserver\Interfaces\ILogger;
use Palasthotel\WordPress\ContentObserver\Logger\Logger;
use Palasthotel\WordPress\ContentObserver\Model\Modification;
use Palasthotel\WordPress\ContentObserver\Model\Site;

/**
 * @property ILogger logger
 */
class Tasks extends _Component {

	public function onCreate() {
		parent::onCreate();
		$this->logger = new Logger( WP_DEBUG );
	}

	/**
	 * @param ILogger $logger
	 */
	public function setLogger( $logger ) {
		$this->logger = $logger;
	}

	/**
	 * @param null|int $site_id
	 *
	 * @return bool[]
	 */
	public function ping( $site_id = null ) {
		$rest    = $this->plugin->rest;
		$request = $this->plugin->remoteRequest;

		$sites = $this->getSites( $site_id );
		if ( null === $sites ) {
			$this->logger->error( "Could not find site with id " . $site_id );

			return [ false ];
		}

		$success = [];
		foreach ( $sites as $site ) {
			$restUrl = $rest->getPingUrl( $site );
			$result  = $request->get( $restUrl, $site->api_key );
			if ( $result instanceof \WP_Error ) {
				$this->logger->error( "Error with site : $restUrl -> {$result->get_error_message()}" );
				$success[ $site->id ] = false;
				continue;
			}
			$this->logger->line( "Ping $site->relation_type : $restUrl ✅ " );
			$success[ $site->id ] = true;
		}

		$this->logger->success( "Ping done." );

		return $success;
	}

	/**
	 * @param null|int $site_id all or one specific site
	 *
	 * @return bool
	 */
	public function connect( $site_id = null ) {
		$rest    = $this->plugin->rest;
		$request = $this->plugin->remoteRequest;

		$sites = $this->getSites( $site_id );

		if ( null === $sites ) {
			$this->logger->error( "Could not find site with id " . $site_id );

			return false;
		}

		foreach ( $sites as $site ) {
			$response = $request->post( $rest->getConnectUrl( $site ), $site->api_key, [
				"site_url"        => get_site_url(),
				"foreign_api_key" => $this->plugin->settings->getApiKey(),
				"relation_type"   => $site->relation_type === Site::OBSERVER ? Site::OBSERVABLE : Site::OBSERVER,
			] );
			if ( $response instanceof \WP_Error ) {
				$this->logger->error( "Could not connect to site ID: $site->id , url: $site->url, api_key: $site->api_key -> {$response->get_error_message()}" );

				return false;
			}
			if ( ! is_object( $response ) ) {
				$this->logger->error( "Could not connect to site ID: $site->id , url: $site->url, api_key: $site->api_key -> response is no object" );

				return false;
			} else if ( ! isset( $response->success ) || ! $response->success ) {
				$this->logger->error( "Could not connect to site ID: $site->id , url: $site->url, api_key: $site->api_key -> response was no success " . json_encode( $response ) );

				return false;
			}
			$this->logger->line( "Connection with $site->url was updated." );
		}

		$this->logger->success( "All sites connection updated." );

		return true;
	}

	/**
	 * @param null|int $site_id
	 *
	 * @return boolean
	 */
	public function notify( $site_id = null ) {

		$repo      = $this->plugin->repo;
		$rest      = $this->plugin->rest;
		$request   = $this->plugin->remoteRequest;
		$observers = $repo->getObservers();
		$mySite    = Site::build( get_site_url() );
		$runTime   = time();
		foreach ( $observers as $observer ) {

			if ( null !== $site_id && $site_id !== $observer->id ) {
				$this->logger->line( "Skipped $observer->url" );
				continue;
			}

			$this->logger->line( "Notify about modifications site $observer->id -> $observer->url" );

			$mods = $repo->getModifications( $observer->last_notification_time );

			$modifications = array_map( function ( $mod ) {
				$this->logger->line( "Modification: " . json_encode( $mod ) );
				$arr = $mod->asArray();
				unset( $arr["site_id"] ); // this is only the internal site id

				return $arr;
			}, $mods );

			$chunks = array_chunk( $modifications, 100 );

			foreach ( $chunks as $chunk ) {
				$this->logger->line( "Send Chunk: " . json_encode( $chunk ) );
				$response = $request->post(
					$rest->getModificationsUrl( $observer ),
					$observer->api_key,
					[
						"site_url" => $mySite->url,
						"mods"     => $chunk,
					]
				);
				if ( $response instanceof \WP_Error ) {
					$this->logger->error( $response->get_error_message() );

					return false;
				} else if ( ! isset( $response->success ) || ! $response->success ) {
					$this->logger->error( "POST notifications has success response " . json_encode( $response ) );

					return false;
				}
			}
			$repo->setSite( $observer->setLastNotificationTime( $runTime ) );
		}

		return true;
	}

	/**
	 * @param int|null $site_id
	 * @param boolean $allModifications
	 * @param int $numberOfModsPerRequest
	 *
	 * @return boolean[]
	 */
	public function fetch(
		$site_id,
		$allModifications,
		$numberOfModsPerRequest
	) {
		$repo        = $this->plugin->repo;
		$rest        = $this->plugin->rest;
		$request     = $this->plugin->remoteRequest;
		$observables = $repo->getObservables();
		$runTime     = time();
		$success     = [];
		$period      = $allModifications ? "all" : "new";
		foreach ( $observables as $observable ) {
			if ( null !== $site_id && $site_id !== $observable->id ) {
				$this->logger->line( "Skipped $observable->url" );
				continue;
			}

			$this->logger->line( "Fetch $period modifications from Site $observable->id -> $observable->url" );

			$url = $rest->getModificationsUrl( $observable );
			$page = 1;
			do {

				$pagedUrl = add_query_arg([
					"page" => $page,
					"number" => $numberOfModsPerRequest
				], $url);

				$this->logger->line("Fetch $pagedUrl");

				$response = $request->get(
					$allModifications ? $pagedUrl : add_query_arg( "since", $observable->last_notification_time, $pagedUrl ),
					$observable->api_key
				);

				$this->logger->line("Fetch ".json_encode($response));

				if ( $response instanceof \WP_Error ) {
					$this->logger->error( $response->get_error_message() );
					$success[ $observable->id ] = false;
					break;
				}

				if ( ! isset( $response->success ) || ! $response->success ) {
					$success[ $observable->id ] = false;
					break;
				}

				foreach ($response->mods as $mod){

					$repo->setModification(
						Modification::fromArray((array)$mod)
							->setSiteId($observable->id)
							->setModified($runTime)
					);
				}

				$page++;

			} while ( count($response->mods) > 0 && $response->pages >= $page );

			if ( ! isset( $success[ $observable->id ] ) ) {
				$success[ $observable->id ] = true;
				$repo->setSite( $observable->setLastNotificationTime( $runTime ) );
			}
		}

		return $success;
	}

	/**
	 * @param null|number $site_id
	 *
	 * @return Site[]|null
	 */
	private function getSites( $site_id ) {
		if ( null != $site_id ) {
			$site_id = intval( $site_id );
			$site    = $this->plugin->repo->getSite( $site_id );
			if ( ! ( $site instanceof Site ) ) {
				return null;
			}

			return [ $site ];
		}

		return $this->plugin->repo->getSites();
	}


}