<?php


namespace Palasthotel\WordPress\ContentObserver;

use Palasthotel\WordPress\ContentObserver\Model\Modification;
use wpdb;

/**
 * @property  wpdb $wpdb
 * @property string table
 */
class Modifications extends _DB {

	public function __construct() {
		parent::__construct( "content_observer_modifications" );
	}

	public function setCreate( $post_id ) {
		return $this->setModification( Modification::build( $post_id )->create() );
	}

	public function setUpdate( $post_id ) {
		return $this->setModification( Modification::build( $post_id )->update() );
	}

	public function setDelete( $post_id ) {
		return $this->setModification( Modification::build( $post_id )->delete() );
	}

	/**
	 * @param Modification $modification
	 *
	 * @return bool|int
	 */
	public function setModification( $modification ) {
		return $this->wpdb->replace(
			$this->table,
			[
				"site_id"      => $modification->site_id,
				"content_id"   => $modification->content_id,
				"content_type" => $modification->content_type,
				"mod_type"     => $modification->type,
				"mod_time"     => $modification->modified,
			], [ "%d", "%s", "%s", "%s", "%d" ]
		);
	}

	/**
	 * @param null|number $site_id
	 * @param number $since
	 *
	 * @return array|object|null
	 */
	public function getModifications( $site_id, $since ) {
		$site_id_query = null === $site_id ? "site_id IS NULL" : "site_id = " . intval( $site_id );
		$results       = $this->wpdb->get_results(
			$this->wpdb->prepare(
				"SELECT site_id, content_id, content_type, mod_time, mod_type FROM $this->table WHERE $site_id_query AND mod_time >= %d",
				$since
			)
		);

		return array_map( function ( $row ) {
			return Modification::build( $row->content_id, $row->content_type, $row->mod_time )
			                   ->setSiteId( $row->site_id )
			                   ->setType( $row->mod_type );
		}, $results );
	}

	/**
	 * create tables if they do not exist
	 */
	function createTable() {
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		\dbDelta( "CREATE TABLE IF NOT EXISTS $this->table
			(
			 site_id int(8) unsigned DEFAULT 0,
			 content_id varchar (50) NOT NULL,
			 content_type varchar(50) NOT NULL,
			 mod_type varchar(10) NOT NULL,
			 mod_time bigint(20) DEFAULT NOW(),
			 primary key content_on_site (site_id, content_id, content_type),
			 key (content_id),			 
			 key (content_type),
			 key (mod_type),
			 key (mod_time),
			 key modification_time_and_type (mod_time, mod_type)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;" );
	}

}