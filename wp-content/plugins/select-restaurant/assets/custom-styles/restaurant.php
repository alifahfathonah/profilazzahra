<?php

if ( ! function_exists( 'stockholm_qode_restaurant_style' ) && stockholm_qode_restaurant_theme_installed() ) {
	function stockholm_qode_restaurant_style() {
		
		if ( stockholm_qode_options()->getOptionValue( 'first_color' ) !== '' ) {
			echo stockholm_qode_dynamic_css( '.qode-working-hours-holder .qode-wh-title .qode-wh-title-accent-word, #ui-datepicker-div .ui-datepicker-current-day:not(.ui-datepicker-today) a', array(
				'color' => stockholm_qode_options()->getOptionValue( 'first_color' )
			) );
			echo stockholm_qode_dynamic_css( '#ui-datepicker-div .ui-datepicker-today', array(
				'background-color' => stockholm_qode_options()->getOptionValue( 'first_color' )
			) );
		}
	}
	
	add_action( 'stockholm_qode_action_style_dynamic', 'stockholm_qode_restaurant_style' );
}