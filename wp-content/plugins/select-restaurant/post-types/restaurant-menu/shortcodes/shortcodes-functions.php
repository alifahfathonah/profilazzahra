<?php

if ( ! function_exists( 'stockholm_qode_restaurant_include_shortcodes' ) ) {
	function stockholm_qode_restaurant_include_shortcodes() {
		include_once QODE_RESTAURANT_CPT_PATH . '/restaurant-menu/shortcodes/restaurant-menu-list.php';
	}
	
	add_action( 'stockholm_qode_action_restaurant_include_shortcode_files', 'stockholm_qode_restaurant_include_shortcodes' );
}

if ( ! function_exists( 'stockholm_qode_restaurant_add_shortcodes' ) ) {
	function stockholm_qode_restaurant_add_shortcodes( $shortcodes_class_name ) {
		$shortcodes = array(
			'StockholmQodeRestaurant\CPT\RestaurantMenu\Shortcodes\RestaurantMenuList\RestaurantMenuList'
		);
		
		$shortcodes_class_name = array_merge( $shortcodes_class_name, $shortcodes );
		
		return $shortcodes_class_name;
	}
	
	add_filter( 'stockholm_qode_filter_restaurant_add_vc_shortcode', 'stockholm_qode_restaurant_add_shortcodes' );
}