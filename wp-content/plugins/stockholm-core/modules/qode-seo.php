<?php

if ( ! function_exists( 'stockholm_qode_seo_plugin_installed' ) ) {
	/**
	 * Function that checks if popular seo plugins are installed
	 * @return bool
	 */
	function stockholm_qode_seo_plugin_installed() {
		return defined( 'WPSEO_VERSION' );
	}
}

if ( ! function_exists( 'stockholm_qode_remove_yoast_json_on_ajax' ) ) {
	/**
	 * Function that removes yoast json ld script
	 * that stops page transition to work on home page
	 * Hooks to wpseo_json_ld_output in order to disable json ld script
	 * @return bool
	 *
	 * @param $data array json ld data that is being passed to filter
	 *
	 * @version 0.2
	 */
	function stockholm_qode_remove_yoast_json_on_ajax( $data ) {
		
		//is current request made through ajax?
		if ( stockholm_qode_is_page_transition_enabled() ) {
			//disable json ld script
			return array();
		}
	
		return $data;
	}
	
	//is yoast installed and it's version is greater or equal of 1.6?
	if ( stockholm_qode_seo_plugin_installed() && version_compare( WPSEO_VERSION, '1.6' ) >= 0 ) {
		add_filter( 'wpseo_json_ld_output', 'stockholm_qode_remove_yoast_json_on_ajax' );
		add_filter( 'disable_wpseo_json_ld_search', 'stockholm_qode_remove_yoast_json_on_ajax' );
	}
}

if ( ! function_exists( 'stockholm_qode_localize_no_ajax_pages' ) ) {
	/**
	 * Function that outputs no_ajax_obj javascript variable that is used default_dynamic.php.
	 * It is used for no ajax pages functionality
	 *
	 * Function hooks to wp_enqueue_scripts and uses wp_localize_script
	 *
	 * @see http://codex.wordpress.org/Function_Reference/wp_localize_script
	 *
	 * @uses stockholm_qode_get_objects_without_ajax()
	 * @uses stockholm_qode_get_wpml_pages_for_current_page()
	 * @uses stockholm_qode_get_woocommerce_pages()
	 *
	 * @version 0.1
	 */
	function stockholm_qode_localize_no_ajax_pages() {
		$global_options = stockholm_qode_return_global_options();
		
		//is ajax enabled?
		if ( stockholm_qode_is_page_transition_enabled() ) {
			$no_ajax_pages = array();
			
			//get posts that have ajax disabled and merge with main array
			$no_ajax_pages = array_merge( $no_ajax_pages, stockholm_qode_get_objects_without_ajax() );
			
			//is wpml installed?
			if ( stockholm_qode_is_wpml_installed() ) {
				//get translation pages for current page and merge with main array
				$no_ajax_pages = array_merge( $no_ajax_pages, stockholm_qode_get_wpml_pages_for_current_page() );
			}
			
			//is woocommerce installed?
			if ( stockholm_qode_is_woocommerce_installed() ) {
				//get all woocommerce pages and products and merge with main array
				$no_ajax_pages = array_merge( $no_ajax_pages, stockholm_qode_get_woocommerce_pages() );
			}
			
			//do we have some internal pages that won't to be without ajax?
			if ( isset( $global_options['internal_no_ajax_links'] ) && $global_options['internal_no_ajax_links'] !== '' ) {
				//get array of those pages
				$options_no_ajax_pages_array = explode( ',', $global_options['internal_no_ajax_links'] );
				
				if ( is_array( $options_no_ajax_pages_array ) && count( $options_no_ajax_pages_array ) ) {
					$no_ajax_pages = array_merge( $no_ajax_pages, $options_no_ajax_pages_array );
				}
			}
			
			//add logout url to main array
			$no_ajax_pages[] = htmlspecialchars_decode( wp_logout_url() );
			
			//finally localize script so we can use it in default_dynamic
			wp_localize_script( 'stockholm-default-dynamic', 'no_ajax_obj', array(
				'no_ajax_pages' => $no_ajax_pages
			) );
		}
	}
	
	add_action( 'wp_enqueue_scripts', 'stockholm_qode_localize_no_ajax_pages' );
}

