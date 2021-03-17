<?php


namespace Palasthotel\WordPress\ContentObserver;


use Palasthotel\WordPress\ContentObserver\Model\Modification;
use WP_REST_Request;

class REST extends _Component {

	const NAMESPACE = "content-sync/v1";

	public function onCreate() {
		parent::onCreate();
		// TODO: create rest API
		// -- ping
		// -- register-observer
		// -- unregister-observer


		add_action( 'rest_api_init', array( $this, 'init_rest_api' ) );
	}

	public function init_rest_api() {
		register_rest_route(
			static::NAMESPACE,
			'/ping',
			array(
				'methods'             => "GET",
				'callback'            => [ $this, 'ping' ],
				'permission_callback' => function ( WP_REST_Request $request ) {
					return $request->get_header( Plugin::REQUEST_HEADER_AUTH ) === $this->plugin->settings->getApiKey();
				},
			)
		);
		register_rest_route(
			static::NAMESPACE,
			'/sites',
			array(
				'methods'             => "GET",
				'callback'            => function () {
					return $this->plugin->repo->getSites();
				},
				'permission_callback' => function ( WP_REST_Request $request ) {
					return current_user_can( 'manage_options' );
				},
			)
		);
		register_rest_route(
			static::NAMESPACE,
			'/observer',
			array(
				'methods'             => "POST",
				'callback'            => [ $this, 'register_observer' ],
				'permission_callback' => function ( WP_REST_Request $request ) {
					return $request->get_header( Plugin::REQUEST_HEADER_AUTH ) === $this->plugin->settings->getApiKey();
				},
				'args'                => [
					"site_url" => array(
						'default'           => "",
						'validate_callback' => function ( $value, $request, $param ) {
							return filter_var( $value, FILTER_VALIDATE_URL );
						},
					),
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
					return $request->get_header( Plugin::REQUEST_HEADER_AUTH ) === $this->plugin->settings->getApiKey();
				},
				'args'                => [
					"mods" => array(
						'default'           => [],
						'validate_callback' => function ( $value, $request, $param ) {

							if ( ! is_array( $value ) ) {
								return false;
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
					return $request->get_header( Plugin::REQUEST_HEADER_AUTH ) === $this->plugin->settings->getApiKey();
				},
				'args'                => [
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
						"default" => 0,
						'sanitize_callback' => function ( $value, $request, $param ) {
							return intval( $value );
						},
					)
				]
			)
		);
	}

	public function ping( WP_REST_Request $request ) {
		return [
			"response" => "pong"
		];
	}

	public function get_modifications( WP_REST_Request $request ) {
		// provide all modifications
		$post_types           = $request->get_param( "post_types" );
		$since                = $request->get_param( "since" );
		$mods                 = $this->plugin->repo->getModifications(null, $since );
		$postTypeFilteredMods = array_filter( $mods, function ( $mod ) use ( $post_types ) {
			return empty( $post_types ) || in_array( get_post_type( $mod->post_id ), $post_types );
		} );

		return $response = $this->modificationsToResponse( $postTypeFilteredMods );
	}

	public function post_modifications( WP_REST_Request $request ) {
		// TOTO: receive notifications from observed site
		return [];
	}

	/**
	 * @param Modification[] $modifications
	 *
	 * @return void[]
	 */
	private function modificationsToResponse( $modifications ) {
		return array_map( function ( $mod ) {
			return array_merge(
				$mod->asArray(),
				[ "post_type" => get_post_type( $mod->post_id ) ]
			);
		}, $modifications );
	}
}