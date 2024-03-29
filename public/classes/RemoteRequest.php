<?php


namespace Palasthotel\WordPress\ContentObserver;


use Palasthotel\WordPress\ContentObserver\Components\Component;
use WP_Error;

class RemoteRequest extends Component {

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
		$finalUrl = add_query_arg( Plugin::REQUEST_PARAM_API_KEY, $site_api_key, $url );
		$response = wp_remote_get( $finalUrl, $args );
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
