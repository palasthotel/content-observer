<?php


namespace Palasthotel\WordPress\ContentObserver;


use Palasthotel\WordPress\ContentObserver\Components\Component;
use Palasthotel\WordPress\ContentObserver\Database\Modifications;
use Palasthotel\WordPress\ContentObserver\Database\Sites;
use Palasthotel\WordPress\ContentObserver\Model\Modification;
use Palasthotel\WordPress\ContentObserver\Model\ModQueryArgs;
use Palasthotel\WordPress\ContentObserver\Model\Site;

class Repository extends Component {

	private Modifications $modificationsDB;
	private Sites $sitesDB;
	/**
	 * @var Site[]|null
	 */
	private ?array $sitesCache = null;


	public function onCreate() {
		parent::onCreate();

		$this->modificationsDB = new Modifications();
		$this->sitesDB         = new Sites();
	}

	/**
	 * initialize repo dependencies
	 */
	public function init(){
		// order matteres because modifications have foreign key for sites
		$this->sitesDB->createTable();
		$this->modificationsDB->createTables();

	}

	/**
	 * @return Site[]
	 */
	public function getSites(): array{
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
	public function getSite($id): ? Site {
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
		$found = array_values(array_filter($this->getSites(),function($site) use ($url){
			return $site->url === rtrim($url,"/")."/";
		}));
		return count($found) > 0 ? $found[0] : null;
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
	 *
	 * @return Modification[]
	 */
	public function getModifications(ModQueryArgs $args): array {

		return $this->modificationsDB->getModifications($args);
	}

	public function countModifications( ModQueryArgs $args ): int {
		return $this->modificationsDB->countModifications($args);
	}

	/**
	 * @param Modification $modification
	 *
	 * @return bool|int
	 */
	public function setModification($modification){
		return $this->modificationsDB->setModification($modification);
	}

	/**
	 * @param int $site_id
	 *
	 * @return bool|int
	 */
	public function deleteSite( $site_id ) {
		$this->sitesCache = null;
		$this->modificationsDB->deleteBySiteId($site_id);
		return $this->sitesDB->delete($site_id);
	}


}
