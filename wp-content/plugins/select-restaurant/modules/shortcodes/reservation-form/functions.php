<?php

if ( ! function_exists( 'stockholm_qode_restaurant_add_res_form_shortcodes' ) ) {
	function stockholm_qode_restaurant_add_res_form_shortcodes( $shortcodes_class_name ) {
		$shortcodes = array(
			'StockholmQodeRestaurant\Shortcodes\ReservationForm\ReservationForm'
		);
		
		$shortcodes_class_name = array_merge( $shortcodes_class_name, $shortcodes );
		
		return $shortcodes_class_name;
	}
	
	add_filter( 'stockholm_qode_filter_restaurant_add_vc_shortcode', 'stockholm_qode_restaurant_add_res_form_shortcodes' );
}
