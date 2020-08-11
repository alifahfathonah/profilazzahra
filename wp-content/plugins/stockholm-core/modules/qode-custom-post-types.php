<?php

/* Create Portfolio, Testimonial, Slider and Carousel post type */
if ( ! function_exists( 'stockholm_qode_create_post_type' ) ) {
	function stockholm_qode_create_post_type() {
		$global_options = stockholm_qode_return_global_options();
		
		$slug = 'portfolio_page';
		if ( isset( $global_options['portfolio_single_slug'] ) && $global_options['portfolio_single_slug'] != "" ) {
			$slug = $global_options['portfolio_single_slug'];
		}
		
		register_post_type( 'portfolio_page',
			array(
				'labels'        => array(
					'name'          => esc_html__( 'Select Portfolio', 'stockholm-core' ),
					'singular_name' => esc_html__( 'Select Portfolio Item', 'stockholm-core' ),
					'add_item'      => esc_html__( 'New Portfolio Item', 'stockholm-core' ),
					'add_new_item'  => esc_html__( 'Add New Portfolio Item', 'stockholm-core' ),
					'edit_item'     => esc_html__( 'Edit Portfolio Item', 'stockholm-core' )
				),
				'public'        => true,
				'has_archive'   => true,
				'rewrite'       => array( 'slug' => $slug ),
				'menu_position' => 4,
				'show_ui'       => true,
				'supports'      => array(
					'author',
					'title',
					'editor',
					'thumbnail',
					'excerpt',
					'page-attributes',
					'comments'
				),
				'menu_icon'     => QODE_ROOT . '/img/favicon.ico'
			)
		);
		
		register_post_type( 'testimonials',
			array(
				'labels'        => array(
					'name'          => esc_html__( 'Select Testimonials', 'stockholm-core' ),
					'singular_name' => esc_html__( 'Select Testimonial', 'stockholm-core' ),
					'add_item'      => esc_html__( 'New Testimonial', 'stockholm-core' ),
					'add_new_item'  => esc_html__( 'Add New Testimonial', 'stockholm-core' ),
					'edit_item'     => esc_html__( 'Edit Testimonial', 'stockholm-core' )
				),
				'public'        => false,
				'show_in_menu'  => true,
				'rewrite'       => array( 'slug' => 'testimonials' ),
				'menu_position' => 4,
				'show_ui'       => true,
				'has_archive'   => false,
				'hierarchical'  => false,
				'supports'      => array( 'title', 'thumbnail' ),
				'menu_icon'     => QODE_ROOT . '/img/favicon.ico'
			)
		);
		
		register_post_type( 'slides',
			array(
				'labels'        => array(
					'name'          => esc_html__( 'Select Slider', 'stockholm-core' ),
					'menu_name'     => esc_html__( 'Select Slider', 'stockholm-core' ),
					'all_items'     => esc_html__( 'Slides', 'stockholm-core' ),
					'add_new'       => esc_html__( 'Add New Slide', 'stockholm-core' ),
					'singular_name' => esc_html__( 'Slide', 'stockholm-core' ),
					'add_item'      => esc_html__( 'New Slide', 'stockholm-core' ),
					'add_new_item'  => esc_html__( 'Add New Slide', 'stockholm-core' ),
					'edit_item'     => esc_html__( 'Edit Slide', 'stockholm-core' )
				),
				'public'        => false,
				'show_in_menu'  => true,
				'rewrite'       => array( 'slug' => 'slides' ),
				'menu_position' => 4,
				'show_ui'       => true,
				'has_archive'   => false,
				'hierarchical'  => false,
				'supports'      => array( 'title', 'thumbnail', 'page-attributes' ),
				'menu_icon'     => QODE_ROOT . '/img/favicon.ico'
			)
		);
		
		register_post_type( 'carousels',
			array(
				'labels'        => array(
					'name'          => esc_html__( 'Select Carousel', 'stockholm-core' ),
					'menu_name'     => esc_html__( 'Select Carousel', 'stockholm-core' ),
					'all_items'     => esc_html__( 'Carousel Items', 'stockholm-core' ),
					'add_new'       => esc_html__( 'Add New Carousel Item', 'stockholm-core' ),
					'singular_name' => esc_html__( 'Carousel Item', 'stockholm-core' ),
					'add_item'      => esc_html__( 'New Carousel Item', 'stockholm-core' ),
					'add_new_item'  => esc_html__( 'Add New Carousel Item', 'stockholm-core' ),
					'edit_item'     => esc_html__( 'Edit Carousel Item', 'stockholm-core' )
				),
				'public'        => false,
				'show_in_menu'  => true,
				'rewrite'       => array( 'slug' => 'carousels' ),
				'menu_position' => 4,
				'show_ui'       => true,
				'has_archive'   => false,
				'hierarchical'  => false,
				'supports'      => array( 'title' ),
				'menu_icon'     => QODE_ROOT . '/img/favicon.ico'
			)
		);
		
		/* Create Portfolio Categories */
		
		$labels = array(
			'name'              => esc_html__( 'Portfolio Categories', 'stockholm-core' ),
			'singular_name'     => esc_html__( 'Portfolio Category', 'stockholm-core' ),
			'search_items'      => esc_html__( 'Search Portfolio Categories', 'stockholm-core' ),
			'all_items'         => esc_html__( 'All Portfolio Categories', 'stockholm-core' ),
			'parent_item'       => esc_html__( 'Parent Portfolio Category', 'stockholm-core' ),
			'parent_item_colon' => esc_html__( 'Parent Portfolio Category:', 'stockholm-core' ),
			'edit_item'         => esc_html__( 'Edit Portfolio Category', 'stockholm-core' ),
			'update_item'       => esc_html__( 'Update Portfolio Category', 'stockholm-core' ),
			'add_new_item'      => esc_html__( 'Add New Portfolio Category', 'stockholm-core' ),
			'new_item_name'     => esc_html__( 'New Portfolio Category Name', 'stockholm-core' ),
			'menu_name'         => esc_html__( 'Portfolio Categories', 'stockholm-core' ),
		);
		
		register_taxonomy( 'portfolio_category', array( 'portfolio_page' ), array(
			'hierarchical'      => true,
			'labels'            => $labels,
			'show_ui'           => true,
			'query_var'         => true,
			'show_admin_column' => true,
			'rewrite'           => array( 'slug' => 'portfolio-category' ),
		) );
		
		/* Create Portfolio Tags */
		
		$labels = array(
			'name'              => esc_html__( 'Portfolio Tags', 'stockholm-core' ),
			'singular_name'     => esc_html__( 'Portfolio Tag', 'stockholm-core' ),
			'search_items'      => esc_html__( 'Search Portfolio Tags', 'stockholm-core' ),
			'all_items'         => esc_html__( 'All Portfolio Tags', 'stockholm-core' ),
			'parent_item'       => esc_html__( 'Parent Portfolio Tag', 'stockholm-core' ),
			'parent_item_colon' => esc_html__( 'Parent Portfolio Tags:', 'stockholm-core' ),
			'edit_item'         => esc_html__( 'Edit Portfolio Tag', 'stockholm-core' ),
			'update_item'       => esc_html__( 'Update Portfolio Tag', 'stockholm-core' ),
			'add_new_item'      => esc_html__( 'Add New Portfolio Tag', 'stockholm-core' ),
			'new_item_name'     => esc_html__( 'New Portfolio Tag Name', 'stockholm-core' ),
			'menu_name'         => esc_html__( 'Portfolio Tags', 'stockholm-core' ),
		);
		
		register_taxonomy( 'portfolio_tag', array( 'portfolio_page' ), array(
			'hierarchical' => false,
			'labels'       => $labels,
			'show_ui'      => true,
			'query_var'    => true,
			'rewrite'      => array( 'slug' => 'portfolio-tag' ),
		) );
		
		/* Create Testimonials Category */
		
		$labels = array(
			'name'              => esc_html__( 'Testimonials Categories', 'stockholm-core' ),
			'singular_name'     => esc_html__( 'Testimonial Category', 'stockholm-core' ),
			'search_items'      => esc_html__( 'Search Testimonials Categories', 'stockholm-core' ),
			'all_items'         => esc_html__( 'All Testimonials Categories', 'stockholm-core' ),
			'parent_item'       => esc_html__( 'Parent Testimonial Category', 'stockholm-core' ),
			'parent_item_colon' => esc_html__( 'Parent Testimonial Category:', 'stockholm-core' ),
			'edit_item'         => esc_html__( 'Edit Testimonials Category', 'stockholm-core' ),
			'update_item'       => esc_html__( 'Update Testimonials Category', 'stockholm-core' ),
			'add_new_item'      => esc_html__( 'Add New Testimonials Category', 'stockholm-core' ),
			'new_item_name'     => esc_html__( 'New Testimonials Category Name', 'stockholm-core' ),
			'menu_name'         => esc_html__( 'Testimonials Categories', 'stockholm-core' ),
		);
		
		register_taxonomy( 'testimonials_category', array( 'testimonials' ), array(
			'hierarchical'      => true,
			'labels'            => $labels,
			'show_ui'           => true,
			'query_var'         => true,
			'show_admin_column' => true,
			'rewrite'           => array( 'slug' => 'testimonials-category' ),
		) );
		
		/* Create Slider Category */
		
		$labels = array(
			'name'              => esc_html__( 'Sliders', 'stockholm-core' ),
			'singular_name'     => esc_html__( 'Slider', 'stockholm-core' ),
			'search_items'      => esc_html__( 'Search Sliders', 'stockholm-core' ),
			'all_items'         => esc_html__( 'All Sliders', 'stockholm-core' ),
			'parent_item'       => esc_html__( 'Parent Slider', 'stockholm-core' ),
			'parent_item_colon' => esc_html__( 'Parent Slider:', 'stockholm-core' ),
			'edit_item'         => esc_html__( 'Edit Slider', 'stockholm-core' ),
			'update_item'       => esc_html__( 'Update Slider', 'stockholm-core' ),
			'add_new_item'      => esc_html__( 'Add New Slider', 'stockholm-core' ),
			'new_item_name'     => esc_html__( 'New Slider Name', 'stockholm-core' ),
			'menu_name'         => esc_html__( 'Sliders', 'stockholm-core' ),
		);
		
		register_taxonomy( 'slides_category', array( 'slides' ), array(
			'hierarchical'      => true,
			'labels'            => $labels,
			'show_ui'           => true,
			'query_var'         => true,
			'show_admin_column' => true,
			'rewrite'           => array( 'slug' => 'slides-category' ),
		) );
		
		/* Create Carousel Category */
		
		$labels = array(
			'name'              => esc_html__( 'Carousels', 'stockholm-core' ),
			'singular_name'     => esc_html__( 'Carousel', 'stockholm-core' ),
			'search_items'      => esc_html__( 'Search Carousels', 'stockholm-core' ),
			'all_items'         => esc_html__( 'All Carousels', 'stockholm-core' ),
			'parent_item'       => esc_html__( 'Parent Carousel', 'stockholm-core' ),
			'parent_item_colon' => esc_html__( 'Parent Carousel:', 'stockholm-core' ),
			'edit_item'         => esc_html__( 'Edit Carousel', 'stockholm-core' ),
			'update_item'       => esc_html__( 'Update Carousel', 'stockholm-core' ),
			'add_new_item'      => esc_html__( 'Add New Carousel', 'stockholm-core' ),
			'new_item_name'     => esc_html__( 'New Carousel Name', 'stockholm-core' ),
			'menu_name'         => esc_html__( 'Carousels', 'stockholm-core' ),
		);
		
		register_taxonomy( 'carousels_category', array( 'carousels' ), array(
			'hierarchical'      => true,
			'labels'            => $labels,
			'show_ui'           => true,
			'query_var'         => true,
			'show_admin_column' => true,
			'rewrite'           => array( 'slug' => 'carousels-category' ),
		) );
	}
	
	add_action( 'init', 'stockholm_qode_create_post_type', 0 );
}