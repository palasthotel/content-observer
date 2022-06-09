<?php


namespace Palasthotel\WordPress\ContentObserver;


use Palasthotel\WordPress\ContentObserver\Model\Modification;
use Palasthotel\WordPress\ContentObserver\Model\ModQueryArgs;
use Palasthotel\WordPress\ContentObserver\Model\Site;
use WP_REST_Request;
use WP_REST_Server;

class REST extends _Component {

	const NAMESPACE = "content-sync/v1";

	public function onCreate() {
		parent::onCreate();
		// TODO: -- unregister-observer

		add_action( 'rest_api_init', [ $this, "init_rest_api" ] );
	}

	/**
	 * @param string $site_url
	 *
	 * @return string
	 */
	public function getRestBaseUrl( $site_url ) {
		return trim( $site_url ) . "/" . rest_get_url_prefix() . "/" . self::NAMESPACE;
	}

	/**
	 * @param string $site_url
	 *
	 * @return string
	 */
	public function getPingUrl( $site_url ) {
		return $this->getRestBaseUrl( $site_url ) . "/ping";
	}

	/**
	 * @param string $site_url
	 *
	 * @return string
	 */
	public function getConnectUrl( $site_url ) {
		return $this->getRestBaseUrl( $site_url ) . "/connect";
	}

	/**
	 * @param string $site_url
	 *
	 * @return string
	 */
	public function getModificationsUrl( $site_url ) {
		return $this->getRestBaseUrl( $site_url ) . "/modifications";
	}

