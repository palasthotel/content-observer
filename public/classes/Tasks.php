<?php


namespace Palasthotel\WordPress\ContentObserver;


use Palasthotel\WordPress\ContentObserver\Components\Component;
use Palasthotel\WordPress\ContentObserver\Interfaces\ILogger;
use Palasthotel\WordPress\ContentObserver\Logger\Logger;
use Palasthotel\WordPress\ContentObserver\Model\Modification;
use Palasthotel\WordPress\ContentObserver\Model\ModQueryArgs;
use Palasthotel\WordPress\ContentObserver\Model\Site;
use Palasthotel\WordPress\ContentObserver\Model\SiteModificationAction;
use WP_Error;

class Tasks extends Component {

	private ILogger $logger;

	public function onCreate() {
		parent::onCreate();
		$this->logger = new Logger( WP_DEBUG );
	}

	public function setLogger( ILogger $logger ) {
		$this->logger = $logger;
	}

	private function getTaskIdTransientKey($taskId){
		return Plugin::DOMAIN."_task_".$taskId."_is_running";
	}

	private function isTaskRunning($taskId){
		return get_transient($this->getTaskIdTransientKey($taskId)) === "true";
	}

	private function setTaskIsRunning($taskId, $isRunning){
		if($isRunning){
			set_transient($this->getTaskIdTransientKey($taskId), "true", 60*60);
		} else {
			delete_transient($this->getTaskIdTransientKey($taskId));
		}
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
			$restUrl = $rest->getPingUrl( $site->url );
			$result  = $request->get( $restUrl, $site->api_key );
			if ( $result instanceof WP_Error ) {
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
	 * @return bool|WP_Error
	 */
	public function connect( $site_id = null ) {
		$rest    = $this->plugin->rest;
		$request = $this->plugin->remoteRequest;

		$taskId = "connect";
		if($this->isTaskRunning($taskId)){
			return new WP_Error("Task is already running");
		}
		$this->setTaskIsRunning($taskId, true);

		$sites = $this->getSites( $site_id );

		if ( null === $sites ) {
			$this->setTaskIsRunning($taskId, false);
			$this->logger->error( "Could not find site with id " . $site_id );
			return false;
		}

		foreach ( $sites as $site ) {
			$this->logger->line( "Update connection with $site->url..." );
			$response = $request->post( $rest->getConnectUrl( $site->url ), $site->api_key, [
				"site_url"        => get_site_url(),
				"foreign_api_key" => $this->plugin->settings->getApiKey(),
				"relation_type"   => $site->relation_type === Site::OBSERVER ? Site::OBSERVABLE : Site::OBSERVER,
			] );
			if ( $response instanceof WP_Error ) {
				$this->setTaskIsRunning($taskId, false);
				$this->logger->error( "Could not connect to site ID: $site->id , url: $site->url, api_key: $site->api_key -> {$response->get_error_message()}" );
				return false;
			}
			if ( ! is_object( $response ) ) {
				$this->setTaskIsRunning($taskId, false);
				$this->logger->error( "Could not connect to site ID: $site->id , url: $site->url, api_key: $site->api_key -> response is no object" );
				return false;
			} else if ( ! isset( $response->success ) || ! $response->success ) {
				$this->setTaskIsRunning($taskId, false);
				$this->logger->error( "Could not connect to site ID: $site->id , url: $site->url, api_key: $site->api_key -> response was no success " . json_encode( $response ) );
				return false;
			}
			$this->plugin->repo->setSite($site->setRegistrationTime(time()));
			$this->logger->line( "Connection with $site->url was updated." );
		}

		$this->logger->success( "All sites connection updated." );
		$this->setTaskIsRunning($taskId, false);

		return true;
	}

	/**
	 * @param null|int $site_id
	 *
	 * @return bool|WP_Error
	 */
	public function notify( $site_id = null ) {

		$taskId = "notify";
		if($this->isTaskRunning($taskId)){
			$this->logger->warning( "Task is already running" );
			return new WP_Error("Task is already running");
		}
		$this->setTaskIsRunning($taskId, true);

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

			$args = ModQueryArgs::build()
			                    ->since($observer->last_notification_time)
								->siteId(Site::MY_SITE);

			$mods = $repo->getModifications( $args );

			$modifications = array_map( function ( $mod ) {
				$this->logger->line( "Modification: " . json_encode( $mod ) );
				$arr = $mod->asArray();
				unset( $arr["site_id"] ); // this is only the internal site id

				return $arr;
			}, $mods );

			$chunks = array_chunk( $modifications, 100 );

			foreach ( $chunks as $chunk ) {
				$this->logger->line( "Send Chunk: " . json_encode( $chunk ) );
				$modificationsUrl = $rest->getModificationsUrl( $observer->url );
				$requestBody = [
					"site_url" => $mySite->url,
					"mods"     => $chunk,
				];
				$response = $request->post(
					$modificationsUrl,
					$observer->api_key,
					$requestBody
				);

				$this->logger->line(json_encode($response));
				if ( $response instanceof WP_Error ) {
					$this->setTaskIsRunning($taskId, false);
					$this->logger->warning( $response->get_error_message() );
					return false;
				} else if ( ! isset( $response->success ) || ! $response->success ) {
					$this->setTaskIsRunning($taskId, false);
					$this->logger->warning( "POST Request-URL $modificationsUrl");
					$this->logger->warning( "POST Request-Body " . json_encode( $requestBody ) );
					$this->logger->warning( "POST notifications has success response " . json_encode( $response ) );
					return false;
				}
			}
			$repo->setSite( $observer->setLastNotificationTime( $runTime ) );
		}

		$this->setTaskIsRunning($taskId, false);
		return true;
	}

	/**
	 * @param int|null $site_id
	 * @param boolean $allModifications
	 * @param int $numberOfModsPerRequest
	 *
	 * @return int
	 */
	public function fetch(
		$site_id = null,
		$allModifications = false,
		$numberOfModsPerRequest = 100
	): int {
		$repo        = $this->plugin->repo;
		$rest        = $this->plugin->rest;
		$request     = $this->plugin->remoteRequest;
		$observables = $repo->getObservables();
		$runTime     = time();
		$success     = [];
		$numberOfModifications = 0;
		$period      = $allModifications ? "all" : "new";
		foreach ( $observables as $observable ) {
			if ( null !== $site_id && $site_id !== $observable->id ) {
				$this->logger->line( "Skipped $observable->url" );
				continue;
			}

			$this->logger->line( "Fetch $period modifications from Site $observable->id -> $observable->url" );

			$url = $rest->getModificationsUrl( $observable->url );
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

				if ( $response instanceof WP_Error ) {
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
					$numberOfModifications++;
				}

				$page++;

			} while ( count($response->mods) > 0 && $response->pages >= $page );

			if ( ! isset( $success[ $observable->id ] ) ) {
				$success[ $observable->id ] = true;
				$repo->setSite( $observable->setLastNotificationTime( $runTime ) );
			}
		}

		return $numberOfModifications;
	}

