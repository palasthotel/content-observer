<?php

namespace Palasthotel\WordPress\ContentObserver\Model;


/**
 * @property null|int id
 * @property string domain
 * @property bool ssl
 * @property string api_key
 * @property int registration_time
 * @property null last_notification_time
 * @property string relationType
 */
class Site {

	const OBSERVER = "observer";
	const OBSERVABLE = "observable";

	private function __construct( $domain, $ssl = true ) {
		$this->id                     = null;
		$this->domain                 = $domain;
		$this->ssl                    = $ssl;
		$this->api_key                = "";
		$this->registration_time      = time();
		$this->last_notification_time = 0;
		$this->relationType           = static::OBSERVABLE;
	}

	public static function build( $url, $ssl = true ) {
		return new Site( $url, $ssl );
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
		$this->relationType = $type;

		return $this;
	}

	public function hasSSL() {
		return $this->ssl;
	}

	public function observable() {
		return $this->setRelationType( static::OBSERVABLE );
	}

	public function observer() {
		return $this->setRelationType( static::OBSERVER );
	}

	public function isObservable() {
		return static::OBSERVABLE === $this->type;
	}

	public function isObserver() {
		return static::OBSERVER === $this->type;
	}

	public function asArray() {
		return [
			"id"                     => $this->id,
			"domain"                 => $this->domain,
			"ssl"                    => $this->ssl,
			"relation_type"          => $this->relationType,
			"api_key"                => $this->api_key,
			"registration_time"      => $this->registration_time,
			"last_notification_time" => $this->last_notification_time,
		];
	}
}