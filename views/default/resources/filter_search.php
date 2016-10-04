<?php

$search_params = elgg_extract('search_params', $vars, []);
$search_params['search_type'] = 'entities';
$search_params['query'] = get_input('q');

$page_owner_guid = (int) get_input('_po');
if (!empty($page_owner_guid)) {
	elgg_set_page_owner_guid($page_owner_guid);
}

$type = elgg_extract('type', $search_params);
$subtype = elgg_extract('subtype', $search_params);

$search_results = elgg_trigger_plugin_hook('search', "$type:$subtype", $search_params, []);
if (empty($search_results)) {
	$search_results = elgg_trigger_plugin_hook('search', $type, $search_params, []);
}

if (empty($search_results)) {
	$content = elgg_echo('notfound');
} else {
	$content = elgg_view_entity_list($search_results['entities'], ['full_view' => false]);
}

$body = elgg_view_layout('content', ['content' => $content, 'filter_context' => 'filter_search']);

echo elgg_view_page('', $body);