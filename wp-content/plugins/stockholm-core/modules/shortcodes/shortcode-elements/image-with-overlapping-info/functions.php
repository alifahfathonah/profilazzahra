<?php

if ( ! function_exists( 'stockholm_qode_add_image_with_overlapping_info_shortcodes' ) ) {
	function stockholm_qode_add_image_with_overlapping_info_shortcodes( $shortcodes_class_name ) {
		$shortcodes = array(
			'Stockholm\Shortcodes\ImageWithOverlappingInfo\ImageWithOverlappingInfo'
		);
		
		$shortcodes_class_name = array_merge( $shortcodes_class_name, $shortcodes );
		
		return $shortcodes_class_name;
	}
	
	add_filter( 'stockholm_qode_filter_add_vc_shortcode', 'stockholm_qode_add_image_with_overlapping_info_shortcodes' );
}