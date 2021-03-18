<?php


namespace Palasthotel\WordPress\ContentObserver\Database;

use wpdb;

/**
 * @property  wpdb $wpdb
 * @property string table_posts
 * @property string table_listeners
 */
class ForeignPosts extends _DB {

	public function __construct() {
		parent::__construct("content_sync_posts");
	}

	/**
	 * create tables if they do not exist
	 */
	function createTable() {
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		\dbDelta( "CREATE TABLE IF NOT EXISTS $this->table_posts
			(
			 id bigint(20) unsigned auto_increment,
			 site_url 
			 post_id bigint (20) unsigned NOT NULL,
			 content text unsigned NOT NULL,
			 
			 primary key (id),
			 unique key unique_relation (embedded_in_post_id, post_id),
			 key (embedded_in_post_id),
			 key (post_id)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;" );
	}

}