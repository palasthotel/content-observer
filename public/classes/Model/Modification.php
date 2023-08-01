<?php

namespace Palasthotel\WordPress\ContentObserver\Model;

class Modification {

	const CREATE = "create";
	const UPDATE = "update";
	const DELETE = "delete";
	public int $site_id;
	public string $content_id;
	public string $content_type;
	public string $type;
	public int $modified;

	private function __construct( string $content_id, string $content_type ) {
		$this->site_id      = 0; // 0 is this site
		$this->content_id   = $content_id;
		$this->content_type = $content_type;
		$this->setModified( time() );
		$this->setType( static::CREATE );
	}

	public static function build( string $content_id, string $content_type ): Modification {
		return new Modification( $content_id, $content_type );
	}

	public function setSiteId( int $site_id ): self {
		$this->site_id = $site_id;

		return $this;
	}

	public function setModified( int|string $timestamp ): self {
		$this->modified = intval( $timestamp );

		return $this;
	}

	public function setType( string $type ): self {
		$this->type = $type;

		return $this;
	}

	public function create(): self {
		return $this->setType( static::CREATE );
	}

	public function update(): self {
		return $this->setType( static::UPDATE );
	}

	public function delete(): self {
		return $this->setType( static::DELETE );
	}

	public static function fromArray( $arr ): Modification {
		return Modification::build( $arr["content_id"], $arr["content_type"] )
		                   ->setModified( intval( $arr["modified"] ) )
		                   ->setType( $arr["type"] )
		                   ->setSiteId( intval( $arr["site_id"] ) );
	}

	public function asArray(): array {
		return [
			"site_id"      => $this->site_id,
			"content_id"   => $this->content_id,
			"content_type" => $this->content_type,
			"type"         => $this->type,
			"modified"     => $this->modified,
		];
	}
}
