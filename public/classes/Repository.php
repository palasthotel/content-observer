<?php


namespace Palasthotel\WordPress\ContentObserver;


use Palasthotel\WordPress\ContentObserver\Model\Site;
use Palasthotel\WordPress\ContentObserver\Model\Modification;

class Repository extends _Component {

	/**
	 * @var Modifications
	 */
	private $modificationsDB;

	/**
	 * @var null|Modification[][]
	 */
	private $modificationsCache;

	/**
	 * @var Sites
	 */
	private $sitesDB;
	/**
	 * @var Site[]
	 */
	private $sitesCache;


	public function onCreate() {
		parent::onCreate();

		$this->modificationsDB = new Modifications();
		$this->modificationsCache = [];
		$this->sitesDB         = new Sites();
		$this->sitesCache = [];
	}

	/**
	 * initialize repo dependencies
	 */
	public function init(){
		// order matteres because modifications have foreign key for sites
		$this->sitesDB->createTable();
		$this->modificationsDB->createTable();

	}

	/**
	 * @return Site[]
	 */
	public function getSites(){
		if(null == $this->sitesCache){
			$this->sitesCache = $this->sitesDB->getAll();
		}
		return $this->sitesCache;
	}

	/**
	 * @return Site[]
	 */
	public function getObservers(){
		$sites = $this->getSites();

		return array_filter($sites, function($site){
			return $site->isObserver();
		});
	}

	/**
	 * @return Site[]
	 */
	public function getObservables(){
		$sites = $this->getSites();

		return array_filter($sites, function($site){
			return $site->isObservable();
		});
	}

	/**
	 * @param int|null $since null means own mods
	 *
	 * @return Modification[]
	 */
	public function getModifications($site_id, $since){
		$since = null === $since ? 0 : $since;
		if(!isset($this->modificationsCache[$since])){

			$this->modificationsCache[$since] = $this->modificationsDB->getModifications($site_id, $since);
		}
		return $this->modificationsCache[$since];
	}

	public function setModification($modification){
		$this->modificationsCache = [];
		return $this->modificationsDB->setModification($modification);
	}

}