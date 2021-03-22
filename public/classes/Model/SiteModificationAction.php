<?php


namespace Palasthotel\WordPress\ContentObserver\Model;


class SiteModificationAction {
	/**
	 * @var Modification[] $modifications
	 */
	var $modifications;
	/**
	 * @var Site
	 */
	var $site;

	/**
	 * SiteModificationAction constructor.
	 *
	 * @param $site
	 * @param $mods
	 */
	private function __construct($site, $mods) {
		$this->modifications = $mods;
		$this->site = $site;
	}

	/**
	 * @param Site $site
	 * @param Modification[] $mods
	 *
	 * @return SiteModificationAction
	 */
	public static function build($site, $mods = []){
		return new self($site, $mods);
	}
}