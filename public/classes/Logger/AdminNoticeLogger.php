<?php


namespace Palasthotel\WordPress\ContentObserver\Logger;


use Palasthotel\WordPress\ContentObserver\Interfaces\ILogger;
use Palasthotel\WordPress\ContentObserver\Plugin;

class AdminNoticeLogger implements ILogger {
	private bool $started = false;

	public function __construct() {

		if (!is_admin()) return;

		$currentLogs = get_option(Plugin::OPTION_ADMIN_NOTICE_LOGS, []);
		if (count($currentLogs) > 0) {
			add_action('admin_notices', [$this, 'admin_notices']);
		}

	}

	public function admin_notices() {

		if (!current_user_can("manage_options")) return;

		$currentLogs = get_option(Plugin::OPTION_ADMIN_NOTICE_LOGS, []);
		foreach ($currentLogs as $log) {
			$log = (string) $log;
			$noticeType = "notice-info";
			if (str_starts_with($log, "✅")) {
				$noticeType = "notice-success";
			} else if (str_starts_with($log, "🚨")) {
				$noticeType = "notice-error";
			} else if (str_starts_with($log, "⚠️")) {
				$noticeType = "notice-warning";
			}
			// logs carry responses of other sites, so they are text, never markup
			printf(
				'<div class="notice %s"><p><strong>Content Observer:</strong> %s</p></div>',
				esc_attr($noticeType),
				esc_html($log)
			);
		}
	}

	private function log($msg) {
		if (!$this->started) {
			delete_option(Plugin::OPTION_ADMIN_NOTICE_LOGS);
			$this->started = true;
		}
		$currentLogs = get_option(Plugin::OPTION_ADMIN_NOTICE_LOGS, []);
		$currentLogs[] = $msg;
		update_option(Plugin::OPTION_ADMIN_NOTICE_LOGS, $currentLogs);
	}

	function line($msg) {
		$this->log($msg);
	}

	function success($msg) {
		$this->log("✅ Success: " . $msg);
	}

	function error($msg) {
		$this->log("🚨 Error: " . $msg);
	}

	function warning($msg) {
		$this->log("⚠️ Warning: " . $msg);
	}
}
