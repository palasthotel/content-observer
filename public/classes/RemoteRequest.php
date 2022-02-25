<?php


namespace Palasthotel\WordPress\ContentObserver;


use WP_Error;

class RemoteRequest extends _Component {

	public function getHeaders(){
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
		$response = wp_remote_get(
			add_query_arg( Plugin::REQUEST_PARAM_API_KEY, $site_api_key, $url ),
			$args
		);
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
		$response = wp_remote_post(
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