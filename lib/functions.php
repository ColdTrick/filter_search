<?php
/**
 * All helper functions are bundled here
 */

/**
 * Get the supported contexts for filter_search
 *
 * @return array
 */
function filter_search_get_supported_contexts() {
	
	$result = [
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
	
	return elgg_trigger_plugin_hook('supported_context', 'filter_search', $result, $result);
}
