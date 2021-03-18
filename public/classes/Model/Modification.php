<?php

namespace Palasthotel\WordPress\ContentObserver\Model;

/**
 * @property string $content_id
 * @property string $content_type
 * @property string $type
 * @property int $modified
 * @property int site_id
 */
class Modification {

	const CREATE = "create";
	const UPDATE = "update";
	const DELETE = "delete";

	/**
	 * Modification constructor.
	 *
	 * @param string $content_id
	 * @param string $content_type
	 */
	private function __construct( $content_id, $content_type ) {
		$this->site_id      = 0; // 0 is this site
		$this->content_id   = $content_id;
		$this->content_type = $content_type;
		$this->setModified( time() );
		$this->setType( static::CREATE );
	}

	/**
	 * @param string $content_id
	 * @param string $content_type
	 *
	 * @return Modification
	 */
	public static function build( $content_id, $content_type ) {
		return new Modification( $content_id, $content_type );
	}

	/**
	 * @param int $site_id
	 *
	 * @return $this
	 */
	public function setSiteId( $site_id ) {
		$this->site_id = $site_id;

		return $this;
	}

	public function setModified( $timestamp ) {
		$this->modified = intval( $timestamp );

		return $this;
	}

	public function setType( $type ) {
		$this->type = $type;

		return $this;
	}

	public function create() {
		return $this->setType( static::CREATE );
	}

	public function update() {
		return $this->setType( static::UPDATE );
	}

	public function delete() {
		return $this->setType( static::DELETE );
	}

	public static function fromArray( $arr ) {
		return Modification::build( $arr["content_id"], $arr["content_type"] )
		                   ->setModified( intval( $arr["modified"] ) )
		                   ->setType( $arr["type"] )
		                   ->setSiteId( intval( $arr["site_id"] ) );
	}

	public function asArray() {
		return [
			"site_id"      => $this->site_id,
			"content_id"   => $this->content_id,
			"content_type" => $this->content_type,
			"type"         => $this->type,
			"modified"     => $this->modified,
		];
	}
}