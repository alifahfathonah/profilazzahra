<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class StockholmQodeInstagramWidget extends WP_Widget {
	private $params;
	
	public function __construct() {
		parent::__construct(
			'qode_instagram_widget',
			esc_html__( 'Stockholm Instagram Widget', 'select-instagram-feed' ),
			array( 'description' => esc_html__( 'Display your Instagram feed', 'select-instagram-feed' ) )
		);
		
		$this->setParams();
	}
	
	private function setParams() {
		$this->params = array(
			array(
				'name'  => 'title',
				'type'  => 'textfield',
				'title' => esc_html__( 'Title', 'select-instagram-feed' )
			),
			array(
				'name'  => 'tag',
				'type'  => 'textfield',
				'title' => esc_html__( 'Tag', 'select-instagram-feed' )
			),
			array(
				'name'  => 'number_of_photos',
				'type'  => 'textfield',
				'title' => esc_html__( 'Number of photos', 'select-instagram-feed' )
			),
			array(
				'name'    => 'number_of_cols',
				'type'    => 'dropdown',
				'title'   => esc_html__( 'Number of columns', 'select-instagram-feed' ),
				'options' => array(
					'2' => esc_html__( 'Two', 'select-instagram-feed' ),
					'3' => esc_html__( 'Three', 'select-instagram-feed' ),
					'4' => esc_html__( 'Four', 'select-instagram-feed' ),
					'5' => esc_html__( 'Five', 'select-instagram-feed' ),
					'6' => esc_html__( 'Six', 'select-instagram-feed' ),
					'7' => esc_html__( 'Seven', 'select-instagram-feed' ),
					'8' => esc_html__( 'Eight', 'select-instagram-feed' )
				)
			),
			array(
				'name'    => 'image_size',
				'type'    => 'dropdown',
				'title'   => esc_html__( 'Image Size', 'select-instagram-feed' ),
				'options' => array(
					'thumbnail'           => esc_html__( 'Small', 'select-instagram-feed' ),
					'low_resolution'      => esc_html__( 'Medium', 'select-instagram-feed' ),
					'standard_resolution' => esc_html__( 'Large', 'select-instagram-feed' )
				)
			),
			array(
				'name'  => 'transient_time',
				'type'  => 'textfield',
				'title' => esc_html__( 'Images Cache Time', 'select-instagram-feed' )
			),
		);
	}
	
	public function widget( $args, $instance ) {
		extract( $instance );
		
		echo wp_kses_post( $args['before_widget'] );
		echo wp_kses_post( $args['before_title'] . $title . $args['after_title'] );
		
		$instagram_api = StockholmQodeInstagramApi::getInstance();
		$images_array  = $instagram_api->getImages( $number_of_photos, $tag, array(
			'use_transients' => true,
			'transient_name' => $args['widget_id'],
			'transient_time' => $transient_time
		) );
		
		$number_of_cols = $number_of_cols == '' ? 3 : $number_of_cols;
		
		if ( is_array( $images_array ) && count( $images_array ) ) { ?>
			<ul class="qode_instagram_feed clearfix col_<?php echo esc_attr( $number_of_cols ); ?>">
				<?php
				foreach ( $images_array as $image ) { ?>
					<li>
						<a href="<?php echo esc_url( $instagram_api->getHelper()->getImageLink( $image ) ); ?>" target="_blank">
							<?php echo stockholm_qode_get_module_part( $instagram_api->getHelper()->getImageHTML( $image, $image_size ) ); ?>
						</a>
					</li>
				<?php } ?>
			</ul>
		<?php }
		
		echo wp_kses_post( $args['after_widget'] );
	}
	
	public function form( $instance ) {
		foreach ( $this->params as $param_array ) {
			$param_name    = $param_array['name'];
			${$param_name} = isset( $instance[ $param_name ] ) ? esc_attr( $instance[ $param_name ] ) : '';
		}
		
		//if code wasn't saved to database
		if ( ! get_option( 'qode_instagram_code' ) ) {
			echo '<p>' . esc_html__( "Please go to Select Options and reconnect with Instagram", "select-instagram-feed" ) . '</p>';
		}
		
		//user has connected with instagram. Show form
		if ( get_option( 'qode_instagram_code' ) ) {
			foreach ( $this->params as $param ) {
				switch ( $param['type'] ) {
					case 'textfield':
						?>
						<p>
							<label for="<?php echo esc_attr( $this->get_field_id( $param['name'] ) ); ?>"><?php echo esc_html( $param['title'] ); ?></label>
							<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( $param['name'] ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( $param['name'] ) ); ?>" type="text" value="<?php echo esc_attr( ${$param['name']} ); ?>"/>
						</p>
						<?php
						break;
					case 'dropdown':
						?>
						<p>
							<label for="<?php echo esc_attr( $this->get_field_id( $param['name'] ) ); ?>"><?php echo esc_html( $param['title'] ); ?></label>
							<?php if ( isset( $param['options'] ) && is_array( $param['options'] ) && count( $param['options'] ) ) { ?>
								<select class="widefat" name="<?php echo esc_attr( $this->get_field_name( $param['name'] ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( $param['name'] ) ); ?>">
									<?php foreach ( $param['options'] as $param_option_key => $param_option_val ) {
										$option_selected = '';
										if ( ${$param['name']} == $param_option_key ) {
											$option_selected = 'selected';
										}
										?>
										<option <?php echo esc_attr( $option_selected ); ?> value="<?php echo esc_attr( $param_option_key ); ?>"><?php echo esc_attr( $param_option_val ); ?></option>
									<?php } ?>
								</select>
							<?php } ?>
						</p>
						<?php
						break;
				}
			}
		}
	}
	
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		foreach ( $this->params as $param ) {
			$param_name = $param['name'];
			
			$instance[ $param_name ] = sanitize_text_field( $new_instance[ $param_name ] );
		}
		
		return $instance;
	}
}

if ( ! function_exists( 'stockholm_qode_instagram_widget_load' ) ) {
	function stockholm_qode_instagram_widget_load( $widgets ) {
		$widgets[] = 'StockholmQodeInstagramWidget';
		
		return $widgets;
	}
	
	add_filter( 'stockholm_qode_filter_register_widgets', 'stockholm_qode_instagram_widget_load' );
}