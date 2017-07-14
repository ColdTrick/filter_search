<?php

elgg_register_plugin_hook_handler('view_vars', 'page/layouts/content', '\ColdTrick\FilterSearch\Views::fixFilterMenu');
