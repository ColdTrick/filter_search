<?php

namespace ColdTrick\FilterSearch;

class Views {
	
	/**
	 * Change some view vars
	 *
	 * @param string $hook         the name of the hook
	 * @param string $type         the type of the hook
	 * @param array  $return_value current return value
	 * @param array  $params       supplied params
	 *
	 * @return void|array
	 */
	public static function pollContentLayoutViewVars($hook, $type, $return_value, $params) {
		
		if (!elgg_in_context('poll')) {
			return;
		}
		
		if (!(elgg_get_page_owner_entity() instanceof \ElggGroup)) {
			return;
		}
		
		if (!stristr(current_page_url(), '/group/')) {
			return;
		}
		
		$return_value['filter'] = elgg_view_menu('filter', [
			'sort_by' => 'priority',
			'class' => 'elgg-menu-hz',
		]);
		
		return $return_value;
	}
}
