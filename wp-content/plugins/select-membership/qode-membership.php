<?php
/**
 * Plugin Name: Select Membership
 * Description: Plugin that adds social login and user dashboard page
 * Author: Select Themes
 * Version: 1.1.3
 */

require_once 'load.php';

if ( ! function_exists( 'stockholm_qode_membership_text_domain' ) ) {
	/**
	 * Loads plugin text domain so it can be used in translation
	 */
	function stockholm_qode_membership_text_domain() {
		load_plugin_textdomain( 'select-membership', false, QODE_MEMBERSHIP_REL_PATH . '/languages' );
	}

	add_action( 'plugins_loaded', 'stockholm_qode_membership_text_domain' );
}

if ( ! function_exists( 'stockholm_qode_membership_scripts' ) ) {
	/**
	 * Loads plugin scripts
	 */
	function stockholm_qode_membership_scripts() {

		wp_enqueue_style( 'stockholm-membership-style', plugins_url( QODE_MEMBERSHIP_REL_PATH . '/assets/css/qode-membership.min.css' ) );
		wp_enqueue_style( 'stockholm-membership-responsive-style', plugins_url( QODE_MEMBERSHIP_REL_PATH . '/assets/css/qode-membership-responsive.min.css' ) );

        //include google+ api
        wp_enqueue_script('stockholm-membership-google-plus-api', 'https://apis.google.com/js/platform.js', array(), null, false);

		$array_deps = array(
			'underscore',
			'jquery-ui-tabs'
		);
		
		wp_enqueue_script( 'stockholm-membership-script', plugins_url( QODE_MEMBERSHIP_REL_PATH . '/assets/js/qode-membership.min.js' ), $array_deps, false, true );
	}

	add_action( 'wp_enqueue_scripts', 'stockholm_qode_membership_scripts' );
}