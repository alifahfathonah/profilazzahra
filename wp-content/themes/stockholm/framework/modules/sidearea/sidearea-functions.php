<?php

if ( ! function_exists( 'stockholm_qode_is_sidearea_enabled' ) ) {
	function stockholm_qode_is_sidearea_enabled() {
		$is_enabled = true;
		if ( stockholm_qode_options()->getOptionValue( 'enable_side_area' ) == "no" || ! is_active_sidebar( 'sidearea' ) ) {
			$is_enabled = false;
		}
		
		return $is_enabled;
	}
}

if ( ! function_exists( 'stockholm_qode_include_sidearea' ) ) {
	function stockholm_qode_include_sidearea() {
		
		if ( stockholm_qode_is_sidearea_enabled() && ! stockholm_qode_is_popup_enabled() && ! stockholm_qode_is_vertical_header_enabled() ) {
			get_template_part( 'framework/modules/sidearea/templates/sidearea' );
		}
	}
	
	add_action( 'stockholm_qode_action_before_page_wrapper', 'stockholm_qode_include_sidearea' );
}

if ( ! function_exists( 'stockholm_qode_sidearea_opener_icon' ) ) {
	function stockholm_qode_sidearea_opener_icon() {
		$icon = stockholm_qode_options()->getOptionValue( 'sidearea_open_icon_type' );
		$html = '';
		
		if ( $icon != '' ) {
			switch ( $icon ):
				case 'font-elegant':
					$html = '<span class="icon_menu"></span>';
					break;
				default:
					$html = '<i class="fa fa-bars"></i>';
					break;
			endswitch;
		}
		
		echo wp_kses_post( $html );
	}
}