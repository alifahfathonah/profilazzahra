<?php
/*
Plugin Name: Stockholm Core
Description: Plugin that adds additional features needed by our theme
Author: Select Themes
Version: 1.2.1
*/
if ( ! class_exists( 'StockholmCore' ) ) {
	class StockholmCore {
		private static $instance;
		
		public function __construct() {
			require_once 'constants.php';
			require_once 'helpers/helper.php';
			
			// Make plugin available for translation
			add_action( 'plugins_loaded', array( $this, 'load_plugin_textdomain' ) );
			
			// Add plugin's body classes
			add_filter( 'body_class', array( $this, 'add_body_classes' ) );
			
			add_action( 'after_setup_theme', array( $this, 'init' ), 5 );
		}
		
		public static function get_instance() {
			if ( self::$instance == null ) {
				self::$instance = new self();
			}
			
			return self::$instance;
		}
		
		function load_plugin_textdomain() {
			load_plugin_textdomain( 'stockholm-core', false, STOCKHOLM_CORE_REL_PATH . '/languages' );
		}
		
		function add_body_classes( $classes ) {
			$classes[] = 'stockholm-core-' . STOCKHOLM_CORE_VERSION;
			
			return $classes;
		}
		
		function init() {
			
			if ( stockholm_core_is_installed( 'theme' ) ) {
				include_once STOCKHOLM_CORE_MODULES_PATH . '/helper.php';
				include_once STOCKHOLM_CORE_MODULES_PATH . '/import/qode-import.php';
			}
		}
	}
	
	StockholmCore::get_instance();
}