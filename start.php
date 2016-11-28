<?php

/**
 * Init function for this plugin
 *
 * @return void
 */

// register default elgg events
elgg_register_event_handler('init', 'system', 'filter_search_init');

function filter_search_init() {
	elgg_extend_view('css/elgg', 'filter_search/filter_search.css');
	elgg_register_plugin_hook_handler('register', 'menu:filter', 'filter_search_register_filter_menu');
	elgg_register_plugin_hook_handler('route', 'all', 'filter_search_route_all');
	
	// special fix for various pages as it does not draw a filter menu
	elgg_extend_view('resources/blog/group', 'filter_search/no_filter_menu_fix', 400);
	elgg_extend_view('resources/discussion/group', 'filter_search/no_filter_menu_fix', 400);
	elgg_extend_view('resources/discussion/all', 'filter_search/no_filter_menu_fix', 400);
	
	
	elgg_register_plugin_hook_handler('view_vars', 'page/layouts/content', function($type, $hook, $return, $params) {
		if (!elgg_in_context('poll')) {
			return;
		}
		
		if (!(elgg_get_page_owner_entity() instanceof \ElggGroup)) {
			return;
		}
		
		if (!stristr(current_page_url(), '/group/')) {
			return;
		}
		
		$return['filter'] = elgg_view_menu('filter', array('sort_by' => 'priority', 'class' => 'elgg-menu-hz'));;
		return $return;
	});
}

function filter_search_get_supported_contexts() {
	return [
// 		'context' => 'handler',
		'questions' => [
			'handler' => 'questions',
			'search_params' => [
				'type' => 'object',
				'subtype' => 'question',
			],
		],
		'blog' => [
			'handler' => 'blog',
			'search_params' => [
				'type' => 'object',
				'subtype' => 'blog',
			],
		],
		'poll' => [
			'handler' => 'poll',
			'search_params' => [
				'type' => 'object',
				'subtype' => 'poll',
			],
		],
		'discussion' => [
			'handler' => 'discussion',
			'search_params' => [
				'type' => 'object',
				'subtype' => 'discussion',
			],
		],
	];
}

function filter_search_route_all($hook, $type, $return_value, $params) {
	$supported = filter_search_get_supported_contexts();
	
	$handler = elgg_extract('handler', $return_value, '');
	$segments = elgg_extract('segments', $return_value, []);
	
	if (!array_key_exists($handler, $supported)) {
		return;
	}
	
	if (elgg_extract(0, $segments) !== 'filter_search') {
		return;
	}
	
	echo elgg_view_resource('filter_search', $supported[$handler]);
	return false;
}

function filter_search_register_filter_menu($hook, $type, $return_value, $params) {
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
