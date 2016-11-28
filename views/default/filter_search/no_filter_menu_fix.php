<?php

elgg_register_plugin_hook_handler('view_vars', 'page/layouts/content', function($type, $hook, $return, $params) {
	$return['filter'] = elgg_view_menu('filter', array('sort_by' => 'priority', 'class' => 'elgg-menu-hz'));;
	return $return;
});
