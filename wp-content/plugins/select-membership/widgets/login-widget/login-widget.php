<?php

class StockholmQodeMembershipLoginRegister extends WP_Widget {
	protected $params;
	
	public function __construct() {
		parent::__construct(
			'qode_login_register_widget', // Base ID
			esc_html__( 'Stockholm Login', 'select-membership' ),
			array( 'description' => esc_html__( 'Login and register wordpress widget', 'select-membership' ), )
		);
	}
	
	public function widget( $args, $instance ) {
		$additional_class = is_user_logged_in() ? 'qode-user-logged-in' : 'qode-user-not-logged-in';
		
		echo '<div class="widget qode-login-register-widget ' . esc_attr( $additional_class ) . '">';
		
		if ( ! is_user_logged_in() ) {
			echo '<a href="#" class="qode-login-opener">' . esc_html__( 'Login', 'select-membership' ) . '</a>';
			
			add_action( 'wp_footer', array( $this, 'render_login_form' ) );
		} else {
			echo stockholm_qode_membership_get_widget_template_part( 'login-widget', 'login-widget-template' );
		}
		
		echo '</div>';
	}
	
	public function render_login_form() {
		//Render modal with login and register forms
		echo stockholm_qode_membership_get_widget_template_part( 'login-widget', 'login-modal-template' );
	}
}

if ( ! function_exists( 'stockholm_qode_membership_widget_load' ) ) {
	function stockholm_qode_membership_widget_load( $widgets ) {
		$widgets[] = 'StockholmQodeMembershipLoginRegister';
		
		return $widgets;
	}
	
	add_filter( 'stockholm_qode_filter_register_widgets', 'stockholm_qode_membership_widget_load' );
}