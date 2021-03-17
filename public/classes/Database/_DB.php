<?php


namespace Palasthotel\WordPress\ContentObserver;


use wpdb;

/**
 * @property  wpdb $wpdb
 * @property  string $table
 */
class _DB {
	public function __construct($tableWithoutPrefix) {
		global $wpdb;
		$this->wpdb = $wpdb;
		$this->table = $this->wpdb->prefix.$tableWithoutPrefix;
	}
}