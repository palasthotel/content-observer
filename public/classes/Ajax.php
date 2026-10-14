<?php

namespace Palasthotel\WordPress\ContentObserver;

use Palasthotel\WordPress\ContentObserver\Components\Component;

class Ajax extends Component {

	const NONCE_ACTION = "content_observer_ajax";

	public function onCreate() {
		parent::onCreate();

		add_action( 'wp_ajax_content_observer_fetch_modifications', [ $this, 'fetch_modifications' ] );
		add_action( 'wp_ajax_content_observer_notify', [ $this, 'notify' ] );
		add_action( 'wp_ajax_content_observer_apply', [ $this, 'apply' ] );
	}

	/**
	 * All three actions change data or contact other sites, so they need an
	 * administrator and a nonce - without it any page could trigger them in an
	 * administrator's browser.
	 */
	private function authorize(): void {
		if ( ! check_ajax_referer( self::NONCE_ACTION, false, false ) ) {
			wp_send_json_error( [ "message" => "Invalid nonce" ], 403 );
		}
		if ( ! current_user_can( "manage_options" ) ) {
			wp_send_json_error( [ "message" => "No permission" ], 403 );
		}
	}

	public function fetch_modifications(): void {
		$this->authorize();
		$numberOfModifications = $this->plugin->tasks->fetch( $this->getSiteId() );
		wp_send_json_success( [
			"number_of_modifications" => $numberOfModifications,
		] );
	}

	public function notify(): void {
		$this->authorize();
		$result = $this->plugin->tasks->notify( $this->getSiteId() );
		if ( is_wp_error( $result ) ) {
			error_log( $result->get_error_message() );
			wp_send_json_error( [ "message" => $result->get_error_message() ] );
		}

		wp_send_json_success( [] );
	}

	private function getSiteId(): ?int {
		return isset( $_POST["site_id"] ) ? intval( $_POST["site_id"] ) : null;
	}

	public function apply(): void {
		$this->authorize();
		$since  = isset( $_POST["since"] ) ? intval( $_POST["since"] ) : 0;
		$result = $this->plugin->tasks->doModificationsHook( $since );
		wp_send_json_success( [
			"number_of_modifications" => $result,
		] );
	}
}
