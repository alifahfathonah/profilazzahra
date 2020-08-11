<?php

include_once STOCKHOLM_CORE_MODULES_PATH . '/shortcodes/shortcodes.php';
include_once STOCKHOLM_CORE_MODULES_PATH . '/custom-fields-post-formats.php';
include_once STOCKHOLM_CORE_MODULES_PATH . '/qode-custom-taxonomy-field.php';
include_once STOCKHOLM_CORE_MODULES_PATH . '/qode-custom-post-types.php';
include_once STOCKHOLM_CORE_MODULES_PATH . '/qode-like.php';
include_once STOCKHOLM_CORE_MODULES_PATH . '/qode-seo.php';
include_once STOCKHOLM_CORE_MODULES_PATH . '/widgets/helper.php';
include_once STOCKHOLM_CORE_MODULES_PATH . '/contact/contact-form.php';
include_once STOCKHOLM_CORE_MODULES_PATH . '/core-dashboard/load.php';

add_filter( 'widget_text', 'do_shortcode' );
add_filter( 'call_to_action_widget', 'do_shortcode' );

/* Function for adding custom meta boxes hooked to default action */
if ( ! function_exists( 'stockholm_qode_include_meta_boxes' ) ) {
	function stockholm_qode_include_meta_boxes() {
		if ( class_exists( 'WP_Block_Type' ) ) {
			add_action( 'admin_head', 'stockholm_qode_meta_box_add' );
		} else {
			add_action( 'add_meta_boxes', 'stockholm_qode_meta_box_add' );
		}
	}
	
	add_action( 'after_setup_theme', 'stockholm_qode_include_meta_boxes' );
}

if ( ! function_exists( 'stockholm_qode_create_meta_box_handler' ) ) {
	function stockholm_qode_create_meta_box_handler( $box, $key ) {
		add_meta_box(
			'qodef-meta-box-' . $key,
			$box->title,
			'stockholm_qodef_render_meta_box',
			$box->scope,
			'advanced',
			'high',
			array( 'box' => $box )
		);
	}
}

if ( ! function_exists( 'stockholm_qode_theme_admin_bar_menu_options' ) ) {
	/**
	 * Add a link to the WP Toolbar
	 */
	function stockholm_qode_theme_admin_bar_menu_options( $wp_admin_bar ) {
		if ( current_user_can( 'edit_theme_options' ) && ! is_admin() ) {
			$args = array(
				'id'    => 'stockholm-admin-bar-options',
				'title' => sprintf( '<span class="ab-icon dashicons-before dashicons-admin-generic"></span> %s', esc_html__( 'Stockholm Options', 'stockholm-core' ) ),
				'href'  => esc_url( admin_url( 'admin.php?page=qode_theme_menu' ) )
			);
			
			$wp_admin_bar->add_node( $args );
			
			foreach ( stockholm_qode_framework()->qodeOptions->adminPages as $key => $value ) {
				$suffix = ! empty( $value->slug ) ? '_tab' . $value->slug : '';
				
				$args = array(
					'id'     => 'stockholm-admin-bar-options-' . $suffix,
					'title'  => $value->title,
					'parent' => 'stockholm-admin-bar-options',
					'href'   => esc_url( admin_url( 'admin.php?page=qode_theme_menu' . $suffix ) )
				);
				
				$wp_admin_bar->add_node( $args );
			};
		}
	}
	
	add_action( 'admin_bar_menu', 'stockholm_qode_theme_admin_bar_menu_options', 999 );
}

if ( ! function_exists( 'stockholm_qode_export_theme_options' ) ) {
	/**
	 * Function that export options from db.
	 */
	function stockholm_qode_export_theme_options() {
		$options = get_option( "qode_options_stockholm" );
		$output  = base64_encode( serialize( $options ) );
		
		return $output;
	}
	
}
if ( ! function_exists( 'stockholm_qode_export_custom_sidebars' ) ) {
	function stockholm_qode_export_custom_sidebars() {
		$custom_sidebars = get_option( "qode_sidebars" );
		$output          = base64_encode( serialize( $custom_sidebars ) );
		
		return $output;
	}
}

