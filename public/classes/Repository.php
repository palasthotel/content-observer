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
	 * @param int $id
	 *
	 * @return Site|null
	 */
	public function getSite($id){
		foreach ($this->getSites() as $site){
			if($id === $site->id){
				return $site;
			}
		}
		return null;
	}

	/**
	 * @param string $url
	 *
	 * @return Site|null
	 */
	public function findSiteByUrl($url){
		$found = array_filter($this->getSites(),function($site) use ($url){
			return $site->url === $url;
		});
		return count($found) ? $found[0] : null;
	}

	/**
	 * @param Site $site
	 *
	 * @return bool|int
	 */
	public function setSite( $site ) {
		$this->sitesCache = null;
		return $this->sitesDB->set($site);
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
	 * @param int $since null means own mods
	 *
	 * @param int $site_id
	 *
	 * @param int $limit
	 *
	 * @param int $pageIndex
	 *
	 * @return Modification[]
	 */
	public function getModifications($since = 0, $site_id = Site::MY_SITE, $limit = 100, $pageIndex = 0){
		if(!isset($this->modificationsCache[$since])){

			$this->modificationsCache[$since] = $this->modificationsDB->getModifications($since, $site_id, $limit, $pageIndex);
		}
		return $this->modificationsCache[$since];
	}

	/**
	 * @param int $since
	 * @param int $site_id
	 */
	public function countModifications( $since, $site_id = Site::MY_SITE ) {
		return $this->modificationsDB->countModifications($since, $site_id);
	}

	/**
	 * @param Modification $modification
	 *
	 * @return bool|int
	 */
	public function setModification($modification){
		$this->modificationsCache = [];
		return $this->modificationsDB->setModification($modification);
	}



}