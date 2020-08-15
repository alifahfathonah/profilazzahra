<?php
if ( ! function_exists( 'add_action' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit();
}

class StockholmQodeImport {
	public $message = "";
	public $attachments = false;
	
	function __construct() {
		add_action( 'admin_menu', array( &$this, 'qode_admin_import' ) );
		add_action( 'admin_menu', array( &$this, 'register_qode_theme_settings' ), 20 ); // permission 20 is set in order to register_qode_theme_settings be after the qode_admin_import
	}
	
	function register_qode_theme_settings() {
		register_setting( 'qode_options_import_page', 'qode_options_import' );
	}
	
	public function import_content( $file ) {
		ob_start();
		$class_wp_importer = ABSPATH . 'wp-admin/includes/class-wp-importer.php';
		require_once( $class_wp_importer );
		require_once STOCKHOLM_CORE_MODULES_PATH . '/import/class.wordpress-importer.php';
		$qode_import = new WP_Import();
		set_time_limit( 0 );
		
		$qode_import->fetch_attachments = $this->attachments;
		$returned_value                 = $qode_import->import( $file );
		
		if ( is_wp_error( $returned_value ) ) {
			$this->message = esc_html__( "An Error Occurred During Import", "stockholm-core" );
		} else {
			$this->message = esc_html__( "Content imported successfully", "stockholm-core" );
		}
		
		ob_get_clean();
	}
	
	public function import_widgets( $file ) {
		$this->import_custom_sidebars( 'custom_sidebars.txt' );
		$options = $this->file_options( $file );
		
		foreach ( (array) $options['widgets'] as $qode_widget_id => $qode_widget_data ) {
			update_option( 'widget_' . $qode_widget_id, $qode_widget_data );
		}
		
		$this->import_sidebars_widgets( $file );
		$this->message = esc_html__( "Widgets imported successfully", "stockholm-core" );
	}
	
	public function import_sidebars_widgets( $file ) {
		$qode_sidebars = get_option( "sidebars_widgets" );
		unset( $qode_sidebars['array_version'] );
		$data = $this->file_options( $file );
		
		if ( is_array( $data['sidebars'] ) ) {
			$qode_sidebars = array_merge( (array) $qode_sidebars, (array) $data['sidebars'] );
			unset( $qode_sidebars['wp_inactive_widgets'] );
			$qode_sidebars                  = array_merge( array( 'wp_inactive_widgets' => array() ), $qode_sidebars );
			$qode_sidebars['array_version'] = 2;
			wp_set_sidebars_widgets( $qode_sidebars );
		}
	}
	
	public function import_custom_sidebars( $file ) {
		$options = $this->file_options( $file );
		update_option( 'qode_sidebars', $options );
		$this->message = esc_html__( "Custom sidebars imported successfully", "stockholm-core" );
	}
	
	public function import_options( $file ) {
		$options = $this->file_options( $file );
		update_option( 'qode_options_stockholm', $options );
		$this->message = esc_html__( "Options imported successfully", "stockholm-core" );
	}
	
	public function import_menus( $file ) {
		global $wpdb;
		
		$this->menus_data = $this->file_options( $file );
		$menu_array       = array();
		$terms_table      = $wpdb->prefix . 'terms';
		
		foreach ( $this->menus_data as $registered_menu => $menu_slug ) {
			$term_rows = $wpdb->get_results( "SELECT * FROM {$terms_table} where slug='{$menu_slug}'", ARRAY_A );
		
			if ( isset( $term_rows[0]['term_id'] ) ) {
				$term_id_by_slug = $term_rows[0]['term_id'];
			} else {
				$term_id_by_slug = null;
			}
			
			$menu_array[ $registered_menu ] = $term_id_by_slug;
		}
		
		set_theme_mod( 'nav_menu_locations', array_map( 'absint', $menu_array ) );
	}
	
	public function import_settings_pages( $file ) {
		$pages = $this->file_options( $file );
		
		foreach ( $pages as $qode_page_option => $qode_page_id ) {
			update_option( $qode_page_option, $qode_page_id );
		}
	}
	
	function update_meta_fields_after_import( $folder ) {
		global $wpdb;
		
		$url            = esc_url( home_url( '/' ) );
		$demo_urls      = $this->import_get_demo_urls( $folder );
		$postmeta_table = $wpdb->prefix . 'postmeta';
		
		foreach ( $demo_urls as $demo_url ) {
			$meta_values = $wpdb->get_results( "SELECT meta_id, meta_value FROM {$postmeta_table} WHERE meta_key LIKE 'qode%' AND meta_value LIKE '" . esc_url( $demo_url ) . "%';" );
			
			if ( ! empty( $meta_values ) ) {
				foreach ( $meta_values as $meta_value ) {
					$new_value = $this->recalc_serialized_lengths( str_replace( $demo_url, $url, $meta_value->meta_value ) );
					
					$wpdb->update( $wpdb->postmeta,	array( 'meta_value' => $new_value ), array( 'meta_id' => $meta_value->meta_id )	);
				}
			}
		}
	}
	
	function update_options_after_import( $folder ) {
		$url       = home_url( '/' );
		$demo_urls = $this->import_get_demo_urls( $folder );
		
		foreach ( $demo_urls as $demo_url ) {
			$global_options    = get_option( 'qode_options_stockholm' );
			$new_global_values = str_replace( $demo_url, $url, $global_options );
			
			update_option( 'qode_options_stockholm', $new_global_values );
		}
	}
	
	function import_get_demo_urls( $folder ) {
		$demo_urls = array();
		$domain_url = strpos( $folder, 'new', false ) !== false ? str_replace( array( '/', 'new' ), array( '', '' ), $folder ) . '.select-themes.com/' : 'demo.select-themes.com/' . $folder;
		
		$demo_urls[] = ! empty( $domain_url ) ? 'http://' . $domain_url : '';
		$demo_urls[] = ! empty( $domain_url ) ? 'https://' . $domain_url : '';
		
		return $demo_urls;
	}
	
	function recalc_serialized_lengths( $sObject ) {
		$ret = preg_replace_callback( '!s:(\d+):"(.*?)";!', 'recalc_serialized_lengths_callback', $sObject );
		
		return $ret;
	}
	
	function recalc_serialized_lengths_callback( $matches ) {
		return "s:" . strlen( $matches[2] ) . ":\"$matches[2]\";";
	}
	
	public function file_options( $file ) {
		$file_content = $this->qode_file_contents( $file );
		
		if ( $file_content ) {
			$unserialized_content = unserialize( base64_decode( $file_content ) );
			
			if ( $unserialized_content ) {
				return $unserialized_content;
			}
		}
		
		return false;
	}
	
	function qode_file_contents( $path ) {
		$url      = "http://export.select-themes.com/" . $path;
		$response = wp_remote_get( $url );
		$body     = wp_remote_retrieve_body( $response );
		
		return $body;
	}
	
	function qode_admin_import() {
		$this->pagehook = add_menu_page(
			esc_html__( 'Select Import', 'stockholm-core' ),
			esc_html__( 'Select Import', 'stockholm-core' ),
			'manage_options', 'qode_options_import_page',
			array(
				&$this,
				'stockholm_qode_generate_import_page'
			),
			'dashicons-download'
		);
		
		add_action( 'admin_print_styles-' . $this->pagehook, 'stockholm_qode_enqueue_admin_styles' );
	}
	
	function stockholm_qode_generate_import_page() { ?>
		<div id="qode-metaboxes-general" class="wrap qodef-page qodef-page-info">
			<h2 class="qodef-page-title"><?php esc_html_e( 'Stockholm — One-Click Import', 'stockholm-core' ) ?></h2>
			<form method="post" action="" id="importContentForm">
				<div class="qodef-page-form">
					<div class="qodef-page-form-section-holder clearfix">
						<h3 class="qodef-page-section-title"><?php esc_html_e( 'Import Demo Content', 'stockholm-core' ); ?></h3>
						<div class="qodef-page-form-section">
							<div class="qodef-field-desc">
								<h4><?php esc_html_e( 'Import', 'stockholm-core' ); ?></h4>
								<p><?php esc_html_e( 'Choose demo content you want to import', 'stockholm-core' ); ?></p>
							</div>
							<div class="qodef-section-content">
								<div class="container-fluid">
									<div class="row">
										<div class="col-lg-3">
											<em class="qodef-field-description"><?php esc_html_e( 'Demo Site', 'stockholm-core' ); ?></em>
											<select name="import_example" id="import_example" class="form-control qodef-form-element">
												<option value="stockholm1"><?php esc_html_e( 'Stockholm 1 - Anders', 'stockholm-core' ); ?></option>
												<option value="stockholm2"><?php esc_html_e( 'Stockholm 2 - Bjorn', 'stockholm-core' ); ?></option>
												<option value="stockholm3"><?php esc_html_e( 'Stockholm 3 - Claes', 'stockholm-core' ); ?></option>
												<option value="stockholm4"><?php esc_html_e( 'Stockholm 4 - Daniel', 'stockholm-core' ); ?></option>
												<option value="stockholm5"><?php esc_html_e( 'Stockholm 5 - Erland', 'stockholm-core' ); ?></option>
												<option value="stockholm6"><?php esc_html_e( 'Stockholm 6 - Fredrik', 'stockholm-core' ); ?></option>
												<option value="stockholm7"><?php esc_html_e( 'Stockholm 7 - Gustav', 'stockholm-core' ); ?></option>
												<option value="stockholm8"><?php esc_html_e( 'Stockholm 8 - Hugo', 'stockholm-core' ); ?></option>
												<option value="stockholm9"><?php esc_html_e( 'Stockholm 9 - Ingmar', 'stockholm-core' ); ?></option>
												<option value="stockholm10"><?php esc_html_e( 'Stockholm 10 - Jonas', 'stockholm-core' ); ?></option>
												<option value="stockholm11"><?php esc_html_e( 'Stockholm 11 - Kaleb', 'stockholm-core' ); ?></option>
												<option value="stockholm12"><?php esc_html_e( 'Stockholm 12 - Lars', 'stockholm-core' ); ?></option>
												<option value="stockholm13"><?php esc_html_e( 'Stockholm 13 - Magnus', 'stockholm-core' ); ?></option>
												<option value="stockholm14"><?php esc_html_e( 'Stockholm 14 - Noel', 'stockholm-core' ); ?></option>
												<option value="stockholm15"><?php esc_html_e( 'Stockholm 15 - Oskar', 'stockholm-core' ); ?></option>
												<option value="stockholm16"><?php esc_html_e( 'Stockholm 16 - Petter', 'stockholm-core' ); ?></option>
												<option value="stockholm17"><?php esc_html_e( 'Stockholm 17 - Roland', 'stockholm-core' ); ?></option>
												<option value="stockholm18"><?php esc_html_e( 'Stockholm 18 - Sigfrid', 'stockholm-core' ); ?></option>
												<option value="stockholm19"><?php esc_html_e( 'Stockholm 19 - Tomas', 'stockholm-core' ); ?></option>
												<option value="stockholm20"><?php esc_html_e( 'Stockholm 20 - Viggo', 'stockholm-core' ); ?></option>
												<option value="stockholmnew1"><?php esc_html_e( 'New Demo 1 - Kelda', 'stockholm-core' ); ?></option>
												<option value="stockholmnew2"><?php esc_html_e( 'New Demo 2 - Frida', 'stockholm-core' ); ?></option>
												<option value="stockholmnew3"><?php esc_html_e( 'New Demo 3 - Jette', 'stockholm-core' ); ?></option>
												<option value="stockholmnew4"><?php esc_html_e( 'New Demo 4 - Metta', 'stockholm-core' ); ?></option>
												<option value="stockholmnew5"><?php esc_html_e( 'New Demo 5 - Göta', 'stockholm-core' ); ?></option>
												<option value="stockholmnew6"><?php esc_html_e( 'New Demo 6 - Eva', 'stockholm-core' ); ?></option>
												<option value="stockholmnew7"><?php esc_html_e( 'New Demo 7 - Tilde', 'stockholm-core' ); ?></option>
												<option value="stockholmnew8"><?php esc_html_e( 'New Demo 8 - Ebba', 'stockholm-core' ); ?></option>
												<option value="stockholmnew9"><?php esc_html_e( 'New Demo 9 - Rona', 'stockholm-core' ); ?></option>
												<option value="stockholmnew10"><?php esc_html_e( 'New Demo 10 - Tove', 'stockholm-core' ); ?></option>
												<option value="stockholmnew11"><?php esc_html_e( 'New Demo 11 - Solveig', 'stockholm-core' ); ?></option>
												<option value="stockholmnew12"><?php esc_html_e( 'New Demo 12 - Hilde', 'stockholm-core' ); ?></option>
												<option value="stockholmnew13"><?php esc_html_e( 'New Demo 13 - Kajsa', 'stockholm-core' ); ?></option>
												<option value="stockholmnew14"><?php esc_html_e( 'New Demo 14 - Elke', 'stockholm-core' ); ?></option>
												<option value="stockholmnew15"><?php esc_html_e( 'New Demo 15 - Anke', 'stockholm-core' ); ?></option>
												<option value="stockholmnew16"><?php esc_html_e( 'New Demo 16 - Dagmar', 'stockholm-core' ); ?></option>
												<option value="stockholmnew17"><?php esc_html_e( 'New Demo 17 - Tala', 'stockholm-core' ); ?></option>
												<option value="stockholmnew18"><?php esc_html_e( 'New Demo 18 - Oda', 'stockholm-core' ); ?></option>
												<option value="stockholmnew19"><?php esc_html_e( 'New Demo 19 - Ylva', 'stockholm-core' ); ?></option>
												<option value="stockholmnew20"><?php esc_html_e( 'New Demo 20 - Gala', 'stockholm-core' ); ?></option>
												<option value="stockholmnew21"><?php esc_html_e( 'New Demo 21 - Olaf', 'stockholm-core' ); ?></option>
												<option value="stockholmnew22"><?php esc_html_e( 'New Demo 22 - Freya', 'stockholm-core' ); ?></option>
												<option value="stockholmnew23"><?php esc_html_e( 'New Demo 23 - Gunnar', 'stockholm-core' ); ?></option>
											</select>
										</div>
										<div class="col-lg-3">
											<em class="qodef-field-description"><?php esc_html_e( 'Import Type', 'stockholm-core' ); ?></em>
											<select name="import_option" id="import_option" class="form-control qodef-form-element">
												<option value=""><?php esc_html_e( 'Please Select', 'stockholm-core' ); ?></option>
												<option value="complete_content"><?php esc_html_e( 'All', 'stockholm-core' ); ?></option>
												<option value="content"><?php esc_html_e( 'Content', 'stockholm-core' ); ?></option>
												<option value="widgets"><?php esc_html_e( 'Widgets', 'stockholm-core' ); ?></option>
												<option value="options"><?php esc_html_e( 'Options', 'stockholm-core' ); ?></option>
											</select>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="qodef-page-form-section">
							<div class="qodef-field-desc">
								<h4><?php esc_html_e( 'Import attachments', 'stockholm-core' ); ?></h4>
								<p><?php esc_html_e( 'Do you want to import media files?', 'stockholm-core' ); ?></p>
							</div>
							<div class="qodef-section-content">
								<div class="container-fluid">
									<div class="row">
										<div class="col-lg-3">
											<input type="checkbox" value="1" class="qodef-form-element" name="import_attachments" id="import_attachments"/>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-lg-3">
								<div class="form-button-section clearfix">
									<input type="submit" class="btn btn-primary btn-sm " value="<?php esc_attr_e( 'Import', 'stockholm-core' ) ?>" name="import" id="import_demo_data"/>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-lg-3"></div>
						</div>
						<div class="import_load">
							<span><?php esc_html_e( 'The import process may take some time. Please be patient.', 'stockholm-core' ) ?> </span><br/>
							<div class="qode-progress-bar-wrapper html5-progress-bar">
								<div class="progress-bar-wrapper">
									<progress id="progressbar" value="0" max="100"></progress>
								</div>
								<div class="progress-value">0%</div>
								<div class="progress-bar-message">
								</div>
							</div>
						</div>
						<div class="alert alert-warning">
							<strong><?php esc_html_e( 'Important notes:', 'stockholm-core' ) ?></strong>
							<ul>
								<li><?php esc_html_e( 'Please note that import process will take time needed to download all attachments from demo web site.', 'stockholm-core' ); ?></li>
								<li> <?php esc_html_e( 'If you plan to use shop, please install WooCommerce before you run import.', 'stockholm-core' ) ?></li>
							</ul>
						</div>
					</div>
				</div>
			</form>
		</div>
		<script type="text/javascript">
			$j(document).ready(function () {
				var index = 1;
				
				function qodeInitContentImport(import_expl, progress_bar, progress_value, import_only_content) {
					var xml_file_name = index < 10 ? 'stockholm_content_0' + index + '.xml' : 'stockholm_content_' + index + '.xml',
						import_attachments = $j("#import_attachments").is(':checked') ? 1 : 0;
					
					jQuery.ajax({
						type: 'POST',
						url: ajaxurl,
						data: {
							action: 'stockholm_qode_action_import_content',
							xml: xml_file_name,
							example: import_expl,
							import_attachments: import_attachments
						},
						success: function (data, textStatus, XMLHttpRequest) {
							qodeSetProgressValue(progress_value, progress_bar, index * 10);
						
							if (index < 10) {
								index++;
								qodeInitContentImport(import_expl, progress_bar, progress_value, import_only_content);
							} else {
								
								if (import_only_content === true) {
									qodeSetCompletedMessage(progress_value, progress_bar);
								} else {
									jQuery.ajax({
										type: 'POST',
										url: ajaxurl,
										data: {
											action: 'stockholm_qode_action_import_other_data',
											example: import_expl
										},
										success: function (data, textStatus, XMLHttpRequest) {
											qodeSetCompletedMessage(progress_value, progress_bar);
										},
										error: function (data, textStatus, XMLHttpRequest) {
											console.log('Error during import other data elements.');
										}
									});
								}
							}
						},
						error: function (data, textStatus, XMLHttpRequest) {
							console.log('Error during import attachments.');
							
							if (confirm('Some error was happened during the import. Click OK to run it again!')) {
								$j('#import_demo_data').trigger('click');
							}
						}
					});
				}
				
				function qodeSetProgressValue(progress_value, progress_bar, progress) {
					progress_value.html((progress) + '%');
					progress_bar.val(progress);
				}
				
				function qodeSetCompletedMessage(progress_value, progress_bar) {
					qodeSetProgressValue(progress_value, progress_bar, 100);
					
					$j('.progress-bar-message').html('<div class="alert alert-success"><?php esc_html_e( 'Import is completed.', 'stockholm-core' ); ?></div>');
				}
				
				$j(document).on('click', '#import_demo_data', function (e) {
					e.preventDefault();
					
					if (confirm('Are you sure, you want to import Demo Data now?')) {
						$j('.import_load').css('display', 'block');
						
						var progress_bar = $j('#progressbar'),
							progress_value = $j('.progress-value'),
							import_opt = $j("#import_option").val(),
							import_expl = $j("#import_example").val();
						
						if (import_opt === 'content') {
							qodeInitContentImport(import_expl, progress_bar, progress_value, true);
						} else if (import_opt === 'widgets') {
							jQuery.ajax({
								type: 'POST',
								url: ajaxurl,
								data: {
									action: 'stockholm_qode_action_import_widgets',
									example: import_expl
								},
								success: function (data, textStatus, XMLHttpRequest) {
									qodeSetCompletedMessage(progress_value, progress_bar);
								}
							});
						} else if (import_opt === 'options') {
							jQuery.ajax({
								type: 'POST',
								url: ajaxurl,
								data: {
									action: 'stockholm_qode_action_import_options',
									example: import_expl
								},
								success: function (data, textStatus, XMLHttpRequest) {
									qodeSetCompletedMessage(progress_value, progress_bar);
								}
							});
						} else if (import_opt === 'complete_content') {
							qodeInitContentImport(import_expl, progress_bar, progress_value);
						}
					}
					return false;
				});
			});
		</script>
		</div>
	<?php }
}

if ( ! function_exists( 'stockholm_qode_initialize_import_object' ) ) {
	function stockholm_qode_initialize_import_object() {
		$masterds_core_import_object = stockholm_qode_get_import_object();
	}
	
	add_action( 'init', 'stockholm_qode_initialize_import_object' );
}

if ( ! function_exists( 'stockholm_qode_get_import_object' ) ) {
	function stockholm_qode_get_import_object() {
		$import_object = new StockholmQodeImport();
		
		return $import_object;
	}
}

if ( ! function_exists( 'stockholm_qode_import_content' ) ) {
	function stockholm_qode_import_content() {
		$stockholm_qode_import = stockholm_qode_get_import_object();
		
		if ( $_POST['import_attachments'] == 1 ) {
			$stockholm_qode_import->attachments = true;
		} else {
			$stockholm_qode_import->attachments = false;
		}
		
		$folder = "stockholm1/";
		if ( ! empty( $_POST['example'] ) ) {
			$folder = $_POST['example'] . "/";
		}
		
		$stockholm_qode_import->import_content( $folder . $_POST['xml'] );
		
		die();
	}
	
	add_action( 'wp_ajax_stockholm_qode_action_import_content', 'stockholm_qode_import_content' );
}

if ( ! function_exists( 'stockholm_qode_import_widgets' ) ) {
	function stockholm_qode_import_widgets() {
		$stockholm_qode_import = stockholm_qode_get_import_object();
		
		$folder = "stockholm1/";
		if ( ! empty( $_POST['example'] ) ) {
			$folder = $_POST['example'] . "/";
		}
		
		$stockholm_qode_import->import_widgets( $folder . 'widgets.txt' );
		
		die();
	}
	
	add_action( 'wp_ajax_stockholm_qode_action_import_widgets', 'stockholm_qode_import_widgets' );
}

if ( ! function_exists( 'stockholm_qode_import_options' ) ) {
	function stockholm_qode_import_options() {
		$stockholm_qode_import = stockholm_qode_get_import_object();
		
		$folder = "stockholm1/";
		if ( ! empty( $_POST['example'] ) ) {
			$folder = $_POST['example'] . "/";
		}
		
		$stockholm_qode_import->import_options( $folder . 'options.txt' );
		$stockholm_qode_import->update_options_after_import( $folder );
		
		die();
	}
	
	add_action( 'wp_ajax_stockholm_qode_action_import_options', 'stockholm_qode_import_options' );
}

if ( ! function_exists( 'stockholm_qode_import_other_data' ) ) {
	function stockholm_qode_import_other_data() {
		$stockholm_qode_import = stockholm_qode_get_import_object();
		
		$folder = "stockholm1/";
		if ( ! empty( $_POST['example'] ) ) {
			$folder = $_POST['example'] . "/";
		}
		
		$stockholm_qode_import->import_options( $folder . 'options.txt' );
		$stockholm_qode_import->import_widgets( $folder . 'widgets.txt' );
		$stockholm_qode_import->import_menus( $folder . 'menus.txt' );
		$stockholm_qode_import->import_settings_pages( $folder . 'settingpages.txt' );

		$stockholm_qode_import->update_meta_fields_after_import( $folder );
		$stockholm_qode_import->update_options_after_import( $folder );
		
		die();
	}
	
	add_action( 'wp_ajax_stockholm_qode_action_import_other_data', 'stockholm_qode_import_other_data' );
}