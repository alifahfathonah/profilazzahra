<?php

if ( ! function_exists( 'stockholm_qode_restaurant_register_working_hours_widget' ) ) {
	/**
	 * Function that register working hours widget
	 */
	function stockholm_qode_restaurant_register_working_hours_widget( $widgets ) {
		$widgets[] = 'StockholmQodeRestaurantWorkingHoursWidget';
		
		return $widgets;
	}
	
	add_filter( 'stockholm_qode_filter_register_widgets', 'stockholm_qode_restaurant_register_working_hours_widget' );
}