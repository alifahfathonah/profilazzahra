<?php

if ( ! function_exists( 'stockholm_qode_register_button' ) ) {
	function stockholm_qode_register_button( $buttons ) {
		array_push( $buttons, "|", "qode_shortcodes" );
		
		return $buttons;
	}
}

if ( ! function_exists( 'stockholm_qode_add_shortcode_file' ) ) {
	function stockholm_qode_add_shortcode_file( $plugin_array ) {
		$plugin_array['qode_shortcodes'] = STOCKHOLM_CORE_MODULES_URL_PATH . '/shortcodes/qode_shortcodes.js';
		
		return $plugin_array;
	}
}

if ( ! function_exists( 'stockholm_qode_shortcodes_button' ) ) {
	function stockholm_qode_shortcodes_button() {
		if ( ! current_user_can( 'edit_posts' ) && ! current_user_can( 'edit_pages' ) ) {
			return;
		}
		
		if ( get_user_option( 'rich_editing' ) == 'true' ) {
			add_filter( 'mce_external_plugins', 'stockholm_qode_add_shortcode_file' );
			add_filter( 'mce_buttons', 'stockholm_qode_register_button' );
		}
	}
	
	add_action( 'after_setup_theme', 'stockholm_qode_shortcodes_button' );
}

if ( ! function_exists( 'stockholm_qode_get_shortcode_template_part' ) ) {
	/**
	 * Loads module template part.
	 *
	 * @param string $template name of the template to load
	 * @param string $shortcode name of the shortcode folder
	 * @param string $slug
	 * @param array  $params array of parameters to pass to template
	 *
	 * @return html
	 * @see stockholm_qode_get_template_part()
	 */
	function stockholm_qode_get_shortcode_template_part( $template, $shortcode, $slug = '', $params = array() ) {
		
		//HTML Content from template
		$html          = '';
		$template_path = STOCKHOLM_CORE_SHORTCODES_ROOT_DIR . '/' . $shortcode;
		
		$temp = $template_path . '/' . $template;
		if ( is_array( $params ) && count( $params ) ) {
			extract( $params );
		}
		
		$template = '';
		
		if ( ! empty( $temp ) ) {
			if ( ! empty( $slug ) ) {
				$template = "{$temp}-{$slug}.php";
				
				if ( ! file_exists( $template ) ) {
					$template = $temp . '.php';
				}
			} else {
				$template = $temp . '.php';
			}
		}
		
		$template = apply_filters( 'stockholm_qode_filter_shortcode_template_part', $template );
		
		if ( $template ) {
			ob_start();
			include( $template );
			$html = ob_get_clean();
		}
		
		return $html;
	}
}

