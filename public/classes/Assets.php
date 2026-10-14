<?php

namespace Palasthotel\WordPress\ContentObserver;

use Palasthotel\WordPress\ContentObserver\Components\Component;

defined( 'ABSPATH' ) || exit;

class Assets extends Component {

	public function enqueueModifications(): void {
		$info = include $this->plugin->path . "/dist/modifications.asset.php";
		wp_enqueue_script(
			Plugin::HANDLE_MODIFICATIONS_JS,
			$this->plugin->url . "/dist/modifications.js",
			$info["dependencies"],
			$info["version"]
		);
		wp_localize_script(
			Plugin::HANDLE_MODIFICATIONS_JS,
			"ContentObserver",
			[
				"ajax_url"    => admin_url( "admin-ajax.php" ),
				"ajax_nonce"  => wp_create_nonce( Ajax::NONCE_ACTION ),
			]
		);
	}

	public function enqueueSettings() {
		$info = include $this->plugin->path . "/dist/settings.asset.php";
		wp_enqueue_script(
			Plugin::HANDLE_SETTINGS_JS,
			$this->plugin->url . "/dist/settings.js",
			$info["dependencies"],
			$info["version"]
		);
		wp_localize_script(
			Plugin::HANDLE_SETTINGS_JS,
			"ContentObserver",
			[
				"ajax_url"     => admin_url( "admin-ajax.php" ),
				"apiNamespace" => REST::NAMESPACE,
			]
		);
	}


}