if ( ! function_exists( 'stockholm_qode_export_widgets_sidebars' ) ) {
	function stockholm_qode_export_widgets_sidebars() {
		$data             = array();
		$data['sidebars'] = stockholm_qode_export_sidebars();
		$data['widgets']  = stockholm_qode_export_widgets();
		
		$output = base64_encode( serialize( $data ) );
		
		return $output;
	}
}

if ( ! function_exists( 'stockholm_qode_export_widgets' ) ) {
	function stockholm_qode_export_widgets() {
		global $wp_registered_widgets;
		
		$all_widgets = array();
		
		foreach ( $wp_registered_widgets as $widget_id => $widget_params ) {
			$all_widgets[] = $widget_params['callback'][0]->id_base;
		}
		
		foreach ( $all_widgets as $widget_id ) {
			$masterds_widget_data = get_option( 'widget_' . $widget_id );
			
			if ( ! empty( $masterds_widget_data ) ) {
				$widget_datas[ $widget_id ] = $masterds_widget_data;
			}
		}
		
		unset( $all_widgets );
		
		return $widget_datas;
	}
}

if ( ! function_exists( 'stockholm_qode_export_sidebars' ) ) {
	function stockholm_qode_export_sidebars() {
		$sidebars = get_option( "sidebars_widgets" );
		$sidebars = stockholm_qode_exclude_sidebar_keys( $sidebars );
		
		return $sidebars;
	}
}

if ( ! function_exists( 'stockholm_qode_exclude_sidebar_keys' ) ) {
	function stockholm_qode_exclude_sidebar_keys( $keys = array() ) {
		if ( ! is_array( $keys ) ) {
			return $keys;
		}
		
		unset( $keys['wp_inactive_widgets'] );
		unset( $keys['array_version'] );
		
		return $keys;
	}
}

if ( ! function_exists( 'stockholm_qode_import_theme_options' ) ) {
	/**
	 * Function that import theme options to db.
	 * It hooks to ajax wp_ajax_qode_import_theme_options action.
	 */
	function stockholm_qode_import_theme_options() {
		
		if ( current_user_can( 'administrator' ) ) {
			if ( empty( $_POST ) || ! isset( $_POST ) ) {
				stockholm_qode_ajax_status( 'error', esc_html__( 'Import field is empty', 'stockholm-core' ) );
			} else {
				$data = $_POST;
				if ( wp_verify_nonce( $data['nonce'], 'qodef_import_theme_options_secret_value' ) ) {
					$content              = $data['content'];
					$unserialized_content = unserialize( base64_decode( $content ) );
					update_option( 'qode_options_stockholm', $unserialized_content );
					stockholm_qode_ajax_status( 'success', esc_html__( 'Options are imported successfully', 'stockholm-core' ) );
				} else {
					stockholm_qode_ajax_status( 'error', esc_html__( 'Non valid authorization', 'stockholm-core' ) );
				}
				
			}
		} else {
			stockholm_qode_ajax_status( 'error', esc_html__( 'You don\'t have privileges for this operation', 'stockholm-core' ) );
		}
	}
	
	add_action( 'wp_ajax_stockholm_qode_action_import_theme_options', 'stockholm_qode_import_theme_options' );
}

if ( ! function_exists( 'stockholm_qode_import_custom_sidebars' ) ) {
	/**
	 * Function that import custom sidebars to db.
	 * It hooks to ajax wp_ajax_qode_import_sidebar_and_widgets action.
	 */
	function stockholm_qode_import_custom_sidebars() {
		if ( current_user_can( 'administrator' ) ) {
			
			if ( empty( $_POST ) || ! isset( $_POST ) ) {
				stockholm_qode_ajax_status( 'error', esc_html__( 'Import field is empty', 'stockholm-core' ) );
			} else {
				$data = $_POST;
				
				if ( wp_verify_nonce( $data['nonce'], 'qodef_import_custom_sidebars_secret_value' ) ) {
					$content              = $data['content'];
					$unserialized_content = unserialize( base64_decode( $content ) );
					
					update_option( 'qode_sidebars', $unserialized_content );
					stockholm_qode_ajax_status( 'success', esc_html__( 'Custom sidebars imported successfully', 'stockholm-core' ) );
					
				} else {
					stockholm_qode_ajax_status( 'error', esc_html__( 'Non valid authorization', 'stockholm-core' ) );
				}
			}
		} else {
			stockholm_qode_ajax_status( 'error', esc_html__( 'You don\'t have privileges for this operation', 'stockholm-core' ) );
		}
	}
	
	add_action( 'wp_ajax_stockholm_qode_action_import_custom_sidebars', 'stockholm_qode_import_custom_sidebars' );
}

