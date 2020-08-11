<?php

if ( stockholm_qode_restaurant_theme_installed() ) {
	if ( ! function_exists( 'stockholm_qode_restaurant_map_map' ) ) {
		/**
		 * Adds admin page for OpenTable integration
		 */
		function stockholm_qode_restaurant_map_map() {
			stockholm_qode_add_admin_page( array(
				'title' => esc_html__( 'Restaurant', 'select-restaurant' ),
				'slug'  => '_restaurant',
				'icon'  => 'fa fa-cutlery'
			) );
			
			//#Working Hours panel
			$panel_working_hours = stockholm_qode_add_admin_panel( array(
				'page'  => '_restaurant',
				'name'  => 'panel_working_hours',
				'title' => 'Working Hours'
			) );
			
			$monday_group = stockholm_qode_add_admin_group( array(
				'name'        => 'monday_group',
				'title'       => esc_html__( 'Monday', 'select-restaurant' ),
				'parent'      => $panel_working_hours,
				'description' => esc_html__( 'Working hours for Monday', 'select-restaurant' ),
			) );
			
			$monday_row = stockholm_qode_add_admin_row( array(
				'name'   => 'monday_row',
				'parent' => $monday_group
			) );
			
			stockholm_qode_add_admin_field( array(
				'name'   => 'wh_monday_from',
				'type'   => 'textsimple',
				'label'  => esc_html__( 'From', 'select-restaurant' ),
				'parent' => $monday_row
			) );
			
			stockholm_qode_add_admin_field( array(
				'name'   => 'wh_monday_to',
				'type'   => 'textsimple',
				'label'  => esc_html__( 'To', 'select-restaurant' ),
				'parent' => $monday_row
			) );
			stockholm_qode_add_admin_field( array(
				'name'          => 'wh_monday_closed',
				'type'          => 'yesnosimple',
				'default_value' => 'no',
				'label'         => esc_html__( 'Closed', 'select-restaurant' ),
				'parent'        => $monday_row,
			) );
			$tuesday_group = stockholm_qode_add_admin_group( array(
				'name'        => 'tuesday_group',
				'title'       => esc_html__( 'Tuesday', 'select-restaurant' ),
				'parent'      => $panel_working_hours,
				'description' => esc_html__( 'Working hours for Tuesday', 'select-restaurant' ),
			) );
			
			$tuesday_row = stockholm_qode_add_admin_row( array(
				'name'   => 'tuesday_row',
				'parent' => $tuesday_group
			) );
			
			stockholm_qode_add_admin_field( array(
				'name'   => 'wh_tuesday_from',
				'type'   => 'textsimple',
				'label'  => esc_html__( 'From', 'select-restaurant' ),
				'parent' => $tuesday_row
			) );
			
			stockholm_qode_add_admin_field( array(
				'name'   => 'wh_tuesday_to',
				'type'   => 'textsimple',
				'label'  => esc_html__( 'To', 'select-restaurant' ),
				'parent' => $tuesday_row
			) );
			stockholm_qode_add_admin_field( array(
				'name'          => 'wh_tuesday_closed',
				'type'          => 'yesnosimple',
				'default_value' => 'no',
				'label'         => esc_html__( 'Closed', 'select-restaurant' ),
				'parent'        => $tuesday_row,
			) );
			$wednesday_group = stockholm_qode_add_admin_group( array(
				'name'        => 'wednesday_group',
				'title'       => esc_html__( 'Wednesday', 'select-restaurant' ),
				'parent'      => $panel_working_hours,
				'description' => esc_html__( 'Working hours for Wednesday', 'select-restaurant' ),
			) );
			
			$wednesday_row = stockholm_qode_add_admin_row( array(
				'name'   => 'wednesday_row',
				'parent' => $wednesday_group
			) );
			
			stockholm_qode_add_admin_field( array(
				'name'   => 'wh_wednesday_from',
				'type'   => 'textsimple',
				'label'  => esc_html__( 'From', 'select-restaurant' ),
				'parent' => $wednesday_row
			) );
			
			stockholm_qode_add_admin_field( array(
				'name'   => 'wh_wednesday_to',
				'type'   => 'textsimple',
				'label'  => esc_html__( 'To', 'select-restaurant' ),
				'parent' => $wednesday_row
			) );
			stockholm_qode_add_admin_field( array(
				'name'          => 'wh_wednesday_closed',
				'type'          => 'yesnosimple',
				'default_value' => 'no',
				'label'         => esc_html__( 'Closed', 'select-restaurant' ),
				'parent'        => $wednesday_row,
			) );
			$thursday_group = stockholm_qode_add_admin_group( array(
				'name'        => 'thursday_group',
				'title'       => esc_html__( 'Thursday', 'select-restaurant' ),
				'parent'      => $panel_working_hours,
				'description' => esc_html__( 'Working hours for Thursday', 'select-restaurant' ),
			) );
			
			$thursday_row = stockholm_qode_add_admin_row( array(
				'name'   => 'thursday_row',
				'parent' => $thursday_group
			) );
			
			stockholm_qode_add_admin_field( array(
				'name'   => 'wh_thursday_from',
				'type'   => 'textsimple',
				'label'  => esc_html__( 'From', 'select-restaurant' ),
				'parent' => $thursday_row
			) );
			
			stockholm_qode_add_admin_field( array(
				'name'   => 'wh_thursday_to',
				'type'   => 'textsimple',
				'label'  => esc_html__( 'To', 'select-restaurant' ),
				'parent' => $thursday_row
			) );
			stockholm_qode_add_admin_field( array(
				'name'          => 'wh_thursday_closed',
				'type'          => 'yesnosimple',
				'default_value' => 'no',
				'label'         => esc_html__( 'Closed', 'select-restaurant' ),
				'parent'        => $thursday_row,
			) );
			$friday_group = stockholm_qode_add_admin_group( array(
				'name'        => 'friday_group',
				'title'       => esc_html__( 'Friday', 'select-restaurant' ),
				'parent'      => $panel_working_hours,
				'description' => esc_html__( 'Working hours for Friday', 'select-restaurant' ),
			) );
			
			$friday_row = stockholm_qode_add_admin_row( array(
				'name'   => 'friday_row',
				'parent' => $friday_group
			) );
			
			stockholm_qode_add_admin_field( array(
				'name'   => 'wh_friday_from',
				'type'   => 'textsimple',
				'label'  => esc_html__( 'From', 'select-restaurant' ),
				'parent' => $friday_row
			) );
			
			stockholm_qode_add_admin_field( array(
				'name'   => 'wh_friday_to',
				'type'   => 'textsimple',
				'label'  => esc_html__( 'To', 'select-restaurant' ),
				'parent' => $friday_row
			) );
			stockholm_qode_add_admin_field( array(
				'name'          => 'wh_friday_closed',
				'type'          => 'yesnosimple',
				'default_value' => 'no',
				'label'         => esc_html__( 'Closed', 'select-restaurant' ),
				'parent'        => $friday_row,
			) );
			$saturday_group = stockholm_qode_add_admin_group( array(
				'name'        => 'saturday_group',
				'title'       => esc_html__( 'Saturday', 'select-restaurant' ),
				'parent'      => $panel_working_hours,
				'description' => esc_html__( 'Working hours for Saturday', 'select-restaurant' ),
			) );
			
			$saturday_row = stockholm_qode_add_admin_row( array(
				'name'   => 'saturday_row',
				'parent' => $saturday_group
			) );
			
			stockholm_qode_add_admin_field( array(
				'name'   => 'wh_saturday_from',
				'type'   => 'textsimple',
				'label'  => esc_html__( 'From', 'select-restaurant' ),
				'parent' => $saturday_row
			) );
			
			stockholm_qode_add_admin_field( array(
				'name'   => 'wh_saturday_to',
				'type'   => 'textsimple',
				'label'  => esc_html__( 'To', 'select-restaurant' ),
				'parent' => $saturday_row
			) );
			stockholm_qode_add_admin_field( array(
				'name'          => 'wh_saturday_closed',
				'type'          => 'yesnosimple',
				'default_value' => 'no',
				'label'         => esc_html__( 'Closed', 'select-restaurant' ),
				'parent'        => $saturday_row,
			) );
			$sunday_group = stockholm_qode_add_admin_group( array(
				'name'        => 'sunday_group',
				'title'       => esc_html__( 'Sunday', 'select-restaurant' ),
				'parent'      => $panel_working_hours,
				'description' => esc_html__( 'Working hours for Sunday', 'select-restaurant' ),
			) );
			
			$sunday_row = stockholm_qode_add_admin_row( array(
				'name'   => 'sunday_row',
				'parent' => $sunday_group
			) );
			
			stockholm_qode_add_admin_field( array(
				'name'   => 'wh_sunday_from',
				'type'   => 'textsimple',
				'label'  => esc_html__( 'From', 'select-restaurant' ),
				'parent' => $sunday_row
			) );
			
			stockholm_qode_add_admin_field( array(
				'name'   => 'wh_sunday_to',
				'type'   => 'textsimple',
				'label'  => esc_html__( 'To', 'select-restaurant' ),
				'parent' => $sunday_row
			) );
			stockholm_qode_add_admin_field( array(
				'name'          => 'wh_sunday_closed',
				'type'          => 'yesnosimple',
				'default_value' => 'no',
				'label'         => esc_html__( 'Closed', 'select-restaurant' ),
				'parent'        => $sunday_row,
			) );
		}
		
		add_action( 'stockholm_qode_action_options_map', 'stockholm_qode_restaurant_map_map', 71 ); //one after elements
	}
}