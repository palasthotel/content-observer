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
	 * @param int|null $timestamp
	 */
	private function __construct( $content_id, $content_type, $timestamp = null ) {
		$this->site_id = 0; // 0 is this site
		$this->content_id = $content_id;
		$this->content_type = $content_type;
		$this->setModified( $timestamp ? $timestamp : time() );
		$this->setType( static::CREATE );
	}

	/**
	 * @param string $content_id
	 * @param string $content_type
	 * @param int|null $timestamp
	 *
	 * @return Modification
	 */
	public static function build($content_id, $content_type, $timestamp = null){
		return new Modification($content_id, $content_type, $timestamp);
	}

	/**
	 * @param int $site_id
	 *
	 * @return $this
	 */
	public function setSiteId($site_id){
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

	public function asArray(){
		return [
			"content_id" => $this->content_id,
			"content_type" => $this->content_type,
			"type" => $this->type,
			"modified" => $this->modified,
		];
	}
}