if ( ! function_exists( 'stockholm_qode_import_widgets' ) ) {
	/**
	 * Function that import sidebars and widgets to db.
	 * It hooks to ajax wp_ajax_qode_import_sidebar_and_widgets action.
	 */
	function stockholm_qode_import_widgets() {
		if ( current_user_can( 'administrator' ) ) {
			
			if ( empty( $_POST ) || ! isset( $_POST ) ) {
				stockholm_qode_ajax_status( 'error', esc_html__( 'Import field is empty', 'stockholm-core' ) );
			} else {
				$data = $_POST;
				if ( wp_verify_nonce( $data['nonce'], 'qodef_import_widgets_secret_value' ) ) {
					$content              = $data['content'];
					$unserialized_content = unserialize( base64_decode( $content ) );
					
					foreach ( (array) $unserialized_content['widgets'] as $widget_id => $widget_data ) {
						update_option( 'widget_' . $widget_id, $widget_data );
					}
					
					$sidebars = get_option( "sidebars_widgets" );
					unset( $sidebars['array_version'] );
					$data = $unserialized_content;
					
					if ( is_array( $data['sidebars'] ) ) {
						$sidebars = array_merge( (array) $sidebars, (array) $data['sidebars'] );
						unset( $sidebars['wp_inactive_widgets'] );
						$sidebars                  = array_merge( array( 'wp_inactive_widgets' => array() ), $sidebars );
						$sidebars['array_version'] = 2;
						wp_set_sidebars_widgets( $sidebars );
					}
					
					stockholm_qode_ajax_status( 'success', esc_html__( 'Widgets imported successfully', 'stockholm-core' ) );
				} else {
					stockholm_qode_ajax_status( 'error', esc_html__( 'Non valid authorization', 'stockholm-core' ) );
				}
			}
		} else {
			stockholm_qode_ajax_status( 'error', esc_html__( 'You don\'t have privileges for this operation', 'stockholm-core' ) );
		}
	}
	
	add_action( 'wp_ajax_stockholm_qode_action_import_widgets', 'stockholm_qode_import_widgets' );
}

if ( ! function_exists( 'stockholm_qode_ajax_status' ) ) {
	/**
	 * Function that return status from ajax functions
	 */
	function stockholm_qode_ajax_status( $status, $message, $data = null ) {
		$response = array(
			'status'  => $status,
			'message' => $message,
			'data'    => $data
		);
		
		$output   = json_encode( $response );
		
		exit( $output );
	}
}

if ( ! function_exists( 'stockholm_qode_add_google_analytic' ) ) {
	function stockholm_qode_add_google_analytic() {
		$global_options = stockholm_qode_return_global_options();
		
		if ( isset( $global_options['google_analytics_code'] ) && $global_options['google_analytics_code'] != "" ) { ?>
			<script>
				var _gaq = _gaq || [];
				_gaq.push(['_setAccount', '<?php echo esc_attr( $global_options['google_analytics_code'] ); ?>']);
				_gaq.push(['_trackPageview']);
				
				(function () {
					var ga = document.createElement('script');
					ga.type = 'text/javascript';
					ga.async = true;
					ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
					var s = document.getElementsByTagName('script')[0];
					s.parentNode.insertBefore(ga, s);
				})();
			</script>
		<?php }
	}
	
	add_action( 'stockholm_qode_action_before_page_header', 'stockholm_qode_add_google_analytic' );
}

if ( ! function_exists( 'stockholm_qode_enqueue_our_prettyphoto_script' ) ) {
	/**
	 * Function that includes our prettyphoto script
	 */
	function stockholm_qode_enqueue_our_prettyphoto_script() {
		
		if ( stockholm_core_is_installed( 'visual-composer' ) ) {
			wp_deregister_script( 'prettyphoto' );
			wp_enqueue_script( 'prettyphoto', QODE_JS_ROOT . '/plugins/jquery.prettyPhoto.js', array( 'jquery' ), false, true );
		}
	}
	
	add_action( 'stockholm_qode_action_enqueue_additional_scripts', 'stockholm_qode_enqueue_our_prettyphoto_script' );
}

