<?php

namespace ColdTrick\FilterSearch;

class Router {
	
	/**
	 * Check is we're in a supported filter_Search context
	 *
	 * @param string $hook         the name of the hook
	 * @param string $type         the type of the hook
	 * @param mixed  $return_value current return value
	 * @param array  $params       supplied params
	 *
	 * @return void|false
	 */
	public static function all($hook, $type, $return_value, $params) {
		
		$segments = elgg_extract('segments', $return_value, []);
		
		if (elgg_extract(0, $segments) !== 'filter_search') {
			return;
		}
		
		$supported = filter_search_get_supported_contexts();
		
		$handler = elgg_extract('handler', $return_value, '');
		
		if (!array_key_exists($handler, $supported)) {
			return;
		}
		
		echo elgg_view_resource('filter_search', $supported[$handler]);
		return false;
	}
}
