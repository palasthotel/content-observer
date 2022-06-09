<?php


namespace Palasthotel\WordPress\ContentObserver;


class Assets extends _Component {


	public function enqueueModifications(){
		$info = include $this->plugin->path . "/dist/modifications.asset.php";
		wp_enqueue_script(
			Plugin::HANDLE_MODIFICATIONS_JS,
			$this->plugin->url . "/dist/modifications.js",
			array_merge(["jquery"],$info["dependencies"]),
			$info["version"]
		);
		wp_localize_script(
			Plugin::HANDLE_MODIFICATIONS_JS,
			"ContentObserver",
			[
				"apiKeyParam" => Plugin::REQUEST_PARAM_API_KEY,
				"apiKeyValue" => $this->plugin->settings->getApiKey(),
			]
		);
	}

	public function enqueueSettings(){
		$info = include $this->plugin->path . "/dist/settings.asset.php";
		wp_enqueue_script(
			Plugin::HANDLE_SETTINGS_JS,
			$this->plugin->url . "/dist/settings.js",
			array_merge(["jquery"],$info["dependencies"]),
			$info["version"]
		);
		wp_localize_script(
			Plugin::HANDLE_SETTINGS_JS,
			"ContentObserver",
			[
				"apiKey" => $this->plugin->settings->getApiKey(),
				"apiNamespace" => REST::NAMESPACE,
				"pingUrlApiKeyParam" => Plugin::REQUEST_PARAM_API_KEY,
				"pingUrl" => $this->plugin->rest->getPingUrl(""),
			]
		);
	}


}