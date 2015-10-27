<?php

// Get path to WP test directory via local.php config
require_once dirname( dirname( __FILE__ ) ) . '/local.php';

require_once WP_TESTS_DIR . '/includes/functions.php';

function _manually_load_plugin()
{
	require dirname( dirname( __FILE__ ) ) . '/moxie-movies.php';
}
tests_add_filter( 'muplugins_loaded', '_manually_load_plugin' );

require WP_TESTS_DIR . '/includes/bootstrap.php';