<?php


namespace Palasthotel\WordPress\ContentObserver;


use Palasthotel\WordPress\ContentObserver\Model\Modification;

class OnPostChange extends _Component {
	public function onCreate() {
		parent::onCreate();
		add_action( 'save_post', function ( $post_id, $post, $update ) {
			$mod = Modification::build( $post_id )->create();
			if ( $update ) {
				$mod->update();
			}
			$this->plugin->repo->setModification( $mod );
		}, 10, 3 );

		add_action('delete_post', function($post_id){
			$this->plugin->repo->setModification(Modification::build($post_id)->delete());
		});
	}
}