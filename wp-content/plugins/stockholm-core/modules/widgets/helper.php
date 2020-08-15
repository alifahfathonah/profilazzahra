<?php

if ( ! function_exists( 'stockholm_qode_load_widget_class' ) ) {
	/**
	 * Loades widget class file.
	 */
	function stockholm_qode_load_widget_class() {
		include_once STOCKHOLM_CORE_MODULES_PATH . '/widgets/widget-class.php';
	}
	
	add_action( 'stockholm_qode_action_before_options_map', 'stockholm_qode_load_widget_class' );
}

if ( ! function_exists( 'stockholm_qode_load_widgets' ) ) {
	/**
	 * Loades all widgets by going through all folders that are placed directly in widgets folder
	 * and loads load.php file in each. Hooks to stockholm_qode_action_before_options_map action
	 */
	function stockholm_qode_load_widgets() {
		
		foreach ( glob( QODE_FRAMEWORK_ROOT_DIR . '/modules/widgets/*/load.php' ) as $widget_load ) {
			include_once $widget_load;
		}
	}
	
	add_action( 'stockholm_qode_action_before_options_map', 'stockholm_qode_load_widgets' );
}

if ( ! function_exists( 'stockholm_qode_register_widgets' ) ) {
	function stockholm_qode_register_widgets() {
		$widgets = apply_filters( 'stockholm_qode_filter_register_widgets', $widgets = array() );
		
		if ( ! empty( $widgets ) ) {
			foreach ( $widgets as $widget ) {
				register_widget( $widget );
			}
		}
	}
	
	add_action( 'widgets_init', 'stockholm_qode_register_widgets' );
}

if ( ! function_exists( 'stockholm_qode_add_widget_menu_closed_level_option' ) ) {
	function stockholm_qode_add_widget_menu_closed_level_option( $widget, $return, $instance ) {
		
		if ( 'nav_menu' == $widget->id_base ) {
			$closed_sub_levels = isset( $instance['closed_sub_levels'] ) ? $instance['closed_sub_levels'] : '';
			?>
			<p>
				<input class="checkbox" type="checkbox" id="<?php echo esc_attr( $widget->get_field_id( 'closed_sub_levels' ) ); ?>" name="<?php echo esc_attr( $widget->get_field_name( 'closed_sub_levels' ) ); ?>" <?php checked( true, $closed_sub_levels ); ?> />
				<label for="<?php echo esc_attr( $widget->get_field_id( 'closed_sub_levels' ) ); ?>">
					<?php esc_html_e( 'Initially closed sub-levels', 'stockholm-core' ); ?>
				</label>
			</p>
			<?php
		}
	}
	
	add_filter( 'in_widget_form', 'stockholm_qode_add_widget_menu_closed_level_option', 10, 3 );
}

if ( ! function_exists( 'stockholm_qode_save_menu_closed_level_option' ) ) {
	function stockholm_qode_save_menu_closed_level_option( $instance, $new_instance ) {
		
		if ( isset( $new_instance['nav_menu'] ) && ! empty( $new_instance['closed_sub_levels'] ) ) {
			$instance['closed_sub_levels'] = 1;
		}
		
		return $instance;
	}
	
	add_filter( 'widget_update_callback', 'stockholm_qode_save_menu_closed_level_option', 10, 2 );
}

if ( ! function_exists( 'stockholm_qode_add_menu_closed_level_class' ) ) {
	function stockholm_qode_add_menu_closed_level_class( $params ) {
		
		$widget_settings = get_option( 'widget_nav_menu' );
		
		if ( ! empty( $widget_settings[ $params[1]['number'] ]['closed_sub_levels'] ) ) {
			add_filter( 'nav_menu_submenu_css_class', 'stockholm_qode_menu_closed_level_class', 10, 3 );
		} else {
			remove_filter( 'nav_menu_submenu_css_class', 'stockholm_qode_menu_closed_level_class', 10, 3 );
		}
		
		return $params;
	}
	
	add_filter( 'dynamic_sidebar_params', 'stockholm_qode_add_menu_closed_level_class' );
}

if ( ! function_exists( 'stockholm_qode_menu_closed_level_class' ) ) {
	function stockholm_qode_menu_closed_level_class( $classes, $args, $depth ) {
		$classes[] = 'qode-sub-menu-closed';
		
		return $classes;
	}
}