<?php


namespace Palasthotel\WordPress\ContentObserver;


use Palasthotel\WordPress\ContentObserver\Components\Component;
use Palasthotel\WordPress\ContentObserver\Model\Modification;
use WP_Post;

class OnPostChange extends Component {

	public function onCreate() {
		parent::onCreate();
		add_action( 'save_post', function ( int $post_id, WP_Post $post, bool $update ) {
			if(!is_post_type_viewable($post->post_type)) return;
			$mod = Modification::build( $post_id, get_post_type($post_id) )->create();
			if ( $update ) {
				$mod->update();
			}
			$this->plugin->repo->setModification( $mod );

			$taxonomies = get_post_taxonomies($post_id);

			foreach ($taxonomies as $tax){
				$terms = get_the_terms($post, $tax);
				if(is_wp_error($terms)) continue;
				foreach ($terms as $term){
					$this->plugin->repo->setModification(
						Modification::build($term->term_id, "taxonomy:$tax")->update()
					);
				}
			}

		}, 10, 3 );

		add_action('delete_post', function($post_id){
			if(!is_post_type_viewable(get_post_type($post_id))) return;
			$this->plugin->repo->setModification(Modification::build($post_id, get_post_type($post_id))->delete());
		});
	}

}