if ( ! function_exists( 'stockholm_qode_get_woocommerce_pages' ) ) {
	/**
	 * Function that returns all url woocommerce pages
	 * @return array array of WooCommerce pages
	 *
	 * @version 0.1
	 */
	function stockholm_qode_get_woocommerce_pages() {
		$woo_pages_array = array();
		
		if ( stockholm_qode_is_woocommerce_installed() ) {
			if ( get_option( 'woocommerce_shop_page_id' ) != '' ) {
				$woo_pages_array[] = get_permalink( get_option( 'woocommerce_shop_page_id' ) );
			}
			if ( get_option( 'woocommerce_cart_page_id' ) != '' ) {
				$woo_pages_array[] = get_permalink( get_option( 'woocommerce_cart_page_id' ) );
			}
			if ( get_option( 'woocommerce_checkout_page_id' ) != '' ) {
				$woo_pages_array[] = get_permalink( get_option( 'woocommerce_checkout_page_id' ) );
			}
			if ( get_option( 'woocommerce_pay_page_id' ) != '' ) {
				$woo_pages_array[] = get_permalink( get_option( ' woocommerce_pay_page_id ' ) );
			}
			if ( get_option( 'woocommerce_thanks_page_id' ) != '' ) {
				$woo_pages_array[] = get_permalink( get_option( ' woocommerce_thanks_page_id ' ) );
			}
			if ( get_option( 'woocommerce_myaccount_page_id' ) != '' ) {
				$woo_pages_array[] = get_permalink( get_option( ' woocommerce_myaccount_page_id ' ) );
			}
			if ( get_option( 'woocommerce_edit_address_page_id' ) != '' ) {
				$woo_pages_array[] = get_permalink( get_option( ' woocommerce_edit_address_page_id ' ) );
			}
			if ( get_option( 'woocommerce_view_order_page_id' ) != '' ) {
				$woo_pages_array[] = get_permalink( get_option( ' woocommerce_view_order_page_id ' ) );
			}
			if ( get_option( 'woocommerce_terms_page_id' ) != '' ) {
				$woo_pages_array[] = get_permalink( get_option( ' woocommerce_terms_page_id ' ) );
			}
			
			$woo_products = wc_get_products( array(
				'post_status'    => 'publish',
				'posts_per_page' => '-1'
			) );
			
			foreach ( $woo_products as $product ) {
				$woo_pages_array[] = get_permalink( $product->get_id() );
			}
		}
		
		return $woo_pages_array;
	}
}

if ( ! function_exists( 'stockholm_qode_get_objects_without_ajax' ) ) {
	/**
	 * Function that returns urls of objects that have ajax disabled.
	 * Works for posts, pages and portfolio pages.
	 * @return array array of urls of posts that have ajax disabled
	 *
	 * @version 0.1
	 */
	function stockholm_qode_get_objects_without_ajax() {
		$posts_without_ajax = array();
		
		$posts_args = array(
			'post_type'   => array( 'post', 'portfolio_page', 'page' ),
			'post_status' => 'publish',
			'meta_key'    => 'qode_show-animation',
			'meta_value'  => 'no_animation'
		);
		
		$posts_query = new WP_Query( $posts_args );
		
		if ( $posts_query->have_posts() ) {
			while ( $posts_query->have_posts() ) {
				$posts_query->the_post();
				$posts_without_ajax[] = get_permalink( get_the_ID() );
			}
		}
		
		wp_reset_postdata();
		
		return $posts_without_ajax;
	}
}

