<?php


namespace Palasthotel\WordPress\ContentObserver;


use Palasthotel\WordPress\ContentObserver\Model\Modification;
use Palasthotel\WordPress\ContentObserver\Model\Site;
use WP_REST_Request;

class REST extends _Component {

	const NAMESPACE = "content-sync/v1";

	public function onCreate() {
		parent::onCreate();
		// TODO: create rest API
		// -- ping
		// -- register-observer
		// -- unregister-observer


		add_action( 'rest_api_init', [ $this, "init_rest_api" ] );
	}

	/**
	 * @param Site $site
	 *
	 * @return string
	 */
	public function getRestBaseUrl( $site ) {
		return trim( $site->url ) . "/" . rest_get_url_prefix() . "/" . self::NAMESPACE;
	}

	/**
	 * @param Site $site
	 *
	 * @return string
	 */
	public function getPingUrl( $site ) {
		return $this->getRestBaseUrl( $site ) . "/ping";
	}

	/**
	 * @param Site $site
	 *
	 * @return string
	 */
	public function getConnectUrl( $site ) {
		return $this->getRestBaseUrl( $site ) . "/connect";
	}

	/**
	 * @param Site $site
	 *
	 * @return string
	 */
	public function getModificationsUrl( $site ) {
		return $this->getRestBaseUrl( $site ) . "/modifications";
	}

	public function init_rest_api() {
		register_rest_route(
			static::NAMESPACE,
			'/ping',
			array(
				'methods'             => "GET",
				'callback'            => [ $this, 'ping' ],
				'permission_callback' => function ( WP_REST_Request $request ) {
					return $request->get_param( Plugin::REQUEST_PARAM_API_KEY ) === $this->plugin->settings->getApiKey();
				},
			)
		);
		register_rest_route(
			static::NAMESPACE,
			'/sites',
			array(
				'methods'             => "GET",
				'callback'            => function () {
					return array_map( function ( $site ) {
						return $site->asArray();
					}, $this->plugin->repo->getSites() );
				},
				'permission_callback' => function ( WP_REST_Request $request ) {
					return current_user_can( 'manage_options' );
				},
			)
		);
		register_rest_route(
			static::NAMESPACE,
			'/connect',
			array(
				'methods'             => "POST",
				'callback'            => [ $this, 'connect' ],
				'permission_callback' => function ( WP_REST_Request $request ) {
					return $request->get_param( Plugin::REQUEST_PARAM_API_KEY ) === $this->plugin->settings->getApiKey();
				},
				'args'                => [
					"site_url"        => array(
						'default'           => "",
						'validate_callback' => function ( $value, $request, $param ) {
							return filter_var( $value, FILTER_VALIDATE_URL );
						},
						'sanitize_callback' => function ( $value, $request, $param ) {
							return esc_url( $value );
						},
					),
					"foreign_api_key" => [
						'sanitize_callback' => function ( $value, $request, $param ) {
							return sanitize_text_field( $value );
						},
					],
					"relation_type"   => [
						'validate_callback' => function ( $value, $request, $param ) {
							return $value === Site::OBSERVER || Site::OBSERVABLE;
						},
					]
				]
			)
		);
		register_rest_route(
			static::NAMESPACE,
			'/modifications',
			array(
				'methods'             => "POST",
				'callback'            => array( $this, 'post_modifications' ),
				'permission_callback' => function ( WP_REST_Request $request ) {
					return $request->get_param( Plugin::REQUEST_PARAM_API_KEY ) === $this->plugin->settings->getApiKey();
				},
				'args'                => [
					"site_url" => [
						'validate_callback' => function ( $value, $request, $param ) {
							if ( ! filter_var( $value, FILTER_VALIDATE_URL ) ) {
								return false;
							}
							$site = $this->plugin->repo->findSiteByUrl( rtrim( esc_url( $value ), "/" ) . "/" );

							return $site instanceof Site;
						},
					],
					"mods"     => array(
						'default'           => [],
						'validate_callback' => function ( $value, $request, $param ) {
							if ( ! is_array( $value ) ) {
								return false;
							}
							foreach ( $value as $mod ) {
								if ( ! is_array( $mod ) ) {
									return false;
								}
								if ( empty( $mod["content_id"] ) ) {
									return false;
								}
								if ( empty( $mod["content_type"] ) ) {
									return false;
								}
								if ( empty( $mod["type"] ) ) {
									return false;
								}
							}

							return true;
						},
					),
				]
			)
		);
		register_rest_route(
			static::NAMESPACE,
			'/modifications',
			array(
				'methods'             => "GET",
				'callback'            => array( $this, 'get_modifications' ),
				'permission_callback' => function ( WP_REST_Request $request ) {
					return $request->get_param( Plugin::REQUEST_PARAM_API_KEY ) === $this->plugin->settings->getApiKey();
				},
				'args'                => [
					"number"     => [
						'default'           => 100,
						'validate_callback' => function ( $value, $request, $param ) {
							return "" . $value === intval( $value ) . "" && intval( $value ) > 0;
						},
						'sanitize_callback' => function ( $value, $request, $param ) {
							return intval( $value );
						}
					],
					"page"       => [
						'default'           => 1,
						'validate_callback' => function ( $value, $request, $param ) {
							return "" . $value === intval( $value ) . "" && intval( $value ) > 0;
						},
						'sanitize_callback' => function ( $value, $request, $param ) {
							return intval( $value );
						}
					],
					"post_types" => array(
						'default'           => [],
						'validate_callback' => function ( $value, $request, $param ) {
							if ( ! is_array( $value ) ) {
								return false;
							}

							foreach ( $value as $post_type ) {
								if ( ! is_string( $post_type ) || ! in_array( $post_type, get_post_types( [ "public" => true ] ) ) ) {
									return false;
								}
							}

							return true;
						},
					),
					"since"      => array(
						"default"           => 0,
						'sanitize_callback' => function ( $value, $request, $param ) {
							return intval( $value );
						},
					)
				]
			)
		);
	}

