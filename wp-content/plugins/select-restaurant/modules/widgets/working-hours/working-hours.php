<?php

class StockholmQodeRestaurantWorkingHoursWidget extends StockholmQodeRestaurantWidget {
	public function __construct() {
		parent::__construct(
			'qode_working_hours_widget',
			esc_html__( 'Stockholm Restaurant Working Hours', 'select-restaurant' ),
			array( 'description' => esc_html__( 'Add a working hours element to your widget areas', 'select-restaurant' ) )
		);
		
		$this->setParams();
	}
	
	protected function setParams() {
		$this->params = array(
			array(
				'type'  => 'textfield',
				'name'  => 'widget_title',
				'title' => esc_html__( 'Widget Title', 'select-restaurant' )
			),
			array(
				'type'    => 'dropdown',
				'name'    => 'items_title_tag',
				'title'   => esc_html__( 'Items Title Tag', 'select-restaurant' ),
				'options' => stockholm_qode_get_title_tag( true ),
			),
			array(
				'type'  => 'textfield',
				'name'  => 'color',
				'title' => esc_html__( 'Color', 'select-restaurant' )
			)
		);
	}
	
	public function widget( $args, $instance ) {
		if ( ! is_array( $instance ) ) {
			$instance = array();
		}
		
		//prepare variables
		$params = '';
		
		//is instance empty?
		if ( is_array( $instance ) && count( $instance ) ) {
			//generate shortcode params
			foreach ( $instance as $key => $value ) {
				$params .= " $key='$value' ";
			}
		}
		
		echo wp_kses_post( $args['before_widget'] );
		
		if ( ! empty( $instance['widget_title'] ) ) {
			echo wp_kses_post( $args['before_title'] ) . esc_html( $instance['widget_title'] ) . wp_kses_post( $args['after_title'] );
		}
		echo '<div class="qode-working-hours-widget">';
		echo do_shortcode( "[qode_working_hours $params]" ); // XSS OK
		echo '</div>';
		
		echo wp_kses_post( $args['after_widget'] );
	}
}