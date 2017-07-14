<?php

require_once(dirname(__FILE__) . '/lib/functions.php');

// register default elgg events
elgg_register_event_handler('init', 'system', 'filter_search_init');

/**
 * Init function for this plugin
 *
 * @return void
 */
function filter_search_init() {
	elgg_extend_view('css/elgg', 'filter_search/filter_search.css');
	
	// special fix for various pages as it does not draw a filter menu
	elgg_extend_view('resources/blog/group', 'filter_search/no_filter_menu_fix', 400);
	elgg_extend_view('resources/discussion/group', 'filter_search/no_filter_menu_fix', 400);
	elgg_extend_view('resources/discussion/all', 'filter_search/no_filter_menu_fix', 400);
	
	// plugin hooks
	elgg_register_plugin_hook_handler('register', 'menu:filter', '\ColdTrick\FilterSearch\FilterMenu::registerFilterSearch');
	elgg_register_plugin_hook_handler('route', 'all', '\ColdTrick\FilterSearch\Router::all');
	elgg_register_plugin_hook_handler('view_vars', 'page/layouts/content', '\ColdTrick\FilterSearch\Views::pollContentLayoutViewVars');
}
