<?php

include_once STOCKHOLM_CORE_MODULES_PATH . '/core-dashboard/core-dashboard.php';

if ( ! function_exists( 'stockholm_core_dashboard_load_files' ) ) {
	function stockholm_core_dashboard_load_files() {
		include_once STOCKHOLM_CORE_MODULES_PATH . '/core-dashboard/rest/include.php';
		include_once STOCKHOLM_CORE_MODULES_PATH . '/core-dashboard/registration-rest.php';
		include_once STOCKHOLM_CORE_MODULES_PATH . '/core-dashboard/sub-pages/sub-page.php';
		
		foreach ( glob( STOCKHOLM_CORE_MODULES_PATH . '/core-dashboard/sub-pages/*/load.php' ) as $subpages ) {
			include_once $subpages;
		}
	}
	
	add_action( 'after_setup_theme', 'stockholm_core_dashboard_load_files' );
}