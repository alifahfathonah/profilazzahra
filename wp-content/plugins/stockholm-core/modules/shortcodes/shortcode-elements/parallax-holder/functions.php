<?php

if ( ! function_exists( 'stockholm_qode_add_parallax_holder_shortcode' ) ) {
	function stockholm_qode_add_parallax_holder_shortcode( $shortcodes_class_name ) {
		$shortcodes = array(
			'Stockholm\Shortcodes\ParallaxHolder\ParallaxHolder'
		);
		
		$shortcodes_class_name = array_merge( $shortcodes_class_name, $shortcodes );
		
		return $shortcodes_class_name;
	}
	
	add_filter( 'stockholm_qode_filter_add_vc_shortcode', 'stockholm_qode_add_parallax_holder_shortcode' );
}

if ( class_exists( 'WPBakeryShortCodesContainer' ) ) {
	class WPBakeryShortCode_Qode_Parallax_Holder extends WPBakeryShortCodesContainer {
	}
}