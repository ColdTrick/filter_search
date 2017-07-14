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
			'selected' => (bool) stristr(current_page_url(), '/filter_search?'),
		]);
		
		return $return_value;
	}
}
