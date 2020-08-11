<?php

namespace Stockholm\Shortcodes\InteractiveImage;

use Stockholm\Shortcodes\Lib\ShortcodeInterface;

class InteractiveImage implements ShortcodeInterface {
	private $base;
	
	public function __construct() {
		$this->base = 'qode_interactive_image';
		
		add_action( 'stockholm_qode_action_vc_map', array( $this, 'vcMap' ) );
	}
	
	public function getBase() {
		return $this->base;
	}
	
	public function vcMap() {
		vc_map( array(
			'name'     => esc_html__( 'Interactive Image', 'stockholm-core' ),
			'base'     => $this->base,
			'category' => esc_html__( 'by SELECT', 'stockholm-core' ),
			'icon'     => 'icon-wpb-interactive-image extended-custom-icon-qode',
			'params'   => array(
				array(
					'type'        => 'attach_image',
					'heading'     => esc_html__( 'Image', 'stockholm-core' ),
					'param_name'  => 'image',
					'admin_label' => true
				),
				array(
					'type'        => 'textfield',
					'heading'     => esc_html__( 'Title', 'stockholm-core' ),
					'param_name'  => 'title',
					'admin_label' => true
				),
				array(
					'type'       => 'dropdown',
					'heading'    => esc_html__( 'Title Tag', 'stockholm-core' ),
					'param_name' => 'title_tag',
					'value'      => array_flip( stockholm_qode_get_title_tag( true ) )
				),
				array(
					'type'       => 'textarea',
					'heading'    => esc_html__( 'Text', 'stockholm-core' ),
					'param_name' => 'text'
				),
				array(
					'type'       => 'textfield',
					'heading'    => esc_html__( 'Button Link', 'stockholm-core' ),
					'param_name' => 'link'
				),
				array(
					'type'       => 'dropdown',
					'heading'    => esc_html__( 'Button Target', 'stockholm-core' ),
					'param_name' => 'link_target',
					'value'      => array_flip( stockholm_qode_get_link_target_array() )
				),
				array(
					'type'       => 'textfield',
					'heading'    => esc_html__( 'Button Text', 'stockholm-core' ),
					'param_name' => 'link_text'
				),
				array(
					'type'       => 'dropdown',
					'heading'    => esc_html__( 'Button Style', 'stockholm-core' ),
					'param_name' => 'link_style',
					'value'      => array(
						esc_html__( 'Default', 'stockholm-core' )    => '',
						esc_html__( 'White', 'stockholm-core' )      => 'white',
						esc_html__( 'Underlined', 'stockholm-core' ) => 'underlined'
					)
				),
				array(
					'type'       => 'checkbox',
					'heading'    => esc_html__( 'Set as active', 'stockholm-core' ),
					'param_name' => 'active'
				)
			)
		) );
	}
	
	public function render( $atts, $content = null ) {
		$args = array(
			'image'       => '',
			'title'       => '',
			'title_tag'   => 'h2',
			'text'        => '',
			'link'        => '',
			'link_target' => '',
			'link_text'   => '',
			'link_style'  => '',
			'active'      => 'false'
		);
		$params = shortcode_atts( $args, $atts );
		
		$params['holderClasses'] = $this->getHolderClasses( $params );
		$params['image_src']     = wp_get_attachment_url( $params['image'] );
		
		return stockholm_qode_get_shortcode_template_part( 'templates/interactive-image', 'interactive-image', '', $params );
	}
	
	private function getHolderClasses( $params ) {
		$classes = '';
		
		if ( $params['active'] == 'true' ) {
			$classes = 'active';
		}
		
		return $classes;
	}
}