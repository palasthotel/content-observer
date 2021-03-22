<?php


namespace Palasthotel\WordPress\ContentObserver\Database;

use Palasthotel\WordPress\ContentObserver\Model\Site;
use wpdb;

/**
 * @property  wpdb $wpdb
 * @property string table
 */
class Sites extends _DB {

	const TABLE_NAME_WITHOUT_PREFIX = "content_observer_sites";

	public function __construct() {
		parent::__construct( static::TABLE_NAME_WITHOUT_PREFIX );
	}

	/**
	 * @param Site $site
	 *
	 * @return bool|int
	 */
	public function set( $site ) {
		$args   = [
			"id"                     => $site->id,
			"slug"                   => $site->slug,
			"site_url"               => $site->url,
			"site_api_key"           => $site->api_key,
			"relation_type"          => $site->relation_type,
			"registration_time"      => $site->registration_time,
			"last_notification_time" => $site->last_notification_time,
		];
		$format = [ "%d", "%s", "%s", "%s", "%s", "%d", "%d" ];

		if ( null !== $site->id && $site->id > 0 ) {
			return $this->wpdb->update(
				$this->table,
				$args,
				[
					"id" => $site->id,
				],
				$format,
				[ "%d" ]
			);
		}

		return $this->wpdb->insert(
			$this->table,
			$args,
			$format
		);
	}

	/**
	 * @param number $site_id
	 *
	 * @return bool|int
	 */
	public function delete( $site_id ) {
		return $this->wpdb->delete( $this->table, [ "id" => $site_id ], [ "%d" ] );
	}

	/**
	 * @param int $id
	 *
	 * @return bool|int
	 */
	public function remove( $id ) {
		return $this->wpdb->delete( $this->table, [ "id" => $id ], [ "%d" ] );
	}

	/**
	 * @return Site[]
	 */
	public function getAll() {
		$results = $this->wpdb->get_results(
			"SELECT id, slug, site_url, site_api_key, relation_type, registration_time, last_notification_time FROM $this->table"
		);

		return array_map( function ( $row ) {
			return Site::build( $row->site_url )
			           ->setId( $row->id )
			           ->setSlug( $row->slug )
			           ->setApiKey( $row->site_api_key )
			           ->setRelationType( $row->relation_type )
			           ->setLastNotificationTime( $row->last_notification_time )
			           ->setRegistrationTime( $row->registration_time );
		}, $results );
	}

	/**
	 * create tables if they do not exist
	 */
	function createTable() {
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		\dbDelta( "CREATE TABLE IF NOT EXISTS $this->table
			(
			 id int(8) unsigned auto_increment,
			 slug varchar (50) NOT NULL,
			 site_url varchar(190) NOT NULL,
			 site_api_key text NOT NULL,
			 relation_type ENUM('observer', 'observable', 'both') NOT NULL,
			 registration_time bigint(20) NOT NULL,
			 last_notification_time bigint(20) NOT NULL DEFAULT 0,
			 primary key (id),
			 unique key (slug),
			 unique key (site_url),
			 key (registration_time),
			 key (last_notification_time)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;" );
	}

}