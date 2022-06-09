<?php

namespace Palasthotel\WordPress\ContentObserver\Model;

/**
 * @property int $per_page
 * @property int $page
 *
 * @property null|int $site_id
 * @property null|int $since
 * @property null|int $content_id
 * @property string[] $content_types
 * @property string[] $modification_types
 */
class ModQueryArgs {

	private function __construct() {
		$this->per_page = 100;
		$this->page     = 1;

		$this->site_id            = null;
		$this->since              = null;
		$this->content_id         = null;
		$this->content_types      = [];
		$this->modification_types = [];
	}


	public static function build(): ModQueryArgs {
		return new ModQueryArgs();
	}

	public function perPage( int $value ): self {
		$this->per_page = max( $value, 1 );

		return $this;
	}

	public function page( int $value ): self {
		$this->page = max( $value, 1 );

		return $this;
	}

	public function siteId( int $value ): self {
		$this->site_id = $value;

		return $this;
	}

	public function since( int $time ): self {
		$this->since = max( $time, 0 );

		return $this;
	}

	public function contentId( int $value ): self {
		$this->content_id = $value;

		return $this;
	}

	/**
	 * @param string[] $value
	 */
	public function contentTypes( array $value ): self {
		$this->content_types = $value;

		return $this;
	}

	public function modificationType( string $value ): self {
		$this->modification_types = $value;

		return $this;
	}


}