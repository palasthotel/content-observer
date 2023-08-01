<?php


namespace Palasthotel\WordPress\ContentObserver;


use Palasthotel\WordPress\ContentObserver\Components\Component;

class Schedule extends Component {

	public function onCreate() {
		parent::onCreate();
		add_action( 'admin_init', function(){
			if(
				!$this->plugin->settings->isNotifyObserversScheduleDisabled()
				&&
				!wp_next_scheduled( Plugin::ACTION_SCHEDULE_NOTIFY_OBSERVERS )
			){
				wp_schedule_event( time(), 'hourly', Plugin::ACTION_SCHEDULE_NOTIFY_OBSERVERS);
			} else {
				wp_clear_scheduled_hook(Plugin::ACTION_SCHEDULE_NOTIFY_OBSERVERS);
			}
			if(
				!$this->plugin->settings->isStartModificationScheduleDisabled()
				&&
				!wp_next_scheduled( Plugin::ACTION_SCHEDULE_START_MODIFICATIONS_HOOK)
			){
				wp_schedule_event( time(), 'hourly', Plugin::ACTION_SCHEDULE_START_MODIFICATIONS_HOOK);
			} else {
				wp_clear_scheduled_hook(Plugin::ACTION_SCHEDULE_START_MODIFICATIONS_HOOK);
			}
		});
		add_action(Plugin::ACTION_SCHEDULE_NOTIFY_OBSERVERS, [$this, "notify_observers"]);
		add_action(Plugin::ACTION_SCHEDULE_START_MODIFICATIONS_HOOK, [$this, "start_modifications_hook"]);
	}

	public function notify_observers(){
		$this->plugin->tasks->notify();
	}

	public function start_modifications_hook(){
		$this->plugin->tasks->doModificationsHook();
	}

}
