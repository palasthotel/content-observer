<?php

namespace Palasthotel\WordPress\ContentObserver\Model;

class Site {

	const MY_SITE = 0;

	const OBSERVER = "observer";
	const OBSERVABLE = "observable";
	const BOTH = "both";
	public int $id;
	public string $slug;
	public string $url;
	public string $api_key;
	public int $registration_time;
	public int $last_notification_time;
	public string $relation_type;

	private function __construct( $url ) {
		$this->id                     = static::MY_SITE;
		$this->slug                   = "$this->id";
		$this->url                    = rtrim( $url, "/" ) . "/";
		$this->api_key                = "";
		$this->registration_time      = time();
		$this->last_notification_time = 0;
		$this->relation_type          = static::OBSERVABLE;
	}

	public static function build( $url ): static {
		return new Site( $url );
	}

	public function setId( int|string $id ): self {
		$this->id = intval( $id );

		return $this;
	}

	public function setSlug( string $slug ): self {
		$this->slug = $slug;

		return $this;
	}

	public function setApiKey( string $api_key ): self {
		$this->api_key = $api_key;

		return $this;
	}

	public function setRegistrationTime( int|string $time ): self {
		$this->registration_time = intval( $time );

		return $this;
	}

	public function setLastNotificationTime( int|string $time ): self {
		$this->last_notification_time = intval( $time );

		return $this;
	}

	public function setRelationType( string $type ): self {
		$this->relation_type = $type;

		return $this;
	}

	public function observable(): self {
		return $this->setRelationType( static::OBSERVABLE );
	}

	public function observer(): self {
		return $this->setRelationType( static::OBSERVER );
	}

	public function isObservable(): bool {
		return static::OBSERVABLE === $this->relation_type || static::BOTH == $this->relation_type;
	}

	public function isObserver(): bool {
		return static::OBSERVER === $this->relation_type || static::BOTH == $this->relation_type;
	}

	public function asArray(): array {
		return [
			"id"                     => $this->id,
			"slug"                   => $this->slug,
			"url"                    => $this->url,
			"relation_type"          => $this->relation_type,
			"api_key"                => $this->api_key,
			"registration_time"      => $this->registration_time,
			"last_notification_time" => $this->last_notification_time,
		];
	}

	public function equals( Site $site ): bool {
		return $site->url == $this->url &&
		       $site->api_key == $this->api_key &&
		       $site->relation_type === $this->relation_type;
	}
}
