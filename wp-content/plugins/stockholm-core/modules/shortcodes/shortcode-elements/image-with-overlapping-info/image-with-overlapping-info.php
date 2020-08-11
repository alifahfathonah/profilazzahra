<?php

namespace Stockholm\Shortcodes\ImageWithOverlappingInfo;

use Stockholm\Shortcodes\Lib\ShortcodeInterface;

class ImageWithOverlappingInfo implements ShortcodeInterface {
	private $base;
	
	public function __construct() {
		$this->base = 'qode_image_with_overlapping_info';
		
		add_action( 'stockholm_qode_action_vc_map', array( $this, 'vcMap' ) );
	}
	
	public function getBase() {
		return $this->base;
	}

	public function vcMap() {
		vc_map( array(
			'name'     => esc_html__( 'Image With Overlapping Info', 'stockholm-core' ),
			'base'     => $this->base,
			'category' => esc_html__( 'by SELECT', 'stockholm-core' ),
			'icon'     => 'icon-wpb-image-with-overlapping-info extended-custom-icon-qode',
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
					'heading'    => esc_html__( 'Link', 'stockholm-core' ),
					'param_name' => 'link'
				),
				array(
					'type'       => 'dropdown',
					'heading'    => esc_html__( 'Target', 'stockholm-core' ),
					'param_name' => 'link_target',
					'value'      => array_flip( stockholm_qode_get_link_target_array() )
				),
				array(
					'type'       => 'textfield',
					'heading'    => esc_html__( 'Link Text', 'stockholm-core' ),
					'param_name' => 'link_text'
				),
				array(
					'type'       => 'dropdown',
					'heading'    => esc_html__( 'Link Hover Animation', 'stockholm-core' ),
					'param_name' => 'link_hover_animation',
					'value'      => array(
						esc_html__( 'Default', 'stockholm-core' )      => '',
						esc_html__( 'Display Dash', 'stockholm-core' ) => 'display-dash'
					)
				)
			)
		) );
	}
	
	public function render( $atts, $content = null ) {
		$args = array(
			'image'                => '',
			'title'                => '',
			'title_tag'            => 'h3',
			'text'                 => '',
			'link'                 => '',
			'link_target'          => '',
			'link_text'            => '',
			'link_hover_animation' => ''
		);
		$params = shortcode_atts( $args, $atts );
		
		$params['image_src']    = wp_get_attachment_url( $params['image'] );
		$params['link_classes'] = $this->linkClasses( $params );
		
		return stockholm_qode_get_shortcode_template_part( 'templates/image-with-overlapping-info-template', 'image-with-overlapping-info', '', $params );
	}
	
	private function linkClasses( $params ) {
		$classes = array( 'qode-iwoi-link' );
		
		if ( $params['link_hover_animation'] == 'display-dash' ) {
			$classes[] = 'display_dash';
		}
		
		return implode( ' ', $classes );
	}
}