<?php
/*
Plugin Name: Select Restaurant
Description: Plugin that adds restaurant features to our theme
Author: Select Themes
Version: 1.1.1
*/

include_once 'load.php';

add_action( 'after_setup_theme', array( StockholmQodeRestaurant\CPT\PostTypesRegister::getInstance(), 'register' ) );

if ( ! function_exists( 'stockholm_qode_restaurant_load_assets' ) ) {
	function stockholm_qode_restaurant_load_assets() {
		wp_enqueue_style( 'stockholm-restaurant-style', plugins_url( '/assets/css/qode-restaurant.min.css', __FILE__ ), array(), '' );
		wp_enqueue_style( 'stockholm-restaurant-responsive-style', plugins_url( '/assets/css/qode-restaurant-responsive.min.css', __FILE__ ), array(), '' );
		wp_enqueue_script( 'jquery-ui-datepicker' );
		wp_enqueue_script( 'stockholm-restaurant-script', plugins_url( '/assets/js/qode-restaurant.min.js', __FILE__ ), array( 'jquery' ), '', true );
	}
	
	add_action( 'wp_enqueue_scripts', 'stockholm_qode_restaurant_load_assets' );
}