	public function ping( WP_REST_Request $request ) {
		return [ "response" => "pong" ];
	}

	public function connect( WP_REST_Request $request ) {
		$siteUrl       = $request->get_param( "site_url" );
		$foreignApiKey = $request->get_param( "foreign_api_key" );
		$relationType  = $request->get_param( "relation_type" );
		$site          = Site::build( $siteUrl )
		                     ->setApiKey( $foreignApiKey )
		                     ->setRelationType( $relationType );
		$dbSite        = $this->plugin->repo->findSiteByUrl( $site->url );

		if ( $dbSite instanceof Site && $dbSite->equals( $site ) ) {
			return [
				"success" => true,
			];
		}

		if ( $dbSite instanceof $site ) {
			$success = $this->plugin->repo->setSite(
				$dbSite
					->setApiKey( $foreignApiKey )
					->setRelationType( $relationType )
			);
		} else {
			$success = $this->plugin->repo->setSite( $site );
		}

		return [
			"success" => true === $success || $success > 0,
		];
	}

	public function post_modifications( WP_REST_Request $request ) {
		$site_url = $request->get_param( "site_url" );
		$site     = $this->plugin->repo->findSiteByUrl( $site_url );
		if ( null === $site ) {
			error_log( "Site not found to set modifications for $site_url" );

			return new \WP_REST_Response( [
				"success" => false,
				"message" => "Site not found"
			], 404 );
		}

		$mods = $request->get_param( "mods" );
		$time = time();
		foreach ( $mods as $mod ) {
			$mod["site_id"]  = $site->id;
			$mod["modified"] = $time;
			$this->plugin->repo->setModification( Modification::fromArray( $mod ) );
		}

		$site->setLastNotificationTime( $time );
		$this->plugin->repo->setSite( $site );

		return [
			"success" => true,
		];
	}

	public function get_modifications( WP_REST_Request $request ) {
		// provide all modifications
		$post_types           = $request->get_param( "post_types" );
		$since                = $request->get_param( "since" );
		$page                 = $request->get_param( "page" );
		$limitPerPage         = $request->get_param( "number" );
		$modsCount            = $this->plugin->repo->countModifications( $since );
		$mods                 = $this->plugin->repo->getModifications( $since, Site::MY_SITE, $limitPerPage, $page - 1 );
		$postTypeFilteredMods = array_filter( $mods, function ( $mod ) use ( $post_types ) {
			return empty( $post_types ) || in_array( get_post_type( $mod->content_type ), $post_types );
		} );

		$responseMods = array_map( function ( $mod ) {
			return $mod->asArray();
		}, $postTypeFilteredMods );

		return [
			"success" => true,
			"mods"    => $responseMods,
			"page"    => $page,
			"pages"   => ceil( $modsCount / $limitPerPage ),
		];
	}
}