	public function init_rest_api() {
		register_rest_route(
			static::NAMESPACE,
			'/ping',
			array(
				'methods'             => WP_REST_Server::READABLE,
				'callback'            => [ $this, 'ping' ],
				'permission_callback' => function ( WP_REST_Request $request ) {
					return $request->get_param( Plugin::REQUEST_PARAM_API_KEY ) === $this->plugin->settings->getApiKey();
				},
				'args'                => [
					'site_id'      => [
						'required'          => false,
						'validate_callback' => function ( $value, $request, $param ) {
							return intval( $value ) . "" === $value . "";
						},
					],
					'site_url'     => [
						'required'          => false,
						'validate_callback' => function ( $value, $request, $param ) {
							return filter_var( $value, FILTER_VALIDATE_URL );;
						},
					],
					'site_api_key' => [
						'required'          => false,
						'sanitize_callback' => function ( $value, $request, $param ) {
							return sanitize_text_field( $value );
						},
					],
				],
			)
		);
		register_rest_route(
			static::NAMESPACE,
			'/sites',
			array(
				'methods'             => WP_REST_Server::READABLE,
				'callback'            => [ $this, 'get_sites' ],
				'permission_callback' => function ( WP_REST_Request $request ) {
					return current_user_can( 'manage_options' );
				},
			)
		);
		register_rest_route(
			static::NAMESPACE,
			'/sites',
			array(
				'methods'             => WP_REST_Server::CREATABLE,
				'callback'            => [ $this, 'post_sites' ],
				'permission_callback' => function ( WP_REST_Request $request ) {
					return current_user_can( 'manage_options' );
				},
				'args'                => [
					"dirty_sites" => array(
						'validate_callback' => function ( $value, $request, $param ) {
							if ( ! is_array( $value ) ) {
								return false;
							}

							foreach ( $value as $site ) {
								if ( ! isset( $site["id"] ) && $site["id"] !== null ) {
									return false;
								}
								if ( intval( $site["id"] ) < 0 ) {
									return false;
								}
								if ( ! isset( $site["slug"] ) ) {
									return false;
								}
								if ( ! isset( $site["url"] ) || ! filter_var( $site["url"], FILTER_VALIDATE_URL ) ) {
									return false;
								}
								if ( ! isset( $site["api_key"] ) || empty( $site["api_key"] ) ) {
									return false;
								}
								if ( ! isset( $site["relation_type"] ) ) {
									return false;
								}
								if ( ! in_array( $site["relation_type"], [
									Site::OBSERVER,
									Site::OBSERVABLE,
									Site::BOTH
								] ) ) {
									return false;
								}
							}

							$slugs = array_unique( array_map( function ( $site ) {
								return $site["slug"];
							}, $value ) );
							if ( count( $slugs ) !== count( $value ) ) {
								return false;
							}

							return true;
						},
						'sanitize_callback' => function ( $value, $request, $param ) {
							return array_map( function ( $item ) {
								$item["url"] = esc_url( $item["url"] );

								return $item;
							}, $value );
						},
					),
					"deletes"     => array(
						'validate_callback' => function ( $value, $request, $param ) {
							if ( ! is_array( $value ) ) {
								return false;
							}

							return array_filter( $value, function ( $int ) {
								return "" . $int === intval( $int ) . "";
							} );
						},
					),
				]
			)
		);
		register_rest_route(
			static::NAMESPACE,
			'/connect',
			array(
				'methods'             => WP_REST_Server::CREATABLE,
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
				'methods'             => WP_REST_Server::CREATABLE,
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
				'methods'             => WP_REST_Server::READABLE,
				'callback'            => array( $this, 'get_modifications' ),
				'permission_callback' => function ( WP_REST_Request $request ) {
					return $request->get_param( Plugin::REQUEST_PARAM_API_KEY ) === $this->plugin->settings->getApiKey();
				},
				'args'                => [
					'site_id'       => [
						'required'          => false,
						'default'           => Site::MY_SITE,
						'validate_callback' => function ( $value, $request, $param ) {
							return intval( $value ) . "" === $value . "";
						},
					],
					"number"        => [
						'default'           => 100,
						'validate_callback' => function ( $value, $request, $param ) {
							return "" . $value === intval( $value ) . "" && intval( $value ) > 0;
						},
						'sanitize_callback' => function ( $value, $request, $param ) {
							return intval( $value );
						}
					],
					"page"          => [
						'default'           => 1,
						'validate_callback' => function ( $value, $request, $param ) {
							return "" . $value === intval( $value ) . "" && intval( $value ) > 0;
						},
						'sanitize_callback' => function ( $value, $request, $param ) {
							return intval( $value );
						}
					],
					"post_types"    => array(
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
					"content_types" => array(
						'default'           => [],
						'validate_callback' => function ( $value, $request, $param ) {
							if ( ! is_array( $value ) ) {
								return false;
							}

							foreach ( $value as $content_type ) {
								if ( ! is_string( $content_type ) ) {
									return false;
								}
							}

							return true;
						},
					),
					"since"         => array(
						"default"           => 0,
						'sanitize_callback' => function ( $value, $request, $param ) {
							return intval( $value );
						},
					)
				]
			)
		);
		register_rest_route(
			static::NAMESPACE,
			'/modifications/run',
			array(
				'methods'             => WP_REST_Server::READABLE,
				'callback'            => function () {
					$this->plugin->tasks->doModificationsHook( 1 );
				},
				'permission_callback' => function ( WP_REST_Request $request ) {
					return true;
				},
			)
		);
	}

	public function ping( WP_REST_Request $request ) {

		// ------------------------------------------------------
		// ping with site url
		// ------------------------------------------------------
		$site_url = $request->get_param( "site_url" );

		if ( ! empty( $site_url ) ) {
			$apiKey   = $request->get_param( "site_api_key" );
			$response = $this->plugin->remoteRequest->get( $this->getPingUrl( $site_url ), $apiKey );
			if ( $response instanceof \WP_Error ) {
				return [ "response" => $response->get_error_message() ];
			}

			return $response;
		}

		// ------------------------------------------------------
		// ping with site id
		// ------------------------------------------------------
		$site_id = intval( $request->get_param( "site_id" ) );
		if ( $site_id <= 0 ) {
			return [ "response" => "pong" ];
		}

		$site = $this->plugin->repo->getSite( $site_id );
		if ( ! ( $site instanceof Site ) ) {
			return [ "response" => "Site not found." ];
		}

		$response = $this->plugin->remoteRequest->get( $this->getPingUrl( $site->url ), $site->api_key );
		if ( $response instanceof \WP_Error ) {
			return [ "response" => $response->get_error_message() ];
		}

		return $response;
	}

	public function get_sites( WP_REST_Request $request ) {
		return array_map( function ( $site ) {
			return $site->asArray();
		}, $this->plugin->repo->getSites() );
	}

	public function post_sites( WP_REST_Request $request ) {

		// modify sites
		$dirtySites = $request->get_param( "dirty_sites" );
		$deletes    = $request->get_param( "deletes" );

		foreach ( $dirtySites as $site ) {
			if ( empty( $site["id"] ) || intval( $site["id"] ) <= 0 ) {
				$siteDb = $this->plugin->repo->findSiteByUrl( $site["url"] );
				if ( $siteDb instanceof Site ) {
					return new \WP_REST_Response( [
						"error"   => true,
						"message" => "Site with url $siteDb->url already exists.",
						"site"    => $siteDb->asArray(),
					], 401 );
				}
			}

			$site = Site::build( $site["url"] )
			            ->setId( $site["id"] )
			            ->setSlug( $site["slug"] )
			            ->setApiKey( $site["api_key"] )
			            ->setRelationType( $site["relation_type"] )
			            ->setRegistrationTime( time() );

			$this->plugin->repo->setSite( $site );

		}

		foreach ( $deletes as $site_id ) {
			$this->plugin->repo->deleteSite( intval( $site_id ) );
		}

		return $this->get_sites( $request );
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
			$success = $this->plugin->repo->setSite(
				$dbSite
					->setRegistrationTime( time() )
			);

			return [
				"success" => $success,
			];
		}

		if ( $dbSite instanceof $site ) {
			$success = $this->plugin->repo->setSite(
				$dbSite
					->setApiKey( $foreignApiKey )
					->setRelationType( $relationType )
					->setRegistrationTime( time() )
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
		$siteId        = $request->get_param( "site_id" );
		$post_types    = $request->get_param( "post_types" );
		$content_types = $request->get_param( "content_types" );
		$since         = $request->get_param( "since" );
		$page          = $request->get_param( "page" );
		$perPage       = $request->get_param( "number" );

		$args = ModQueryArgs::build();
		$args->page( $page );
		$args->perPage( $perPage );
		if ( $siteId >= 0 ) {
			$args->siteId( $siteId );
		}
		if ( $since > 0 ) {
			$args->since( $since );
		}
		if ( ! empty( $content_types ) ) {
			$args->contentTypes( $content_types );
		}
		$modsCount            = $this->plugin->repo->countModifications( $args );
		$mods                 = $this->plugin->repo->getModifications( $args );
		$postTypeFilteredMods = array_filter( $mods, function ( $mod ) use ( $post_types ) {
			return empty( $post_types ) || in_array( get_post_type( $mod->content_id ), $post_types );
		} );

		$responseMods = array_map( function ( $mod ) {
			return $mod->asArray();
		}, $postTypeFilteredMods );

		return [
			"success" => true,
			"mods"    => $responseMods,
			"page"    => $page,
			"pages"   => ceil( $modsCount / $perPage ),
		];
	}
}