if ( ! function_exists( 'stockholm_core_send_contact_page_form' ) ) {
	function stockholm_core_send_contact_page_form() {
		global $wp_filesystem;
		WP_Filesystem();
		
		check_ajax_referer( 'stockholm_core_contact_page_nonce', 'contact_page_nonce' );
		
		if ( isset( $_POST['form_data'] ) ) {
			$data_string = $_POST['form_data'];
			parse_str( $data_string, $data_array );
			
			$send_mail     = true;
			$use_recaptcha = stockholm_qode_options()->getOptionValue( 'use_recaptcha' );
			$secret_key    = stockholm_qode_options()->getOptionValue( 'recaptcha_private_key' );
			
			$receive_mail_option  = stockholm_qode_options()->getOptionValue( 'receive_mail' );
			$email_from_option    = stockholm_qode_options()->getOptionValue( 'email_from' );
			$email_subject_option = stockholm_qode_options()->getOptionValue( 'email_subject' );
			
			$email_to   = ! empty( $receive_mail_option ) ? sanitize_email( $receive_mail_option ) : sanitize_email( get_option( 'admin_email' ) );
			$email_from = ! empty( $email_from_option ) ? sanitize_email( $email_from_option ) : $email_to;
			$subject    = ! empty( $email_subject_option ) ? esc_attr( $email_subject_option ) : esc_attr__( 'Contact Us', 'stockholm-core' );
			
			$first_name = esc_attr( stockholm_qode_replace_newline( $data_array['fname'] ) );
			$last_name  = esc_attr( stockholm_qode_replace_newline( $data_array['lname'] ) );
			$website    = esc_url( $data_array['website'] );
			$message    = $data_array['message'];
			
			$text = esc_html__( 'Name: ', 'stockholm-core' ) . $first_name . " " . $last_name . "\n";
			$text .= esc_html__( 'Email: ', 'stockholm-core' ) . sanitize_email( $data_array['email'] ) . "\n";
			if ( ! empty( $website ) ) {
				$text .= esc_html__( 'WebSite: ', 'stockholm-core' ) . $website . "\n";
			}
			$text .= esc_html__( 'Message: ', 'stockholm-core' ) . sanitize_textarea_field( $message );
			
			$headers = "MIME-Version: 1.0" . "\r\n";
			$headers .= "Content-type:text/plain; charset=utf-8" . "\r\n";
			$headers .= "From: '" . $first_name . " " . $last_name . "' <" . $email_from . "> " . "\r\n";
			$headers .= "Reply-To: '" . sanitize_email( $data_array['email'] ) . "'" . "\r\n";
			if ( $use_recaptcha == 'yes' && $secret_key != '' ) {
				$captcha       = $data_array['g-recaptcha-response'];
				$response      = $wp_filesystem->get_contents( "https://www.google.com/recaptcha/api/siteverify?secret=" . $secret_key . "&response=" . $captcha );
				$response_keys = json_decode( $response, true );
				
				if ( ! $response_keys['success'] ) {
					$send_mail = false;
					stockholm_qode_ajax_status( 'error', esc_html__( 'Invalid Captcha', 'stockholm-core' ) );
				}
			}
			
			if ( $send_mail ) {
				$result = wp_mail( $email_to, $subject, $text, $headers );
				if ( $result ) {
					stockholm_qode_ajax_status( 'success', '<strong>' . esc_html__( 'THANK YOU!', 'stockholm-core' ) . ' </strong>' . esc_html__( 'Your email was successfully sent. We will contact you as soon as possible.', 'stockholm-core' ) );
				} else {
					stockholm_qode_ajax_status( 'error', esc_html__( 'Email server problem', 'stockholm-core' ) );
				}
			}
		}
		
		wp_die();
	}
	
	add_action( 'wp_ajax_nopriv_stockholm_core_send_contact_page_form', 'stockholm_core_send_contact_page_form' );
	add_action( 'wp_ajax_stockholm_core_send_contact_page_form', 'stockholm_core_send_contact_page_form' );
}

if ( ! function_exists( 'stockholm_qode_replace_newline' ) ) {
	function stockholm_qode_replace_newline( $text ) {
		$text = (string) $text;
		$text = str_replace( array( "\r", "\n" ), '', $text );
		
		return trim( $text );
	}
}