<?php


namespace Palasthotel\WordPress\ContentObserver;


use WP_Error;

class RemoteRequest extends _Component {

	/**
	 * @param $url
	 * @param $site_api_key
	 *
	 * @return object|WP_Error
	 */
	public function get( $url, $site_api_key ) {
		$response = wp_remote_get( add_query_arg( Plugin::REQUEST_PARAM_API_KEY, $site_api_key, $url ) );
		if($response instanceof WP_Error) return $response;
		return json_decode($response['body']);
	}

	/**
	 * @param $url
	 * @param $site_api_key
	 * @param $data
	 *
	 * @return object|WP_Error
	 */
	public function post( $url, $site_api_key, $data ) {
		$response = wp_remote_post(
			add_query_arg( Plugin::REQUEST_PARAM_API_KEY, $site_api_key, $url ),
			[
				"body" => $data
			]
		);
		if($response instanceof WP_Error) return $response;
		return json_decode($response['body']);
	}

}