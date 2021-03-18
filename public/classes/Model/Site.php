<?php

namespace Palasthotel\WordPress\ContentObserver\Model;


/**
 * @property null|int id
 * @property string url
 * @property string api_key
 * @property int registration_time
 * @property null|int last_notification_time
 * @property string $relation_type
 */
class Site {

	const MY_SITE = 0;

	const OBSERVER = "observer";
	const OBSERVABLE = "observable";

	private function __construct( $url ) {
		$this->id                     = static::MY_SITE;
		$this->url                    = rtrim( $url, "/" ) . "/";
		$this->api_key                = "";
		$this->registration_time      = time();
		$this->last_notification_time = 0;
		$this->relation_type          = static::OBSERVABLE;
	}

	public static function build( $url ) {
		return new Site( $url );
	}

	public function setId( $id ) {
		$this->id = intval( $id );

		return $this;
	}

	public function setApiKey( $api_key ) {
		$this->api_key = $api_key;

		return $this;
	}

	public function setRegistrationTime( $time ) {
		$this->registration_time = intval( $time );

		return $this;
	}

	public function setLastNotificationTime( $time ) {
		$this->last_notification_time = intval( $time );

		return $this;
	}

	public function setRelationType( $type ) {
		$this->relation_type = $type;

		return $this;
	}

	public function observable() {
		return $this->setRelationType( static::OBSERVABLE );
	}

	public function observer() {
		return $this->setRelationType( static::OBSERVER );
	}

	public function isObservable() {
		return static::OBSERVABLE === $this->relation_type;
	}

	public function isObserver() {
		return static::OBSERVER === $this->relation_type;
	}

	public function asArray() {
		return [
			"id"                     => $this->id,
			"url"                    => $this->url,
			"relation_type"          => $this->relation_type,
			"api_key"                => $this->api_key,
			"registration_time"      => $this->registration_time,
			"last_notification_time" => $this->last_notification_time,
		];
	}

	/**
	 * @param Site $site
	 *
	 * @return bool
	 */
	public function equals( $site ) {
		return $site->url == $this->url &&
		       $site->api_key == $this->api_key &&
		       $site->relation_type === $this->relation_type;
	}
}