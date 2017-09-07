<?php

namespace ColdTrick\FilterSearch;

class FilterMenu {
	
	/**
	 * Add menu item to support filter search
	 *
	 * @param string          $hook         the name of the hook
	 * @param string          $type         the type of the hook
	 * @param \ElggMenuItem[] $return_value current return value
	 * @param array           $params       supplied params
	 *
	 * @return void|\ElggMenuItem[]
	 */
	public static function registerFilterSearch($hook, $type, $return_value, $params) {
		
		$context = elgg_get_context();
		$supported = filter_search_get_supported_contexts();
		
		if (!array_key_exists($context, $supported)) {
			return;
		}
		
		if ($context === 'discussion') {
			// no filter menu for discussion pages
			$return_value = [];
		}
		
		// add search button/form
		$form = elgg_view_form('filter_search', [
			'action' => $context . '/filter_search',
			'method' => 'GET',
			'disable_security' => true,
			'id' => 'filter_search_form',
			'class' => 'hidden',
			'data-toggle-slide' => '0',
		]);
		
		$return_value[] = \ElggMenuItem::factory([
			'name' => 'filter_search',
			'text' => elgg_view('output/url', [
				'text' => elgg_view_icon('search'),
				'href' => '#filter_search_form',
				'rel' => 'toggle',
			]) . $form,
			'href' => false,
			'title' => elgg_echo('search'),
			'priority' => 999,
		]);
		
		// add search query tab
		$search_params = elgg_extract($context, $supported);
		$search_params['page_owner'] = elgg_get_page_owner_guid();
		$search_params['context'] = $context;
		
		$hmac = elgg_build_hmac($search_params);
		$hash = $hmac->getToken();
		$session = elgg_get_session();
		
		$query = get_input('q');
		$selected = false;
		$url = elgg_http_add_url_query_elements(current_page_url(), [
			'limit' => null,
			'offset' => null,
		]);
		if (!empty($query)) {
			$selected = true;
			// store result
			$session->set("filter_search_{$hash}", $url);
		} else {
			// check if we store a previous query
			$url = $session->get("filter_search_{$hash}");
			if (!empty($url)) {
				$parts = explode('&', parse_url($url, PHP_URL_QUERY));
				foreach ($parts as $part) {
					list($key, $value) = explode('=', $part);
					if ($key !== 'q') {
						continue;
					}
					
					$query = $value;
					break;
				}
			}
		}
		
		if (!empty($query)) {
			$return_value[] = \ElggMenuItem::factory([
				'name' => 'filter_search_query',
				'text' => elgg_echo('filter_search:menu:filter:query', [$query]),
				'href' => $url,
				'priority' => 998,
				'selected' => $selected,
			]);
		}
		
		return $return_value;
	}
	
	/**
	 * Adds all/back menu item to support filter search
	 *
	 * @param string          $hook         the name of the hook
	 * @param string          $type         the type of the hook
	 * @param \ElggMenuItem[] $return_value current return value
	 * @param array           $params       supplied params
	 *
	 * @return void|\ElggMenuItem[]
	 */
	public static function registerFilterAll($hook, $type, $return_value, $params) {
		
		$context = elgg_get_context();
		$supported = filter_search_get_supported_contexts();
		
		if (!array_key_exists($context, $supported)) {
			return;
		}
		
		$all_found = false;
		foreach ($return_value as $menu_item) {
			if ($menu_item->getName() == 'all') {
				$all_found = true;
				break;
			}
		}
		
		if (!$all_found) {
			$url = "{$context}/all";
			$page_owner = elgg_get_page_owner_entity();
			if ($page_owner instanceof \ElggGroup) {
				$url = "{$context}/group/{$page_owner->guid}/all";
			}
			
			$return_value[] = \ElggMenuItem::factory([
				'name' => 'all',
				'text' => elgg_echo('all'),
				'href' => $url,
				'priority' => 1,
			]);
		}
		
		return $return_value;
	}
}