if ( ! function_exists( 'stockholm_qode_get_wpml_pages_for_current_page' ) ) {
	/**
	 * Function that returns urls translated pages for current page.
	 * @return array array of url urls translated pages for current page.
	 *
	 * @version 0.1
	 */
	function stockholm_qode_get_wpml_pages_for_current_page() {
		$wpml_pages_for_current_page = array();
		
		if ( stockholm_qode_is_wpml_installed() ) {
			$language_pages = wpml_get_active_languages_filter( 'skip_missing=0' );
			
			foreach ( $language_pages as $key => $language_page ) {
				$wpml_pages_for_current_page[] = $language_page["url"];
			}
		}
		
		return $wpml_pages_for_current_page;
	}
}

if ( ! function_exists( 'stockholm_qode_add_ajax_meta' ) ) {
	/**
	 * Function that echoes meta data for ajax
	 */
	function stockholm_qode_add_ajax_meta() {
		$global_options  = stockholm_qode_return_global_options();
		
		if ( isset( $global_options['disable_qode_seo'] ) && $global_options['disable_qode_seo'] == 'no' && ! stockholm_qode_seo_plugin_installed() ) {
			$seo_description = get_post_meta( stockholm_qode_get_page_id(), "qode_seo_description", true );
			$seo_keywords    = get_post_meta( stockholm_qode_get_page_id(), "qode_seo_keywords", true );
			?>
			
			<div class="seo_title"><?php wp_title( '' ); ?></div>
			
			<?php if ( $seo_description !== '' ) { ?>
				<div class="seo_description"><?php echo esc_html( $seo_description ); ?></div>
			<?php } else if ( $global_options['meta_description'] ) { ?>
				<div class="seo_description"><?php echo esc_html( $global_options['meta_description'] ); ?></div>
			<?php } ?>
			<?php if ( $seo_keywords !== '' ) { ?>
				<div class="seo_keywords"><?php echo esc_html( $seo_keywords ); ?></div>
			<?php } else if ( $global_options['meta_keywords'] ) { ?>
				<div class="seo_keywords"><?php echo esc_html( $global_options['meta_keywords'] ); ?></div>
			<?php }
		}
	}
	
	add_action( 'stockholm_qode_action_ajax_meta', 'stockholm_qode_add_ajax_meta' );
}

if ( ! function_exists( 'stockholm_qode_add_header_meta' ) ) {
	/**
	 * Function that echoes meta data if our seo is enabled
	 */
	function stockholm_qode_add_header_meta() {
		global $is_IE;
		$global_options  = stockholm_qode_return_global_options();
		
		if ( isset( $global_options['disable_qode_seo'] ) && $global_options['disable_qode_seo'] == 'no' && ! stockholm_qode_seo_plugin_installed() ) {
			$seo_description = get_post_meta( stockholm_qode_get_page_id(), "qode_seo_description", true );
			$seo_keywords    = get_post_meta( stockholm_qode_get_page_id(), "qode_seo_keywords", true );
			?>
			
			<?php if ( $seo_description ) { ?>
				<meta name="description" content="<?php echo esc_html( $seo_description ); ?>">
			<?php } else if ( $global_options['meta_description'] ) { ?>
				<meta name="description" content="<?php echo esc_html( $global_options['meta_description'] ); ?>">
			<?php } ?>
			
			<?php if ( $seo_keywords ) { ?>
				<meta name="keywords" content="<?php echo esc_html( $seo_keywords ); ?>">
			<?php } else if ( $global_options['meta_keywords'] ) { ?>
				<meta name="keywords" content="<?php echo esc_html( $global_options['meta_keywords'] ); ?>">
			<?php }
		}
		
		if ( $is_IE ) { ?>
			<meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible">
		<?php }
		
		$favicon = get_option( 'site_icon' );
		if ( empty( $favicon ) && $global_options['favicon_image'] !== '' ) { ?>
			<link rel="shortcut icon" type="image/x-icon" href="<?php echo esc_url( $global_options['favicon_image'] ); ?>">
			<link rel="apple-touch-icon" href="<?php echo esc_url( $global_options['favicon_image'] ); ?>"/>
		<?php }
	}
	
	add_action( 'stockholm_qode_action_header_meta', 'stockholm_qode_add_header_meta' );
}