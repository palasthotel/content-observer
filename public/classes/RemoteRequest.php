<?php


namespace Palasthotel\WordPress\ContentObserver;


use Palasthotel\WordPress\ContentObserver\Components\Component;
use WP_Error;

defined( 'ABSPATH' ) || exit;

class RemoteRequest extends Component {

	/*
	 * The wp_safe_ variants refuse internal addresses and unusual ports, so a
	 * registered site URL cannot point requests at this server's network. For
	 * sites that really are internal, allow them with the core filter
	 * http_request_host_is_external.
	 */
	public function getHeaders(){
		// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.DynamicHooknameFound -- content_observer_remote_request_header
		return apply_filters(Plugin::FILTER_REMOTE_REQUEST_HEADER,[]);
	}

	/**
	 * @param $url
	 * @param $site_api_key
	 *
	 * @return object|WP_Error
	 */
	public function get( $url, $site_api_key ) {
		$headers = $this->getHeaders();
		$args = array(
			'headers' => $headers,
		);
		$finalUrl = add_query_arg( Plugin::REQUEST_PARAM_API_KEY, $site_api_key, $url );
		$response = wp_safe_remote_get( $finalUrl, $args );
		if($response instanceof WP_Error) return $response;
		return json_decode($response['body']);
	}

	/**
	 * @param string $url
	 * @param string $site_api_key
	 * @param array $data
	 *
	 * @return object|WP_Error
	 */
	public function post( $url, $site_api_key, $data ) {
		$headers = $this->getHeaders();
		$response = wp_safe_remote_post(
			add_query_arg( Plugin::REQUEST_PARAM_API_KEY, $site_api_key, $url ),
			[
				"body" => $data,
				"headers" => $headers,
			]
		);
		if($response instanceof WP_Error) return $response;
		return json_decode($response['body']);
	}

}
