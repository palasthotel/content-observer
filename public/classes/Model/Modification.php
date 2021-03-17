<?php


namespace Palasthotel\WordPress\ContentObserver\Model;


/**
 * @property int $post_id
 * @property string type
 * @property int modified
 */
class Modification {

	const CREATE = "create";
	const UPDATE = "update";
	const DELETE = "delete";

	/**
	 * Modification constructor.
	 *
	 * @param int $post_id
	 * @param int|null $timestamp
	 */
	private function __construct( $post_id, $timestamp = null ) {
		$this->post_id = intval( $post_id );
		$this->setModified( $timestamp ? $timestamp : time() );
		$this->setType( static::CREATE );
	}

	/**
	 * @param int $post_id
	 *
	 * @param int|null $timestamp
	 *
	 * @return Modification
	 */
	public static function build($post_id, $timestamp = null){
		return (new Modification($post_id, $timestamp));
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
			"post_id" => $this->post_id,
			"type" => $this->type,
			"modified" => $this->modified,
		];
	}
}