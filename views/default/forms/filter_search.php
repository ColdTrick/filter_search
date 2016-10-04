<?php

$page_owner_guid = elgg_get_page_owner_guid();
if (!empty($page_owner_guid)) {
	echo elgg_view_input('hidden', [
		'name' => '_po',
		'value' => $page_owner_guid,
	]);
}

echo elgg_view_input('text', [
	'name' => 'q',
	'placeholder' => elgg_echo('search'),
]);
