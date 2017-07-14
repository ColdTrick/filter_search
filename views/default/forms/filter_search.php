<?php

$page_owner_guid = elgg_get_page_owner_guid();
if (!empty($page_owner_guid)) {
	echo elgg_view_field([
		'#type' => 'hidden',
		'name' => '_po',
		'value' => $page_owner_guid,
	]);
}

if (elgg_get_page_owner_entity() instanceof ElggGroup) {
	echo elgg_view_field([
		'#type' => 'hidden',
		'name' => '_cg',
		'value' => $page_owner_guid,
	]);
}

echo elgg_view_field([
	'#type' => 'text',
	'name' => 'q',
	'placeholder' => elgg_echo('search'),
]);
