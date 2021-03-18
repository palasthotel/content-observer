<?php


use Palasthotel\WordPress\ContentObserver\Model\Site;
use Palasthotel\WordPress\ContentObserver\Plugin;

function content_observer_plugin(){
	return Plugin::instance();
}

/**
 * @param $site_id
 *
 * @return Site|null
 */
function content_observer_get_site_by_id($site_id){
	return content_observer_plugin()->repo->getSite($site_id);
}

/**
 * @param $site_url
 *
 * @return Site|null
 */
function content_observer_find_site_by_url($site_url){
	return content_observer_plugin()->repo->findSiteByUrl($site_url);
}