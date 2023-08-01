<?php

namespace Palasthotel\WordPress\ContentObserver\Model;

class ModQueryArgs {

	public int $per_page = 100;
	public int $page = 1;

	public ?int $site_id = null;
	public ?int $since = null;
	public ?string $content_id = null;
	/**
	 * @var string[]
	 */
	public array $content_types = [];
	/**
	 * @var string[]
	 */
	public array $modification_types = [];

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
		$this->modification_types = [$value];

		return $this;
	}


}
