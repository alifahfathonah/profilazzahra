<?php

if ( ! function_exists( 'stockholm_qode_restaurant_album_meta_box_functions' ) ) {
	function stockholm_qode_restaurant_album_meta_box_functions( $post_types ) {
		$post_types[] = 'qode-restaurant-menu';
		
		return $post_types;
	}
	
	add_filter( 'stockholm_qode_filter_meta_box_post_types_save', 'stockholm_qode_restaurant_album_meta_box_functions' );
	add_filter( 'stockholm_qode_filter_meta_box_post_types_remove', 'stockholm_qode_restaurant_album_meta_box_functions' );
}

if ( ! function_exists( 'stockholm_qode_restaurant_register_restaurant_menu_cpt' ) ) {
	function stockholm_qode_restaurant_register_restaurant_menu_cpt( $cpt_class_name ) {
		$cpt_class = array(
			'StockholmQodeRestaurant\CPT\RestaurantMenu\RestaurantMenuRegister'
		);
		
		$cpt_class_name = array_merge( $cpt_class_name, $cpt_class );
		
		return $cpt_class_name;
	}
	
	add_filter( 'stockholm_qode_filter_restaurant_register_custom_post_types', 'stockholm_qode_restaurant_register_restaurant_menu_cpt' );
}


