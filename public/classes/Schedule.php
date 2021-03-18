<?php


namespace Palasthotel\WordPress\ContentObserver;


class Schedule extends _Component {

	public function onCreate() {
		parent::onCreate();
		add_action( 'admin_init', function(){
			if(!wp_next_scheduled( Plugin::ACTION_SCHEDULE_NOTIFY_OBSERVERS )){
				wp_schedule_event( time(), 'hourly', Plugin::ACTION_SCHEDULE_NOTIFY_OBSERVERS);
			}
			if(!wp_next_scheduled( Plugin::ACTION_SCHEDULE_START_MODIFICATIONS_HOOK)){
				wp_schedule_event( time(), 'hourly', Plugin::ACTION_SCHEDULE_START_MODIFICATIONS_HOOK);
			}
		});
		add_action(Plugin::ACTION_SCHEDULE_NOTIFY_OBSERVERS, [$this, "notify_observers"]);
		add_action(Plugin::ACTION_SCHEDULE_START_MODIFICATIONS_HOOK, [$this, "start_modifications_hook"]);
	}

	public function notify_observers(){
		$this->plugin->tasks->notify();
	}

	public function notify_observables(){
		$this->plugin->tasks->fetch();
	}

	public function start_modifications_hook(){
		$last_hook_run = intval(get_option(Plugin::OPTION_LAST_MODIFICATIONS_HOOK_RUN, 0));
		$runTime = time();
		foreach ($this->plugin->repo->getSites() as $site){
			$mods = $this->plugin->repo->getModifications($last_hook_run, $site->id);
			do_action(Plugin::ACTION_ON_MODIFICATIONS, $mods, $site);
		}
		update_option(Plugin::OPTION_LAST_MODIFICATIONS_HOOK_RUN, $runTime);
	}

}