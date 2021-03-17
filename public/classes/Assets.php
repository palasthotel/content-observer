<?php


namespace Palasthotel\WordPress\ContentObserver;


class Assets extends _Component {
	public function enqueueSettings(){
		$info = include $this->plugin->path . "/js/settings/settings.asset.php";
		wp_enqueue_script(
			Plugin::HANDLE_SETTINGS_JS,
			$this->plugin->url . "/js/settings/settings.js",
			array_merge(["jquery"],$info["dependencies"]),
			$info["version"]
		);
		wp_localize_script(
			Plugin::HANDLE_SETTINGS_JS,
			"ContentSync",
			[
				"apiNamespace" => REST::NAMESPACE,
			]
		);
	}
}