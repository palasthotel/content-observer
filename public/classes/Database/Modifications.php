<?php


namespace Palasthotel\WordPress\ContentObserver\Database;

use Palasthotel\WordPress\ContentObserver\Model\Modification;
use Palasthotel\WordPress\ContentObserver\Model\ModQueryArgs;
use wpdb;

/**
 * @property  wpdb $wpdb
 * @property string table
 */
class Modifications extends _DB {

	public function __construct() {
		parent::__construct( "content_observer_modifications" );
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
	 * @param int $site_id
	 *
	 * @return bool|int
	 */
	public function deleteBySiteId( $site_id ) {
		return $this->wpdb->delete($this->table, ["site_id"=>$site_id], ["%d"]);
	}

	/**
	 * @return Modification[]
	 */
	public function getModifications( ModQueryArgs $args): array {

		$where = $this->buildWhere($args);

		// pagination
		$limitQuery = "";
		$limit = $args->per_page;
		$pageIndex = $args->page-1;
		$hasValidLimit = is_int($limit) && $limit > 0;
		$hasValidPageIndex = is_int($pageIndex) && $pageIndex >= 0;
		if(
			$hasValidLimit &&
			$hasValidPageIndex
		){
			$offset =$pageIndex*$limit;
			$limitQuery = "LIMIT $offset, $limit";
		} else if($hasValidLimit){
			$limitQuery = "LIMIT $limit";
		}

		$results = $this->wpdb->get_results(
			"SELECT site_id, content_id, content_type, mod_time, mod_type FROM $this->table $where ORDER BY mod_time ASC $limitQuery",
		);

		return array_map( function ( $row ) {
			return Modification::build( $row->content_id, $row->content_type )
			                   ->setModified( $row->mod_time )
			                   ->setSiteId( $row->site_id )
			                   ->setType( $row->mod_type );
		}, $results );
	}

	public function countModifications( ModQueryArgs $args ): int {
		$where = $this->buildWhere($args);
		$count = $this->wpdb->get_var( "SELECT count(site_id) FROM $this->table $where" );
		return intval($count);
	}

	private function buildWhere(ModQueryArgs $args): string {
		$conditions = [];
		if(null !== $args->site_id) {
			$conditions[] = $this->wpdb->prepare(
				"site_id = %d",
				$args->site_id
			);
		}
		if(null !== $args->since){
			$conditions[] = $this->wpdb->prepare(
				"mod_time >= %d",
				$args->since
			);
		}
		if(null !== $args->content_id){
			$conditions[] = $this->wpdb->prepare(
				"content_id = %d",
				$args->content_id
			);
		}
		if(!empty($args->content_types)){
			$types = implode(", ",array_map(function($type){
				return $this->wpdb->prepare("%s",$type);
			}, $args->content_types));

			$conditions[] = "content_type IN ($types)";
		}
		if(!empty($args->modification_types)){
			$types = implode(", ",array_map(function($type){
				return $this->wpdb->prepare("%s", $type);
			}, $args->modification_types));
			$conditions[] = "mod_type IN ($types)";
		}

		return count($conditions) > 0 ? "WHERE ".implode(" AND ", $conditions) : "";
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
			 mod_time bigint(20) NOT NULL,
			 primary key content_on_site (site_id, content_id, content_type),
			 key (content_id),			 
			 key (content_type),
			 key (mod_type),
			 key (mod_time),
			 key modification_time_and_type (mod_time, mod_type)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;" );
	}



}
