<?php

if ( stockholm_qode_restaurant_theme_installed() ) {
	if ( ! function_exists( 'stockholm_qode_restaurant_meta_box_map' ) ) {
		function stockholm_qode_restaurant_meta_box_map() {
			
			$restaurant_menu_meta_box = stockholm_qode_create_meta_box(
				array(
					'scope' => array( 'qode-restaurant-menu' ),
					'title' => esc_html__( 'Restaurant Menu Item Settings', 'select-restaurant' ),
					'name'  => 'cafe_menu_item_meta'
				)
			);
			
			stockholm_qode_create_meta_box_field(
				array(
					'name'          => 'qode_restaurant_menu_item_price',
					'type'          => 'text',
					'default_value' => '',
					'label'         => esc_html__( 'Restaurant Menu Item Price', 'select-restaurant' ),
					'description'   => esc_html__( 'Enter price for this restaurant menu item', 'select-restaurant' ),
					'parent'        => $restaurant_menu_meta_box,
					'args'          => array(
						'col_width' => '3'
					)
				)
			);
			
			stockholm_qode_create_meta_box_field(
				array(
					'name'          => 'qode_restaurant_menu_item_description',
					'type'          => 'text',
					'default_value' => '',
					'label'         => esc_html__( 'Restaurant Menu Item Description', 'select-restaurant' ),
					'description'   => esc_html__( 'Enter description for this restaurant menu item', 'select-restaurant' ),
					'parent'        => $restaurant_menu_meta_box,
				)
			);
			
			stockholm_qode_create_meta_box_field(
				array(
					'name'          => 'qode_restaurant_menu_item_label',
					'type'          => 'text',
					'default_value' => '',
					'label'         => esc_html__( 'Restaurant Menu Item Label', 'select-restaurant' ),
					'description'   => esc_html__( 'Enter label for this restaurant menu item', 'select-restaurant' ),
					'parent'        => $restaurant_menu_meta_box,
					'args'          => array(
						'col_width' => '3'
					)
				)
			);
		}
		
		add_action( 'stockholm_qode_action_meta_boxes_map', 'stockholm_qode_restaurant_meta_box_map' );
	}
}