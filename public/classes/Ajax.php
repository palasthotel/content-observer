<?php

namespace Palasthotel\WordPress\ContentObserver;

use Palasthotel\WordPress\ContentObserver\Components\Component;

class Ajax extends Component {
	public function onCreate() {
		parent::onCreate();

		add_action('wp_ajax_content_observer_fetch_modifications', [$this, 'fetch_modifications']);
		add_action('wp_ajax_content_observer_notify', [$this, 'notify']);
		add_action('wp_ajax_content_observer_apply', [$this, 'apply']);
	}

	public function fetch_modifications(){
		if(!current_user_can("manage_options")) return;
		$numberOfModifications = $this->plugin->tasks->fetch($this->getSiteId());
		wp_send_json_success([
			"number_of_modifications" => $numberOfModifications,
		]);
	}

	public function notify(){
		if(!current_user_can("manage_options")) return;
		$result = $this->plugin->tasks->notify($this->getSiteId());
		if(is_wp_error($result)){
			error_log($result->get_error_message());
			wp_send_json_error(["message" => $result->get_error_message()]);
		}

		wp_send_json_success([]);
	}
	private function getSiteId(){
		return isset($_GET["site_id"]) ? intval($_GET["site_id"]) : null;
	}

	public function apply(){
		if(!current_user_can("manage_options")) return;
		$since = intval($_GET["since"]);
		$result = $this->plugin->tasks->doModificationsHook($since);
		wp_send_json_success([
			"number_of_modifications" => $result,
		]);
	}
}
