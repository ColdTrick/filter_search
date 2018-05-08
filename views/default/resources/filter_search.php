<?php

$search_params = elgg_extract('search_params', $vars, []);
$search_params['search_type'] = 'entities';
$search_params['query'] = get_input('q');
$search_params['offset'] = max(0, (int) get_input('offset'));
$search_params['limit'] = max(0, (int) get_input('limit', (int) elgg_get_config('default_limit')));

$page_owner_guid = (int) get_input('_po');
if (!empty($page_owner_guid)) {
	elgg_set_page_owner_guid($page_owner_guid);
}

$container_guid = (int) get_input('_cg');
if (!empty($container_guid)) {
	$search_params['container_guid'] = $container_guid;
}

$type = elgg_extract('type', $search_params);
$subtype = elgg_extract('subtype', $search_params);

$search_results = elgg_trigger_plugin_hook('search', "$type:$subtype", $search_params, []);
if (empty($search_results)) {
	$search_results = elgg_trigger_plugin_hook('search', $type, $search_params, []);
}

$content = elgg_view_entity_list($search_results['entities'], [
	'full_view' => false,
	'no_results' => elgg_echo('notfound'),
	'count' => elgg_extract('count', $search_results, 0),
	'offset' => elgg_extract('offset', $search_params),
	'limit' => elgg_extract('limit', $search_params),
]);

$title = elgg_echo("item:$type:$subtype");

$filter = null;
if (elgg_get_page_owner_entity() instanceof \ElggGroup) {
	$filter = elgg_view_menu('filter', [
		'sort_by' => 'priority',
		'class' => 'elgg-menu-hz',
	]);
}

$body = elgg_view_layout('content', [
	'title' => $title,
	'content' => $content,
	'filter' => $filter,
	'filter_context' => 'filter_search',
]);

echo elgg_view_page($title, $body);