	/**
	 * publish modifications
	 *
	 */
	public function doModificationsHook( ?int $since = null): int{
		if(null === $since || $since <= 0){
			$last_hook_run = intval(get_option(Plugin::OPTION_LAST_MODIFICATIONS_HOOK_RUN, 0));
		} else {
			$last_hook_run = $since;
		}

		$this->logger->line("Run modifications since $last_hook_run");

		$runTime = time();
		$numberOfAppliedModifications = 0;
		foreach ($this->plugin->repo->getSites() as $site){
			$args = ModQueryArgs::build()->siteId($site->id)->since($last_hook_run);
			do {
				$mods = $this->plugin->repo->getModifications($args);
				$this->logger->line("Found ".count($mods)." modification of site $site->slug, page $args->page");
				$this->runModifications($site, $mods);
				$args->page($args->page+1);
				$numberOfAppliedModifications++;
			} while(count($mods) > 0);
		}
		update_option(Plugin::OPTION_LAST_MODIFICATIONS_HOOK_RUN, $runTime);
		return $numberOfAppliedModifications;
	}

	/**
	 * @param Site $site
	 * @param Modification[] $mods
	 *
	 * @return void
	 */
	public function runModifications(Site $site, array $mods){
		do_action(Plugin::ACTION_ON_MODIFICATIONS, SiteModificationAction::build($site, $mods));
		do_action( sprintf(Plugin::ACTION_ON_SITE_MODIFICATIONS, $site->id), SiteModificationAction::build($site, $mods));
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
