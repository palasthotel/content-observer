<?php


namespace Palasthotel\WordPress\ContentObserver\Model;


class SiteModificationAction {
	/**
	 * @var Modification[] $modifications
	 */
	public array $modifications;
	public Site $site;

	/**
	 * @param Modification[] $mods
	 */
	private function __construct( Site $site, array $mods) {
		$this->modifications = $mods;
		$this->site = $site;
	}

	/**
	 * @param Modification[] $mods
	 */
	public static function build(Site $site, array $mods = []): self {
		return new static($site, $mods);
	}
}