include_once STOCKHOLM_CORE_SHORTCODES_ROOT_DIR . '/accordion.php';
include_once STOCKHOLM_CORE_SHORTCODES_ROOT_DIR . '/blockquote.php';
include_once STOCKHOLM_CORE_SHORTCODES_ROOT_DIR . '/button.php';
include_once STOCKHOLM_CORE_SHORTCODES_ROOT_DIR . '/call-to-action.php';
include_once STOCKHOLM_CORE_SHORTCODES_ROOT_DIR . '/countdown.php';
include_once STOCKHOLM_CORE_SHORTCODES_ROOT_DIR . '/counter.php';
include_once STOCKHOLM_CORE_SHORTCODES_ROOT_DIR . '/cover-boxes.php';
include_once STOCKHOLM_CORE_SHORTCODES_ROOT_DIR . '/custom-font.php';
include_once STOCKHOLM_CORE_SHORTCODES_ROOT_DIR . '/dropcaps.php';
include_once STOCKHOLM_CORE_SHORTCODES_ROOT_DIR . '/highlights.php';
include_once STOCKHOLM_CORE_SHORTCODES_ROOT_DIR . '/icon.php';
include_once STOCKHOLM_CORE_SHORTCODES_ROOT_DIR . '/icon-list-item.php';
include_once STOCKHOLM_CORE_SHORTCODES_ROOT_DIR . '/icon-with-text.php';
include_once STOCKHOLM_CORE_SHORTCODES_ROOT_DIR . '/image-hover.php';
include_once STOCKHOLM_CORE_SHORTCODES_ROOT_DIR . '/image-slider.php';
include_once STOCKHOLM_CORE_SHORTCODES_ROOT_DIR . '/image-with-text.php';
include_once STOCKHOLM_CORE_SHORTCODES_ROOT_DIR . '/interactive-banners.php';
include_once STOCKHOLM_CORE_SHORTCODES_ROOT_DIR . '/latest-posts.php';
include_once STOCKHOLM_CORE_SHORTCODES_ROOT_DIR . '/line-graph.php';
include_once STOCKHOLM_CORE_SHORTCODES_ROOT_DIR . '/message.php';
include_once STOCKHOLM_CORE_SHORTCODES_ROOT_DIR . '/ordered-list.php';
include_once STOCKHOLM_CORE_SHORTCODES_ROOT_DIR . '/pie-chart.php';
include_once STOCKHOLM_CORE_SHORTCODES_ROOT_DIR . '/pie-chart-doughnut.php';
include_once STOCKHOLM_CORE_SHORTCODES_ROOT_DIR . '/pie-chart-full.php';
include_once STOCKHOLM_CORE_SHORTCODES_ROOT_DIR . '/pie-chart-with-icon.php';
include_once STOCKHOLM_CORE_SHORTCODES_ROOT_DIR . '/portfolio-slider.php';
include_once STOCKHOLM_CORE_SHORTCODES_ROOT_DIR . '/product-categories-showcase.php';
include_once STOCKHOLM_CORE_SHORTCODES_ROOT_DIR . '/product-list.php';
include_once STOCKHOLM_CORE_SHORTCODES_ROOT_DIR . '/progress-bar.php';
include_once STOCKHOLM_CORE_SHORTCODES_ROOT_DIR . '/progress-bar-icon.php';
include_once STOCKHOLM_CORE_SHORTCODES_ROOT_DIR . '/progress-bar-vertical.php';
include_once STOCKHOLM_CORE_SHORTCODES_ROOT_DIR . '/qode-carousel.php';
include_once STOCKHOLM_CORE_SHORTCODES_ROOT_DIR . '/qode-slider.php';
include_once STOCKHOLM_CORE_SHORTCODES_ROOT_DIR . '/service-table.php';
include_once STOCKHOLM_CORE_SHORTCODES_ROOT_DIR . '/social-icon.php';
include_once STOCKHOLM_CORE_SHORTCODES_ROOT_DIR . '/social-share.php';
include_once STOCKHOLM_CORE_SHORTCODES_ROOT_DIR . '/social-share-list.php';
include_once STOCKHOLM_CORE_SHORTCODES_ROOT_DIR . '/team.php';
include_once STOCKHOLM_CORE_SHORTCODES_ROOT_DIR . '/testimonials.php';
include_once STOCKHOLM_CORE_SHORTCODES_ROOT_DIR . '/unordered-list.php';

//shortcodes v2
if ( ! function_exists( 'stockholm_qode_load_shortcode_interface' ) ) {
	function stockholm_qode_load_shortcode_interface() {
		include_once STOCKHOLM_CORE_MODULES_PATH . '/shortcodes/lib/shortcode-interface.php';
	}
	
	add_action( 'stockholm_qode_action_before_options_map', 'stockholm_qode_load_shortcode_interface' );
}

if ( ! function_exists( 'stockholm_qode_load_shortcodes' ) ) {
	/**
	 * Loades all shortcodes by going through all folders that are placed directly in shortcodes/shortcode-elements folder
	 * and loads load.php file in each. Hooks to stockholm_qode_action_before_options_map action
	 *
	 * @see http://php.net/manual/en/function.glob.php
	 */
	function stockholm_qode_load_shortcodes() {
		foreach ( glob( STOCKHOLM_CORE_SHORTCODES_ROOT_DIR . '/*/load.php' ) as $shortcode_load ) {
			include_once $shortcode_load;
		}
		
		do_action( 'stockholm_qode_action_include_shortcodes_file' );
		
		include_once STOCKHOLM_CORE_MODULES_PATH . '/shortcodes/lib/shortcode-loader.php';
	}
	
	add_action( 'stockholm_qode_action_before_options_map', 'stockholm_qode_load_shortcodes' );
}