<?php

include_once 'const.php';
include_once 'helpers.php';

//load lib
require_once 'lib/shortcode-interface.php';
require_once 'lib/shortcode-loader.php';
require_once 'lib/shortcode-functions.php';

//load post-post-types
require_once 'lib/post-type-interface.php';
require_once 'post-types/post-types-functions.php';
require_once 'post-types/post-types-register.php'; //this has to be loaded last

require_once 'modules/widgets/widgets-functions.php';

//load admin
if ( ! function_exists( 'stockholm_qode_restaurant_load_admin' ) ) {
	function stockholm_qode_restaurant_load_admin() {
		require_once 'admin/options/restaurant/map.php';
	}
	
	add_action( 'stockholm_qode_action_before_options_map', 'stockholm_qode_restaurant_load_admin' );
}

//load custom styles
if ( ! function_exists( 'stockholm_qode_restaurant_load_custom_styles' ) ) {
	function stockholm_qode_restaurant_load_custom_styles() {
		require_once 'assets/custom-styles/restaurant.php';
	}
	
	add_action( 'after_setup_theme', 'stockholm_qode_restaurant_load_custom_styles' );
}