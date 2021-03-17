<?php


namespace Palasthotel\WordPress\ContentObserver;


class Schedule extends _Component {

	public function onCreate() {
		parent::onCreate();
		add_action( 'admin_init', function(){
			if(!wp_next_scheduled( Plugin::ACTION_SCHEDULE_NOTIFY_OBSERVERS )){
				wp_schedule_event( time(), 'hourly', Plugin::ACTION_SCHEDULE_NOTIFY_OBSERVERS);
			}
			if(!wp_next_scheduled( Plugin::ACTION_SCHEDULE_NOTIFY_OBSERVABLES)){
				wp_schedule_event( time(), 'hourly', Plugin::ACTION_SCHEDULE_NOTIFY_OBSERVABLES);
			}
		});
		add_action(Plugin::ACTION_SCHEDULE_NOTIFY_OBSERVERS, [$this, "notify_observers"]);
		add_action(Plugin::ACTION_SCHEDULE_NOTIFY_OBSERVABLES, [$this, "notify_observables"]);
	}

	public function notify_observers(){
		$obervers = $this->plugin->repo->getObservers();
		foreach ($obervers as $observer){
			$mods =$this->plugin->repo->getModifications($observer->id, $observer->last_notification_time);
			wp_remote_post(
				$observer->url."/wp-json/".REST::NAMESPACE."/modifications",
				[
					"body" => [
						"mods" => array_map(function($mod){
							return array_merge(
								$mod->asArray(),
								["post_type" => get_post_type($mod->post_id)]
							);
						}, $mods),
					]
				]
			);
		}
		// fetch all changes since last run
		// notify observers about changes
	}

	public function notify_observables(){
		$observables = $this->plugin->repo->getObservables();
		// check if observables is reachable for content-sync
		// remind them that we are watching for changes
	}

}