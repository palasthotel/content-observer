<?php


namespace Palasthotel\WordPress\ContentObserver;


use Palasthotel\WordPress\ContentObserver\Model\Modification;

class OnPostChange extends _Component {
	public function onCreate() {
		parent::onCreate();
		add_action( 'save_post', function ( $post_id, $post, $update ) {
			if(!is_post_type_viewable($post->post_type)) return;
			$mod = Modification::build( $post_id, get_post_type($post_id) )->create();
			if ( $update ) {
				$mod->update();
			}
			$this->plugin->repo->setModification( $mod );
		}, 10, 3 );

		add_action('delete_post', function($post_id){
			if(!is_post_type_viewable(get_post_type($post_id))) return;
			$this->plugin->repo->setModification(Modification::build($post_id, get_post_type($post_id))->delete());
		});
	}
}