<?php

namespace Stockholm\Shortcodes\SimpleBlogList;

use Stockholm\Shortcodes\Lib\ShortcodeInterface;

class SimpleBlogList implements ShortcodeInterface {
	private $base;
	
	public function __construct() {
		$this->base = 'qode_simple_blog_list';
		
		add_action( 'stockholm_qode_action_vc_map', array( $this, 'vcMap' ) );
	}
	
	public function getBase() {
		return $this->base;
	}
	
	public function vcMap() {
		vc_map( array(
			'name'     => esc_html__( 'Simple Blog List', 'stockholm-core' ),
			'base'     => $this->base,
			'category' => esc_html__( 'by SELECT', 'stockholm-core' ),
			'icon'     => 'icon-wpb-simple-blog-list extended-custom-icon-qode',
			'params'   => array(
				array(
					'type'       => 'dropdown',
					'heading'    => esc_html__( 'Posts Title Tag', 'stockholm-core' ),
					'param_name' => 'posts_title_tag',
					'value'      => array_flip( stockholm_qode_get_title_tag( true ) )
				),
				array(
					'type'       => 'textfield',
					'heading'    => 'Excerpt Length',
					'param_name' => 'excerpt_length',
				),
				array(
					'type'       => 'dropdown',
					'heading'    => esc_html__( 'Number of Columns', 'stockholm-core' ),
					'param_name' => 'number_of_columns',
					'value'      => array(
						'3' => '3',
						'4' => '4',
						'5' => '5'
					)
				),
				array(
					'type'       => 'dropdown',
					'heading'    => esc_html__( 'Read More Hover Animation', 'stockholm-core' ),
					'param_name' => 'read_more_hover_animation',
					'value'      => array(
						esc_html__( 'Default', 'stockholm-core' )      => '',
						esc_html__( 'Display Dash', 'stockholm-core' ) => 'display-dash'
					)
				),
				array(
					'type'       => 'dropdown',
					'heading'    => esc_html__( 'Enable Blog List Animation', 'stockholm-core' ),
					'param_name' => 'blog_list_animation',
					'value'      => array(
						esc_html__( 'No', 'stockholm-core' )  => 'no',
						esc_html__( 'Yes', 'stockholm-core' ) => 'yes'
					)
				),
				array(
					'type'        => 'textfield',
					'heading'     => esc_html__( 'Number', 'stockholm-core' ),
					'param_name'  => 'number',
					'description' => esc_html__( 'Number of blog posts on page (Leave empty for all)', 'stockholm-core' ),
					'group'       => esc_html__( 'Build Query', 'stockholm-core' )
				),
				array(
					'type'       => 'dropdown',
					'heading'    => esc_html__( 'Order By', 'stockholm-core' ),
					'param_name' => 'order_by',
					'value'      => array(
						esc_html__( 'Date', 'stockholm-core' )  => 'date',
						esc_html__( 'Title', 'stockholm-core' ) => 'title'
					),
					'group'      => esc_html__( 'Build Query', 'stockholm-core' )
				),
				array(
					'type'       => 'dropdown',
					'heading'    => esc_html__( 'Order', 'stockholm-core' ),
					'param_name' => 'order',
					'value'      => array_flip( stockholm_qode_get_query_order_array() ),
					'group'      => esc_html__( 'Build Query', 'stockholm-core' )
				),
				array(
					'type'        => 'textfield',
					'heading'     => esc_html__( 'Category', 'stockholm-core' ),
					'param_name'  => 'category',
					'description' => esc_html__( 'Category Slug (leave empty for all)', 'stockholm-core' ),
					'group'       => esc_html__( 'Build Query', 'stockholm-core' )
				),
				array(
					'type'        => 'textfield',
					'heading'     => esc_html__( 'Selected Posts', 'stockholm-core' ),
					'param_name'  => 'selected_projects',
					'description' => esc_html__( 'Selected Posts (leave empty for all, delimit by comma)', 'stockholm-core' ),
					'group'       => esc_html__( 'Build Query', 'stockholm-core' )
				)
			)
		) );
	}
	
	public function render( $atts, $content = null ) {
		$args = array(
			'posts_title_tag'           => 'h3',
			'excerpt_length'            => '',
			'number_of_columns'         => '3',
			'read_more_hover_animation' => '3',
			'blog_list_animation'       => 'no',
			'number'                    => '-1',
			'order'                     => 'DESC',
			'order_by'                  => 'date',
			'category'                  => '',
			'selected_projects'         => '',
			'title_color'               => ''
		);
		$params = shortcode_atts( $args, $atts );
		
		$query_args                  = $this->createQueryArgs( $params );
		$blog_query                  = new \WP_Query( $query_args );
		$params['blog_query']        = $blog_query;
		$params['holder_classes']    = $this->holderClasses( $params );
		$params['read_more_classes'] = $this->readMoreClasses( $params );
		
		return stockholm_qode_get_shortcode_template_part( 'templates/simple-blog-list-template', 'simple-blog-list', '', $params );
	}
	
	private function createQueryArgs( $params ) {
		$args = array(
			'post_status'    => 'publish',
			'post_type'      => 'post',
			'orderby'        => $params['order_by'],
			'order'          => $params['order'],
			'posts_per_page' => $params['number']
		);
		
		if ( $params['category'] !== '' ) {
			$args['category_name'] = $params['category'];
		}
		
		if ( $params['selected_projects'] !== '' ) {
			$args['post__in'] = explode( ",", $params['selected_projects'] );
		}
		
		return $args;
	}
	
	private function holderClasses( $params ) {
		$classes = array( 'qode-simple-blog-list' );
		
		if ( $params['number_of_columns'] != '' ) {
			$classes[] = 'qode-sbl-' . $params['number_of_columns'] . '-columns';
		}
		
		if ( $params['blog_list_animation'] == 'yes' ) {
			$classes[] = 'qode-sbl-animated';
		}
		
		return $classes;
	}
	
	private function readMoreClasses( $params ) {
		$classes = array( '' );
		
		if ( $params['read_more_hover_animation'] == 'display-dash' ) {
			$classes[] = 'display_dash';
		}
		
		return implode( ' ', $classes );
	}
}